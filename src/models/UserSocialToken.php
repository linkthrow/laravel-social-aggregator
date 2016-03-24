<?php namespace LinkThrow\LaravelSocialAggregator\Models;

use LinkThrow\LaravelSocialAggregator\Models\UserSocialPost;
use LinkThrow\LaravelSocialAggregator\Models\UserSocialTokenFilter;

class UserSocialToken extends \Eloquent {

    protected $table = 'user_social_token';

    protected $hidden = array('created_at', 'updated_at');
    protected $fillable = ['type', 'short_lived_token', 'long_lived_token', 'expires_at', 'entity_id', 'user_id', 'entity_name'];

    private static $rules = [
        'long_lived_token' => 'sometimes|string',
        'short_lived_token' => 'required_if:type,facebook|string',
        'type'      =>  'required|string|in:facebook,twitter,instagram,other',
        'expires_at'    =>  'sometimes|date',
        'entity_id'    =>  'sometimes|string',
        'entity_name'    =>  'sometimes|string',
        'user_id'    =>  'sometimes|integer'
    ];

    protected $casts = [
        'expires_at' => 'date',
        'user_id' => 'integer'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($userSocialToken) {
            UserSocialPost::join('social_post', 'social_post.id', '=', 'user_social_post.social_post_id')
                                ->where('user_social_post.user_id', '=', $userSocialToken->user_id)
                                ->where('social_post.type', '=', $userSocialToken->type)
                                ->delete();

            UserSocialTokenFilter::where('user_social_token_filter.user_social_token_id', '=', $userSocialToken->id)
                                    ->delete();
        });
    }

    public function rules()
    {
        return UserSocialToken::$rules;
    }

    public static function staticRules()
    {
        return UserSocialToken::$rules;
    }

    public function scopeType($query, $type)
    {
        return $query->whereType($type);
    }

    public function getDates()
    {
        return array('expires_at', 'created_at', 'updated_at');
    }
}
