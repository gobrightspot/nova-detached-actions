<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('detached-actions', __DIR__. '/../dist/js/tool.js');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
