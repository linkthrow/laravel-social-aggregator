<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class GetInstagramMaxIdForPagination
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function retrieve()
    {
        return 0;
        $instagramPosts = SocialPost::join('user_social_post', 'user_social_post.social_post_id', '=', 'social_post.id')->where('social_post.type', '=', 'instagram')->where('user_social_post.user_id', '=', $this->userId)->get();
    }
}
