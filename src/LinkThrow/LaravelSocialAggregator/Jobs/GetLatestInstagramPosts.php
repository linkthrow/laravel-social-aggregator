<?php namespace LinkThrow\LaravelSocialAggregator\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestInstagramPostsForUser;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetLatestInstagramPosts extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    public function handle()
    {
        $userInstagramTokens = UserSocialToken::where('type', '=', 'instagram')
        ->where(function ($query) {
            $query->where("expires_at", '>', Carbon::now())
                ->orWhereNull("expires_at");
        })
        ->whereNotNull('user_social_token.user_id')->get();
        foreach ($userInstagramTokens as &$userInstagramToken) {
            $this->dispatch(new GetLatestInstagramPostsForUser($userInstagramToken));
        }
    }
}
