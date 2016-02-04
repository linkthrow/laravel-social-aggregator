<?php namespace LinkThrow\LaravelSocialAggregator\Models;

class UserSocialToken extends Eloquent {

    protected $table = 'user_social_token';

    protected $fillable = ['type', 'short_lived_token', 'long_lived_token', 'expires_at'];

    public function scopeType($query, $type)
    {
        return $query->whereType($type);
    }

    public function getDates()
    {
        return array('expires_at', 'created_at', 'updated_at');
    }
}
