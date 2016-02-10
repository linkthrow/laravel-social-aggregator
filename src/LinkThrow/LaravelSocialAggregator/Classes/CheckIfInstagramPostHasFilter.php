<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class CheckIfInstagramPostHasFilter
{
    protected $hashtags;
    protected $hashtag;

    public function __construct(array $hashtags, $hashtag)
    {
        $this->hashtags = $hashtags;
        $this->hashtag = $hashtag;
    }

    public function check() {
        foreach ($this->hashtags as $hashtag) {
            if($hashtag === $this->hashtag) {
                return true;
            }
        }
        return false;
    }
}
