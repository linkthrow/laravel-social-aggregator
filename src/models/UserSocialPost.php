<?php namespace LinkThrow\LaravelSocialAggregator\Models;

class UserSocialPost extends \Eloquent {

	protected $table = 'user_social_post';

	protected $fillable = ['user_id', 'social_post_id'];

    public function getDates()
    {
        return array('created_at', 'updated_at');
    }
}
