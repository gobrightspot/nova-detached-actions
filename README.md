# Laravel Nova Detached Actions Tool

![Status: ABANDONED](https://img.shields.io/badge/Status-ABANDONED-red.svg?style=flat)
[![No Maintenance Intended](http://unmaintained.tech/badge.svg)](http://unmaintained.tech/) 

## Deprecation Notice :warning:

> GoBrightspot is no longer maintaining this project. Please fork it to continue development.

## Intro

A Laravel Nova tool to allow for placing actions in the Nova toolbar, detached from the checkbox selection mechanism.

:warning: Keep in mind, since the action is detached from the row selection checkboxes in the resource table, you will not have a collection of models to iterate over. Detached actions are intended to be independent of the selection in the table.

:warning: Also, keep in mind, pivot actions are not supported and have not been tested.

![screenshot](https://i.imgur.com/S8GrNFI.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require gobrightspot/nova-detached-actions
```

The tool will be automatically registered via the `ToolServiceProvider`

## Usage

Create a custom Nova Action file:

```bash
php artisan nova:action ExportUsers
```

Instead of extending the `ExportUsers` class with the `Laravel\Nova\Actions\Action` class, swap it with the `Brightspot\Nova\Tools\DetachedActions\DetachedAction` class.

Since we won't receive a collection of `$models`, you can remove the variable from the `handle` method, so that the signature is `public function handle(ActionFields $fields)`.

You can also customize the button label, by overriding the `label()` method. If you do not override the label, it will 'humanize' the class name, in the example `ExportUsers` would become `Export Users`.

By default, the detached action will only appear on the Index Toolbar.

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
```

## Chunking and repetitive calls to the handle()
If you initiate an action on the background Nova will chunk up the total amount of records and call the `handle()` function of your DetachedAction for each chunk. This could have unexpected performance impact as the system will perform your action for each chunk of records.
This happens in the `handleRequest()` function of `\Laravel\Nova\Actions\Action.php`.

To prevent this the simplest way is to overrule this function in your `DetachedAction`. this is a bare example dispatching just a job without any checks or other logic:

```php
/** @return array<int,string> */
public function handleRequest(ActionRequest $request): array
{
    dispatch(new GenerateTicketReport($request->resolveFields()));
    return DetachedAction::message('Nice job!');
}
```

### Display on different screens

##### `showOnIndexToolbar()`
Show the detached action buttons on the index page toolbar (i.e. the default location). Do not show on the index grid action dropdown, 

##### `onlyOnIndexToolbar()`
Only show the detached action buttons on the index toolbar. Do not show them anywhere else.

##### `exceptOnIndexToolbar`
Show the detached action buttons everywhere except the index toolbar.

##### `onlyOnIndex`
Show the detached action buttons only on the index view. Allows them to be displayed on the `standalone` dropdown or in the grid action dropdown.

##### `showOnDetailToolbar()`
Show the detached action buttons on the detail page toolbar (i.e. the default location).

##### `onlyOnDetailToolbar()`
Only show the detached action buttons on the index toolbar. Do not show them anywhere else.

##### `exceptOnDetailToolbar`
Show the detached action buttons everywhere except the detail toolbar.

##### `onlyOnDetail`
Show the detached action buttons only on the detail view. Allows them to be displayed on the `standalone` dropdown or in the action dropdown.

### Usage with the Laravel Nova Excel `DownloadExcel` action

You can easily integrate the `DetachedAction` tool with the [Laravel Nova Excel](https://github.com/Maatwebsite/Laravel-Nova-Excel) `DownloadExcel` action by simply passing some additional data along using `withMeta()`.

```php
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
             'name' => 'Export',
             'showOnIndexToolbar' => true
         ])->confirmButtonText('Export'),
     ];
 }
 ```

### Customizing Buttons

### Visible vs Invisible Buttons

By default, the component will show the first 3 buttons and put the rest into a dropdown menu. If you want to change the number of buttons visible per resource, you can do so by using the `additionalInformation` method, like so:

```php
public static function additionalInformation(Request $request)
{
    return [
        'visibleActionsLimit' => 4
    ];
}
```

You can also change the icon type and whether or not you want to display a dropdown arrow for the invisible action menu:

```php
public static function additionalInformation(Request $request)
{
    return [
        'visibleActionsLimit' => 2,
        'showInvisibleActionsArrow' => true,
        'invisibleActionsIcon' => 'menu'
    ];
}
```


### Customizing Button Classes

The package ships with some common sense default HTML classes that are applied to the action buttons. In the component, we automatically assign the following:

```
btn btn-default ml-3 detached-action-button flex justify-center items-center
```

The action buttons are buttons so it makes sense to assign the `btn` and `btn-default` classes, we also want consistent spacing between buttons, so we apply `ml-3` and to line up the icons and text inside the button, we use `flex justify-center items-center`. Further, in order to allow theme developers to set a specific class name to hook into, we apply `detached-action-button` on both the Index and Detail views.

On top of these classes, the `DetachedAction` class provides `btn-primary` as a default, that will give the buttons the default button color, i.e. blue in the default Nova theme.

A developer can add classes on the fly, using the `extraClassesWithDefault()` and `extraClasses()` methods on the `DetachedAction` class.

### The `extraClassesWithDefault()` method

 If you want to keep the default classes but add extra classes alongside them, then you probably want to use this method. You can pass an array of single class names or multiple class names separated by spaces.

```php
return [
    (new ImportUsers)->extraClassesWithDefault('bg-info')
];
```

### The `extraClasses()` method

You are free to use any tailwind/nova class.

```php
return [
   (new ImportUsers)->extraClasses('bg-logo text-white hover:black')
];
```

### Adding an icon

You can use any of the 104 Heroicon icons by specifying the icon name in lowercase:

```php
return [
   (new ImportUsers)->icon('add')
];
```

You can also customize the display of that icon using `iconClasses`:

```php
return [
   (new ImportUsers)->icon('upload')->iconClasses('mr-3 -ml-2')
];
```


![screenshot](https://i.imgur.com/9PaOxZC.png)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


