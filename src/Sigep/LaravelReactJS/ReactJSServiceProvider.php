<?php

namespace Sigep\LaravelReactJS;

use Illuminate\Support\ServiceProvider;

class ReactJSServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->package('sigep/reactjs');
    }

    public function register()
    {
        $this->app->bind('reactjs', function ($app) {
            return new ReactJS($app);
        });
    }
}
