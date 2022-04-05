<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Actions\DispatchAction as NovaDispatchAction;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class DispatchAction extends NovaDispatchAction
{
    /**
     * Dispatch the given action.
     *
     * @param  string  $method
     * @param  Collection  $models
     * @return mixed|void
     *
     * @throws Throwable
     */
    public function forModels($method, Collection $models)
    {
        if ($this->action instanceof ShouldQueue) {
            $this->addQueuedActionJob($method, $models);

            return;
        }

        return $this->dispatchSynchronouslyForCollection($method, $models);
    }
}