<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('detached-actions', __DIR__. '/../dist/js/tool.js');
        Nova::style('detached-actions', __DIR__. '/../dist/css/tool.css');
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
