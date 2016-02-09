<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class GetTwitterSinceIdForPagination
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function retrieve()
    {
        $twitterPosts = SocialPost::join('user_social_post', 'social_post.id', '=', 'user_social_post.social_post_id')->where('user_social_post.user_id', '=', $this->userId)->where('social_post.type', '=', 'twitter')->select('social_post.*')->get();
        $maxId = 0;
        foreach ($twitterPosts as $post) {
            $postJSON = json_decode($post->post);
            $maxId = ($postJSON->id > $maxId) ? $postJSON->id : $maxId;
        }
        return $maxId;
    }
}
