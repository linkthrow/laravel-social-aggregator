<?php namespace LinkThrow\LaravelSocialAggregator\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;
use Carbon\Carbon;
use App;
use LinkThrow\LaravelSocialAggregator\Models\SocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use LinkThrow\LaravelSocialAggregator\Classes\AssignInstagramPostsToDatabase;
use DB;
use Thujohn\Twitter\Facades\Twitter;
use LinkThrow\LaravelSocialAggregator\Classes\GetInstagramMaxIdForPagination;
use LinkThrow\LaravelSocialAggregator\Classes\CheckIfInstagramPostsContainAlreadyExtractedPost;
use LinkThrow\LaravelSocialAggregator\Classes\CheckIfSocialMediaTokenHasFilters;
use LinkThrow\LaravelSocialAggregator\Classes\CheckIfInstagramPostHasFilter;
use Instagram;

class GetLatestInstagramPostsForUser extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $userInstagramToken;

    public function __construct(UserSocialToken $userInstagramToken)
    {
        $this->userInstagramToken = $userInstagramToken;
    }

    public function handle()
    {
        Instagram::setAccessToken($this->userInstagramToken->long_lived_token);
        $morePosts = true;
        $firstCall = true;

        do {

            if(!$firstCall) {
                $posts = Instagram::pagination($posts, 20);
            } else {
                $posts = Instagram::getUserMedia('self', 20);
                $firstCall = false;
            }

            $checkIfSocialMediaTokenHasFilters = new CheckIfSocialMediaTokenHasFilters($this->userInstagramToken);
            $filter = $checkIfSocialMediaTokenHasFilters->get();
            $morePosts = (isset($posts->pagination->next_max_id)) ? true : false;

            //Check if posts contain any which have already been indexed therefore stop pagination
            $checkIfPostsContainAlreadyExtractedPost = new CheckIfInstagramPostsContainAlreadyExtractedPost($posts->data, $this->userInstagramToken->user_id);
            if($checkIfPostsContainAlreadyExtractedPost->check()) {
                $morePosts = false;
            }

            foreach ($posts->data as $post) {

                $assignToDB = false;
                if($filter) {
                    $checkIfPostHasFilter = new CheckIfInstagramPostHasFilter($post->tags, $filter->filter);
                    if($checkIfPostHasFilter->check()) {
                        $assignToDB = true;
                    }
                } else {
                    $assignToDB = true;
                }
                if($assignToDB) {
                    $assignInstagramPostsToDb = new AssignInstagramPostsToDatabase($this->userInstagramToken->user_id, $post);
                    $assignInstagramPostsToDb->assign();
                }
            }

        } while($morePosts);
    }
}
