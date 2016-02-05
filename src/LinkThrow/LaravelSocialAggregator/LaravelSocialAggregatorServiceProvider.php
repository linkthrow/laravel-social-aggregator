<?php namespace LinkThrow\LaravelSocialAggregator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use App;

class LaravelSocialAggregatorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	public function boot()
	{
		// $this->package('linkthrow/laravel-social-aggregator');

		App::register('SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider');

		AliasLoader::getInstance()->alias('SocialAggregator', 'LinkThrow\LaravelSocialAggregator\SocialAggregator');
		AliasLoader::getInstance()->alias('Facebook', 'SammyK\LaravelFacebookSdk\FacebookFacade');

        $this->publishes([
            realpath(__DIR__.'/../../migrations') => $this->app->databasePath().'/migrations',
        ]);

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
