<?php
use LinkThrow\LaravelSocialAggregator\Models\UserSocialToken;

Route::get('twitter/login/{userId}', ['as' => 'twitter.login', function($userId){
    Request::session()->put('twitter_user_id', $userId);
    return Socialite::driver('twitter')->redirect();
}]);

Route::get('twitter/callback', ['as' => 'twitter.callback', function () {
    try {
        if (Request::session()->has('twitter_user_id')) {
            $user = Socialite::driver('twitter')->user();
            $userSocialToken = new UserSocialToken;
            $userSocialToken->type = 'twitter';
            $userSocialToken->expires_at = null;
            $userSocialToken->short_lived_token = $user->token;
            $userSocialToken->long_lived_token = $user->tokenSecret;
            $userSocialToken->entity_id = $user->id;
            $userSocialToken->entity_name = $user->nickname;
            $userSocialToken->user_id = Request::session()->get('twitter_user_id');
            $userSocialToken->save();
            Request::session()->forget('twitter_user_id');
            return view('oauth::closepopup')->with('id', $userSocialToken->id)->with('username', $user->nickname);
        } else {
            abort(422, 'Cannot get user id!');
        }
    } catch (Exception $e) {
        abort(422, 'Error logging into twitter!');
    }
}]);

Route::get('instagram/login/{userId}', ['as' => 'instagram.login', function($userId){
    Request::session()->put('instagram_user_id', $userId);
    return Socialite::driver('instagram')->redirect();
}]);

Route::get('instagram/callback', ['as' => 'instagram.callback', function () {
    try {
        if (Request::session()->has('instagram_user_id')) {
            $user = Socialite::driver('instagram')->user();
            $userSocialToken = new UserSocialToken;
            $userSocialToken->type = 'instagram';
            $userSocialToken->expires_at = null;
            $userSocialToken->short_lived_token = $user->token;
            $userSocialToken->long_lived_token = $user->token;
            $userSocialToken->entity_id = $user->id;
            $userSocialToken->entity_name = $user->nickname;
            $userSocialToken->user_id = Request::session()->get('instagram_user_id');
            $userSocialToken->save();
            Request::session()->forget('instagram_user_id');
            return view('oauth::closepopup')->with('id', $userSocialToken->id)->with('username', $user->nickname);
        } else {
            abort(422, 'Cannot get user id!');
        }
    } catch (Exception $e) {dd($e);
        abort(422, 'Error logging into instagram!');
    }
}]);
