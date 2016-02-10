<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class AssignTweetsToDatabase
{
    protected $userId;
    protected $tweet;

    public function __construct($userId, $tweet)
    {
        $this->userId = $userId;
        $this->tweet = $tweet;
    }

    public function assign()
    {
        DB::transaction(function () {
            $postCreatedTimestamp = strtotime($this->tweet->created_at);
            $postCreatedTime = Carbon::createFromTimestamp($postCreatedTimestamp);

            //Save the post
            $socialPost = new SocialPost;
            $socialPost->type = 'twitter';
            $socialPost->post = json_encode($this->tweet);
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
