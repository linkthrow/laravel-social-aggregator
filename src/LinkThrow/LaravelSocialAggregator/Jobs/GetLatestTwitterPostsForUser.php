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
use LinkThrow\LaravelSocialAggregator\Classes\AssignTweetsToDatabase;
use DB;
use Thujohn\Twitter\Facades\Twitter;
use LinkThrow\LaravelSocialAggregator\Classes\GetTwitterMaxIdForPagination;
use LinkThrow\LaravelSocialAggregator\Classes\GetTwitterSinceIdForPagination;
use LinkThrow\LaravelSocialAggregator\Classes\CheckIfSocialMediaTokenHasFilters;
use LinkThrow\LaravelSocialAggregator\Classes\CheckIfTweetHasFilter;

class GetLatestTwitterPostsForUser extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $userTwitterToken;

    public function __construct(UserSocialToken $userTwitterToken)
    {
        $this->userTwitterToken = $userTwitterToken;
    }

    public function handle()
    {
        $request_token = [
            'token'  => $this->userTwitterToken->short_lived_token,
            'secret' => $this->userTwitterToken->long_lived_token,
        ];
        // $twitter = App::make('Thujohn\Twitter\Twitter');
        $twitter = Twitter::reconfig($request_token);

        $twitterSincePaginatorData = new GetTwitterSinceIdForPagination($this->userTwitterToken->user_id);
        $twitterSinceId = $twitterSincePaginatorData->retrieve();

        $firstCall = ($twitterSinceId === 0) ? true : false;

        do {

            $twitterSincePaginatorData = new GetTwitterSinceIdForPagination($this->userTwitterToken->user_id);
            $twitterSinceId = $twitterSincePaginatorData->retrieve();

            $twitterCallParams = array(
                'id'        =>      $this->userTwitterToken->entity_id,
                'count'     =>      100,
                'format'    =>      'json'
            );

            if($twitterSinceId > 0) {
                $twitterCallParams['since_id']  =  $twitterSinceId;
            }

            $checkIfSocialMediaTokenHasFilters = new CheckIfSocialMediaTokenHasFilters($this->userTwitterToken);
            $filter = $checkIfSocialMediaTokenHasFilters->get();
            $tweets = json_decode(Twitter::getUserTimeline($twitterCallParams));

            foreach ($tweets as $tweet) {
                $assignToDB = false;
                if($filter) {
                    $checkIfTweetHasFilter = new CheckIfTweetHasFilter($tweet->entities->hashtags, $filter->filter);
                    if($checkIfTweetHasFilter->check()) {
                        $assignToDB = true;
                    }
                } else {
                    $assignToDB = true;
                }
                if($assignToDB) {
                    $assignTweetsToDb = new AssignTweetsToDatabase($this->userTwitterToken->user_id, $tweet);
                    $assignTweetsToDb->assign();
                }
            }

            $twitterPaginatorData = new GetTwitterMaxIdForPagination($tweets);
            $twitterMaxId = $twitterPaginatorData->extract();

        } while(!$firstCall && count($tweets) > 0);
    }
}
