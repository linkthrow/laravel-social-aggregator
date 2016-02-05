<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class AssignFacebookPostsToDatabase
{
    protected $userId;
    protected $post;

    public function __construct($userId, $post)
    {
        $this->userId = $userId;
        $this->post = $post;
    }

    public function assign()
    {
        DB::transaction(function () {
            $postCreatedTimestamp = strtotime($this->post['created_time']);
            $postCreatedTime = Carbon::createFromTimestamp($postCreatedTimestamp);

            //Save the post
            $socialPost = new SocialPost;
            $socialPost->type = 'facebook';
            $socialPost->post = json_encode($this->post);
            $socialPost->created_at = $postCreatedTime;
            $socialPost->save();

            //Assign social post to the user
            $userSocialPost = new UserSocialPost;
            $userSocialPost->user_id = $this->userId;
            $userSocialPost->social_post_id = $socialPost->id;
            $userSocialPost->created_at = $postCreatedTime;
            $userSocialPost->save();
        });
        return true;
    }
}
