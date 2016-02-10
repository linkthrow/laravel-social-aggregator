<?php namespace LinkThrow\LaravelSocialAggregator\Classes;

use App;
use Config;
use Carbon\Carbon;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;

class ExtendFacebookShortLiveToken
{
    protected $userFacebookToken;

    public function __construct(UserSocialToken $userFacebookToken)
    {
        $this->userFacebookToken = $userFacebookToken;
    }

    public function extend()
    {
        $facebook = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');

        //Extend short lived token to long
        $facebook->setDefaultAccessToken($this->userFacebookToken->short_lived_token);

        $clientId = Config::get('laravel-facebook-sdk.facebook_config.app_id');
        $clientSecret = Config::get('laravel-facebook-sdk.facebook_config.app_secret');

        $longLivedToken = $facebook->get('oauth/access_token?grant_type=fb_exchange_token&fb_exchange_token='.$this->userFacebookToken->short_lived_token.'&client_id='.$clientId.'&client_secret='.$clientSecret);
        if($longLivedToken = json_decode($longLivedToken->getBody())) {
            $this->userFacebookToken->long_lived_token = $longLivedToken->access_token;
            $this->userFacebookToken->expires_at = Carbon::now()->addSeconds($longLivedToken->expires_in);
            $this->userFacebookToken->save();
        }

        return $longLivedToken->access_token;
    }
}
