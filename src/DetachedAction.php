<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Nova;
use Illuminate\Support\Arr;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionMethod;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Exceptions\MissingActionHandlerException;

abstract class DetachedAction extends Action
{
    /**
     * Indicates if this action is only available on the custom index toolbar.
     *
     * @var bool
     */
    public $showOnIndexToolbar = true;

    /**
     * Indicates if this action is only available on the custom detail toolbar.
     *
     * @var bool
     */
    public $showOnDetailToolbar = true;

    /**
     * Indicates if this action is only available on the resource index view.
     *
     * @var bool
     */
    public $onlyOnIndex = false;

    /**
     * Indicates if this action is only available on the resource detail view.
     *
     * @var bool
     */
    public $onlyOnDetail = false;

    /**
     * Indicates if this action is available on the resource index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Indicates if this action is available on the resource detail view.
     *
     * @var bool
     */
    public $showOnDetail = false;

    /**
     * Indicates if this action is available on the resource's table row.
     *
     * @var bool
     */
    public $showOnTableRow = false;

    /**
     * Indicates if the action can be run without any models.
     *
     * @var bool
     */
    public $standalone = true;

    /**
     * The displayable label of the button.
     *
     * @var string
     */
    public $label;

    /**
     * Extra CSS classes to apply to detached action button.
     *
     * @var array
     */
    public $extraClasses = [];

    /**
     * The icon type.
     *
     * @var string
     */
    public $icon = '';

    /**
     * CSS classes to customize the display of an icon in a button.
     *
     * @var string
     */
    public $iconClasses = '';

    /**
     * The default CSS classes to apply to detached action button.
     *
     * @var array
     */
    public $defaultClasses = ['btn-primary'];

    /**
     * Get the displayable label of the button.
     *
     * @return string
     */
    public function label()
    {
        return $this->label ?: Nova::humanize($this);
    }

    /**
     * Execute the action for the given request.
     *
     * @param  \Laravel\Nova\Http\Requests\ActionRequest  $request
     * @return mixed
     * @throws MissingActionHandlerException
     */
    public function handleRequest(ActionRequest $request)
    {
        $method = ActionMethod::determine($this, $request->targetModel());

        if (! method_exists($this, $method)) {
            throw MissingActionHandlerException::make($this, $method);
        }

        $wasExecuted = false;

        $fields = $request->resolveFields();

        if (! $this->standalone) {
            $results = $request->chunks(
                static::$chunkCount, function ($models) use ($fields, $request, $method, &$wasExecuted) {
                    $models = $models->filterForExecution($request);

                    if (count($models) > 0) {
                        $wasExecuted = true;

                        return DispatchAction::forModels(
                            $request, $this, $method, $models, $fields
                        );
                    }
                }
            );
        }
        else {
            $wasExecuted = true;
            $results = DispatchAction::forModels(
                $request, $this, $method, collect([]), $fields
            );
        }

        if (! $wasExecuted) {
            return static::danger(__('Sorry! You are not authorized to perform this action.'));
        }

        return $this->handleResult($fields, $results);
    }

    /**
     * Determine if the action is to be shown on the custom index toolbar.
     *
     * @return self
     */
    public function showOnIndexToolbar()
    {
        $this->showOnIndexToolbar = true;

        return $this;
    }

    /**
     * Determine if the action is to be shown only on the custom index toolbar.
     *
     * @return self
     */
    public function onlyOnIndexToolbar()
    {
        $this->showOnIndexToolbar = true;
        $this->showOnDetailToolbar = false;
        $this->onlyOnIndex = false;
        $this->onlyOnDetail = false;
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnTableRow = false;

        return $this;
    }

    /**
     * Determine if the action is not to be shown on the index view.
     *
     * @return $this
     */
    public function exceptOnIndexToolbar()
    {
        $this->showOnIndexToolbar = false;

        return $this;
    }

