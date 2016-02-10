<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialTokenFilter;
use DB;

class CheckIfSocialMediaTokenHasFilters
{
    protected $userSocialMediaToken;

    public function __construct($userSocialMediaToken)
    {
        $this->userSocialMediaToken = $userSocialMediaToken;
    }

    public function get() {
        $filters = UserSocialTokenFilter::where('user_social_token_id', '=', $this->userSocialMediaToken->id)->first();
        return $filters;
    }
}
