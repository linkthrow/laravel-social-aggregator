<?php namespace LinkThrow\LaravelSocialAggregator\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestFacebookPostsForUser;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetLatestFacebookPosts extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    public function handle()
    {
        $userFacebookTokens = UserSocialToken::where('type', '=', 'facebook')
        ->where(function ($query) {
            $query->where("expires_at", '>', Carbon::now())
                ->orWhereNull("expires_at");
        })->get();

        foreach ($userFacebookTokens as &$userFacebookToken) {
            $this->dispatch(new GetLatestFacebookPostsForUser($userFacebookToken));
        }
    }
}
