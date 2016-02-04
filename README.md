# Social Aggregator for Laravel

Social Aggregator is interface to social APIs. With this package you can get latest posts/tweets/photos from Facebook, Twitter, Instagram and save it to database to show it in your application.

# Installation

Open `composer.json` file of your project and add the following to the require array:
```json
"linkthrow/laravel-social-aggregator": "dev-master"
```

Now run `composer update` to install the new requirement.

Once it's installed, you need to register the service provider in `app/config/app.php` in the providers array:
```php
'providers' => array(
  ...
  'LinkThrow\LaravelSocialAggregator\LaravelSocialAggregatorServiceProvider',
);
```

Publish the config file:
`php artisan config:publish linkthrow/laravel-social-aggregator`

Then execute migration with the following command

`php artisan migrate --package="linkthrow/laravel-social-aggregator"`


This will create new table `social_posts`. In this table package store the posts from feeds.
