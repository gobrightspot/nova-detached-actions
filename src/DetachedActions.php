<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class DetachedActions extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('detached-actions', __DIR__.'/../dist/js/tool.js');
        Nova::style('detached-actions', __DIR__.'/../dist/css/tool.css');
    }
}
