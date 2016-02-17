<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class CheckIfInstagramPostsContainAlreadyExtractedPost
{
    protected $userId;
    protected $posts;

    public function __construct($posts, $userId)
    {
        $this->userId = $userId;
        $this->posts = $posts;
    }

    public function check() {
        $postInDB = false;

        foreach ($this->posts as $instagramPost) {
            if($this->checkPostInDB($instagramPost->id, $this->userId)) {
                $postInDB = true;
            }
        }
        return $postInDB;
    }

    private function checkPostInDB($postId, $userId) {
        $instagramPostsFromUser = SocialPost::join('user_social_post', 'social_post.id', '=', 'user_social_post.social_post_id')->where('user_social_post.user_id', '=', $userId)->where('social_post.type', '=', 'instagram')->get();
        foreach ($instagramPostsFromUser as $instagramPost) {
            $post = json_decode($instagramPost->post);
            if($post->id === $postId) {
                return true;
            }
        }
        return false;
    }
}
