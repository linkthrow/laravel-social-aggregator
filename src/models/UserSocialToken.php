<?php namespace LinkThrow\LaravelSocialAggregator\Models;

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
