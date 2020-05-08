<?php

namespace Brightspot\Nova\Tools\DetachedActions;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Nova;

abstract class DetachedAction extends Action
{
    /**
     * Indicates if this action is only available on the resource index view.
     *
     * @var bool
     */
    public $onlyOnIndex = true;

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
     * Prepare the action for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'detachedAction' => true,
            'label' => $this->label(),
        ], parent::jsonSerialize(), $this->meta());
    }
}
