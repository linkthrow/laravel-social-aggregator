<?php namespace LinkThrow\LaravelSocialAggregator\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestTwitterPostsForUser;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetLatestTwitterPosts extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    public function handle()
    {
        $userTwitterTokens = UserSocialToken::where('type', '=', 'twitter')
        ->where(function ($query) {
            $query->where("expires_at", '>', Carbon::now())
                ->orWhereNull("expires_at");
        })
        ->whereNotNull('user_social_token.user_id')->get();
        foreach ($userTwitterTokens as &$userTwitterToken) {
            $this->dispatch(new GetLatestTwitterPostsForUser($userTwitterToken));
        }
    }
}
