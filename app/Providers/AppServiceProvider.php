<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Validator::extend('businessFieldsRequired', function ($attribute, $value, $parameters, $validator) {
            // Check if business_name is required and not empty
            if ($validator->getData()[$parameters[0]]) {
                // Check if all other fields are required and not empty
                foreach ($parameters as $field) {
                    if (empty($validator->getData()[$field])) {
                        return false;
                    }
                }
            }
            return true;
        });
    }
}
