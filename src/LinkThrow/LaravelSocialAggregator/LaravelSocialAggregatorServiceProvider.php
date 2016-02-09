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
        App::register('Laravel\Socialite\SocialiteServiceProvider');
        App::register('Thujohn\Twitter\TwitterServiceProvider');
        App::register('Vinkla\Instagram\InstagramServiceProvider');

		AliasLoader::getInstance()->alias('SocialAggregator', 'LinkThrow\LaravelSocialAggregator\SocialAggregator');
		AliasLoader::getInstance()->alias('Facebook', 'SammyK\LaravelFacebookSdk\FacebookFacade');
        AliasLoader::getInstance()->alias('Twitter', 'Thujohn\Twitter\Facades\Twitter');
        AliasLoader::getInstance()->alias('Socialite', 'Laravel\Socialite\Facades\Socialite');
        AliasLoader::getInstance()->alias('Instagram', 'Vinkla\Instagram\Facades\Instagram');

        $this->publishes([
            realpath(__DIR__.'/../../migrations') => $this->app->databasePath().'/migrations',
        ]);

        $this->loadViewsFrom(__DIR__.'/../../views', 'socialAggregator');

        if(!$this->app->routesAreCached()) {
            require __DIR__.'/../../routes.php';
        }

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
