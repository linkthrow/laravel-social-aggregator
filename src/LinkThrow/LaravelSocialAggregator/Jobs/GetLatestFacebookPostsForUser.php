<?php namespace LinkThrow\LaravelSocialAggregator\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;
use Carbon\Carbon;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Facebook;
use App;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use LinkThrow\LaravelSocialAggregator\Classes\ExtendFacebookShortLiveToken;
use LinkThrow\LaravelSocialAggregator\Classes\AssignFacebookPostsToDatabase;
use DB;

class GetLatestFacebookPostsForUser extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $userFacebookToken;

    public function __construct(UserSocialToken $userFacebookToken)
    {
        $this->userFacebookToken = $userFacebookToken;
    }

    public function handle()
    {
        $facebook = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

        //Check if long term access token is set
        if(!$this->userFacebookToken->long_lived_token) {
            //If user has no short term token then abort as cannot extend
            if(!$this->userFacebookToken->short_lived_token) {
                abort(422, 'Cannot access posts for user without short lived token!');
            }

            $tokenExtender = new ExtendFacebookShortLiveToken($this->userFacebookToken);
            $tokenExtender->extend();
        }

        $facebook->setDefaultAccessToken($this->userFacebookToken->long_lived_token);

        $firstCall = true;
        $feedQuery = $this->userFacebookToken->entity_id . '/feed?fields=call_to_action,caption,link,message_tags,full_picture,message,description,from,icon,name,place,source,story_tags,story,created_time,type,with_tags&limit=100';

        if($lastFacebookPostByUser = UserSocialPost::join('social_post', 'user_social_post.social_post_id', '=', 'social_post.id')->where('user_social_post.user_id', '=', $this->userFacebookToken->user_id)->where('social_post.type', '=', 'facebook')->orderBy('user_social_post.created_at', 'DESC')->first()) {
            $firstCall = false;
            $feedQuery = $feedQuery . '&since='.$lastFacebookPostByUser->created_at;
        }

        do {

            $latestPostsRaw = $facebook->get($feedQuery);
            $latestPostsDecoded = $latestPostsRaw->getDecodedBody();
            $latestPostsJSON = $latestPostsDecoded['data'];

            foreach ($latestPostsJSON as $post) {
                $assignPostToDb = new AssignFacebookPostsToDatabase($this->userFacebookToken->user_id, $post);
                $assignPostToDb->assign();
            }

            $latestPostsPaging = array();

            if(array_key_exists('paging', $latestPostsDecoded)) {
                $latestPostsPaging = $latestPostsDecoded['paging'];
            }

        } while(!$firstCall && array_key_exists('next', $latestPostsPaging) && $feedQuery = $this->extractMeaningfulPath($latestPostsPaging['next']));
    }

    private function extractMeaningfulPath($path) {
        return 'me/feed?'.substr($path, strpos($path, "?") + 1);
    }
}
