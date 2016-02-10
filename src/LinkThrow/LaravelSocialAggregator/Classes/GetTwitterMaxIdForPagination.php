<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class GetTwitterMaxIdForPagination
{
    protected $tweets;

    public function __construct($tweets)
    {
        $this->tweets = $tweets;
    }

    public function extract()
    {
        if(count($this->tweets) > 0) {
            $lastId = $this->tweets[0]->id;

            foreach ($this->tweets as $tweet) {
                $lastId = ($tweet->id < $lastId) ? $tweet->id : $lastId;
            }

            return $lastId - 1;
        } else {
            return 0;
        }
    }
}
