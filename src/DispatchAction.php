<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Queue;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Nova;

class DispatchAction extends \Laravel\Nova\Actions\DispatchAction
{
    /**
     * Dispatch the given action.
     *
     * @param  \Laravel\Nova\Http\Requests\ActionRequest $request
     * @param  \Laravel\Nova\Actions\Action $action
     * @param  string $method
     * @param  \Illuminate\Support\Collection $models
     * @param  \Laravel\Nova\Fields\ActionFields $fields
     * @return mixed
     */
    public static function forModels(
        ActionRequest $request,
        Action $action,
        $method,
        Collection $models,
        ActionFields $fields
    ) {
        if ($action instanceof ShouldQueue) {
            return static::queueForModels($request, $action, $method, $models);
        }

        return Transaction::run(function ($batchId) use ($fields, $request, $action, $method, $models) {
            if (! $action->withoutActionEvents) {
                Nova::actionEvent()->createForModels($request, $action, $batchId, $models);
            }

            return $action->withBatchId($batchId)->{$method}($fields, $models);
        }, function ($batchId) {
            Nova::actionEvent()->markBatchAsFinished($batchId);
        });
    }
}
