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
            $ci =& get_instance();
            return $ci;
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
