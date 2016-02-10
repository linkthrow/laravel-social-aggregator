<?php namespace LinkThrow\LaravelSocialAggregator\Models;

class UserSocialTokenFilter extends \Eloquent {

    protected $table = 'user_social_token_filter';

    protected $hidden = array('created_at', 'updated_at');
    protected $fillable = ['user_social_token_id', 'filter'];

    private static $rules = [
        'user_social_token_id' => 'required|integer|exists:user_social_token,id',
        'filter' => 'required|string'
    ];

    protected $casts = [
        'user_social_token_id' => 'integer'
    ];

    public function rules()
    {
        return UserSocialTokenFilter::$rules;
    }

    public static function staticRules()
    {
        return UserSocialTokenFilter::$rules;
    }
}
