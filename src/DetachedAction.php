<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionMethod;
use Laravel\Nova\Exceptions\MissingActionHandlerException;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Nova;

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
     * The displayable label of the button.
     *
     * @var string
     */
    public $label;

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

        $fields = $request->resolveFields();

        $results = DispatchAction::forModels(
            $request,
            $this,
            $method,
            collect([]),
            $fields
        );

        return $this->handleResult($fields, [$results]);
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
     * @param bool $value
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
     * Determine if the action is to be shown on the custom detil toolbar.
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
     * @param bool $value
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
        ], parent::jsonSerialize(), $this->meta());
    }
}
