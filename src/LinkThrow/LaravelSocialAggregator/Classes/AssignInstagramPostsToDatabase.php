<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use DB;

class AssignInstagramPostsToDatabase
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

            if(!$this->checkPostInDB($this->post->id, $this->userId)) {
                $postCreatedTime = Carbon::createFromTimestamp($this->post->created_time);

                //Save the post
                $socialPost = new SocialPost;
                $socialPost->type = 'instagram';
                $socialPost->post = json_encode($this->post);
                $socialPost->created_at = $postCreatedTime;
                $socialPost->save();

                //Assign social post to the user
                $userSocialPost = new UserSocialPost;
                $userSocialPost->user_id = $this->userId;
                $userSocialPost->social_post_id = $socialPost->id;
                $userSocialPost->created_at = $postCreatedTime;
                $userSocialPost->save();
            }
        });
        return true;
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