    /**
     * Determine if the action is to be shown on the custom index toolbar.
     *
     * @param  bool  $value
     *
     * @return self
     */
    public function onlyOnIndex($value = true)
    {
        parent::onlyOnIndex($value);

        $this->showOnIndexToolbar = $value;
        $this->showOnDetailToolbar = ! $value;

        return $this;
    }

    /**
     * Determine if the action is to be shown on the custom detail toolbar.
     *
     * @return self
     */
    public function showOnDetailToolbar()
    {
        $this->showOnDetailToolbar = true;

        return $this;
    }

    /**
     * Determine if the action is to be shown only on the custom detail toolbar.
     *
     * @return self
     */
    public function onlyOnDetailToolbar()
    {
        $this->showOnDetailToolbar = true;
        $this->showOnIndexToolbar = false;
        $this->onlyOnIndex = false;
        $this->onlyOnDetail = false;
        $this->showOnIndex = false;
        $this->showOnDetail = false;
        $this->showOnTableRow = false;

        return $this;
    }

    /**
     * Determine if the action is to be shown only on the detail view.
     *
     * @param  bool  $value
     *
     * @return self
     */
    public function onlyOnDetail($value = true)
    {
        parent::onlyOnDetail($value);

        $this->showOnIndexToolbar = false;
        $this->showOnDetailToolbar = true;

        return $this;
    }

    /**
     * Determine if the action is not to be shown on the detail view.
     *
     * @return $this
     */
    public function exceptOnDetailToolbar()
    {
        $this->showOnDetailToolbar = false;

        return $this;
    }

    /**
     * Determine if the action is to be shown on the custom index toolbar.
     *
     * @return bool
     */
    public function shownOnIndexToolbar()
    {
        return $this->showOnIndexToolbar;
    }

    /**
     * Determine if the action is to be shown on the custom detail toolbar.
     *
     * @return bool
     */
    public function shownOnDetailToolbar()
    {
        return $this->showOnDetailToolbar;
    }

    /**
     * The default detached action classes.
     *
     * @return mixed
     */
    public function defaultClasses()
    {
        return $this->defaultClasses;
    }

    /**
     * Set the extra CSS classes to be applied to the detached action button.
     *
     * @param mixed $classes
     * @return $this
     */
    public function extraClasses($classes)
    {
        $this->extraClasses = $this->prepareClasses(Arr::wrap($classes));

        return $this;
    }

    /**
     * Set the extra CSS classes to be applied to the detached action button.
     *
     * @param mixed $classes
     * @return $this
     */
    public function extraClassesWithDefault($classes)
    {
        $this->extraClasses = $this->prepareClasses(array_merge(
            Arr::wrap($this->defaultClasses),
            Arr::wrap($classes)
        ));

        return $this;
    }

    /**
     * Get the display classes for the detached action button.
     *
     * @return array
     */
    public function getClasses()
    {
        if (empty($this->extraClasses)) {
            return $this->prepareClasses($this->defaultClasses);
        }

        return $this->prepareClasses($this->extraClasses);
    }

    public function icon($type)
    {
        $this->icon = $type;

        return $this;
    }

    public function iconClasses($classes)
    {
        $this->iconClasses = $this->prepareClasses($classes);

        return $this;
    }

    /**
     * Prepare the classes so that a string or an array of strings is formatted correctly.
     *
     * @param string|array $classes
     *
     * @return array
     */
    protected function prepareClasses($classes)
    {
        return array_filter(array_map('trim', Arr::wrap($classes)));
    }

    /**
     * Prepare the action for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'detachedAction' => true,
            'label' => $this->label(),
            'showOnIndexToolbar' => $this->shownOnIndexToolbar(),
            'showOnDetailToolbar' => $this->shownOnDetailToolbar(),
            'classes' => $this->getClasses(),
            'icon' => $this->icon,
            'iconClasses' => $this->iconClasses,
        ], parent::jsonSerialize(), $this->meta());
    }
}
