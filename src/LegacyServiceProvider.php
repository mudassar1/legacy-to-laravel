<?php

namespace mudassar1\Legacy;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LegacyServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->app->singleton('ci', function ($app) {
            return get_instance();
        });


        View::composer(
            ['*'],
            function ($view) {
                $view->with('ci', get_instance());
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }
}
