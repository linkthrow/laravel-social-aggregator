<?php namespace LinkThrow\LaravelSocialAggregator;

use Config;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Facebook;
use App;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use \Facebook\Entities\AccessToken;
use Carbon\Carbon;
use DB;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestInstagramPosts;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestTwitterPosts;
use LinkThrow\LaravelSocialAggregator\Jobs\GetLatestFacebookPosts;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Instagram;

class SocialAggregator {
    use DispatchesJobs;

    public function updateInstagramPosts()
    {
        $this->dispatch(new GetLatestInstagramPosts());
    }

    public function updateFacebookPosts()
    {
        $this->dispatch(new GetLatestFacebookPosts());
    }

    public function updateTwitterPosts()
    {
        $this->dispatch(new GetLatestTwitterPosts());
    }
}
