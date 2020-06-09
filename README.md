# Laravel Nova Detached Actions Tool

A Laravel Nova tool to allow for placing actions in the Nova toolbar, detached from the checkbox selection mechanism.

:warning: Keep in mind, since the action is detached from the row selection checkboxes in the resource table, you will not have a collection of models to iterate over. Detached actions are intended to be independent of the selction in the table.
:warning: Also, keep in mind, pivot actions are not supported and have not been tested.

![screenshot](https://i.imgur.com/S8GrNFI.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require gobrightspot/nova-detached-actions
```

The tool will be automatically registered via the `ToolServiceProvider` - Thanks @milewski

## Usage

Create a custom Nova Action file:

```bash
php artisan nova:action ExportUsers
```

Instead of extending the `ExportUsers` class with the `Laravel\Nova\Actions\Action` class, swap it with the `Brightspot\Nova\Tools\DetachedActions\DetachedAction` class.

Since we won't receive a collection of `$models`, you can remove the variable from the `handle` method, so that the signature is `public function handle(ActionFields $fields)`.

You can also customize the button label, by overriding the `label()` method. If you do not override the label, it will 'humanize' the class name, in the example `ExportUsers` would become `Export Users`.

By default the detached action will only appear on the Index Toolbar.

If you want to also show the action on the resource index view (when users select a row with a checkbox), set the `$public $showOnIndex = true;`
If you want to also show the action on the resource detail view (when user selects the action from the dropdown), set the `$public $showOnDetail = true;`

Here's a full example:

```php
<?php

namespace App\Nova\Actions;

use Brightspot\Nova\Tools\DetachedActions\DetachedAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\ActionFields;

class ExportUsers extends DetachedAction
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * Get the displayable label of the button.
     *
     * @return string
     */
    public function label()
    {
        return __('Export Users');
    }

    /**
     * Perform the action.
     *
     * @param  ActionFields  $fields
     *
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        // Do work to export records

        return DetachedAction::message('It worked!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
```

Register the action on your resource:

```php
<?php
...
    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new App\Nova\Actions\ExportUsers
        ];
    }
...
```

### Usage with the Laravel Nova Excel `DownloadExcel` action

You can easily integrate the `DetachedAction` tool with the [Laravel Nova Excel](https://github.com/Maatwebsite/Laravel-Nova-Excel) `DownloadExcel` action by simply passing some additional data along using `withMeta()`.

```php
<?php
...
    /**
      * Get the actions available for the resource.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return array
      */
     public function actions(Request $request)
     {
         return [
             (new DownloadExcel)->withHeadings()->askForWriterType()->withMeta([
                 'detachedAction' => true,
                 'label' => 'Export',
                 'showOnIndexToolbar' => true
             ])->confirmButtonText('Export'),
         ];
     }
 ...
 ```

### Customizing Buttons

The package ships with some common sense default HTML classes that are applied to the action buttons. In the component, we automatically assign the following:

```
btn btn-default ml-3 btn-detached-action btn-detached-index-action
```

The action buttons are buttons so it makes sense to assign the `btn` and `btn-default` classes, we also want consistent spacing between buttons so we apply `ml-3` and in order to allow theme developers to set a specific class name to hook into, we apply `btn-detached-action` on both the Index and Detail views.

On top of these classes, the `DetachedAction` class provides `btn-primary` as a default, that will give the buttons the default button color, i.e. blue in the default Nova theme.

Furthermore, a developer can add classes on the fly, using the `extraClassesWithDefault()` and `extraClasses()` methods on the `DetachedAction` class.

### The `extraClassesWithDefault()` method

 If you want to keep all of the other classes but change the background color, then you probably want to use this method. It will maintain the consistent spacing between button, as well as the white text and shadow on the button. You can pass an array of single class names or multiple class names separated by spaces.

```php
    return [
        (new ImportUsers)->extraClassesWithDefault('bg-info')
    ];
```

### The `extraClasses()` method

If you want to completely restyle the buttons then use this method. You will need to apply margins, background etc. You may want to use this method if you want to have "link style" buttons.

```php
   return [
       (new ImportUsers)->extraClasses('no-shadow btn-link px-2')
   ];
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


