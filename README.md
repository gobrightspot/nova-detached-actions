# Laravel Nova Detached Actions Tool

A Laravel Nova tool to allow for placing actions in the Nova toolbar, detached from the checkbox selection mechanism.

:warning: Keep in mind, since the action is detached from the row selection checkboxes in the resource table, you will not have a collection of models to iterate over. Detached actions are intended to be independent of the selction in the table.


![screenshot](https://i.imgur.com/S8GrNFI.png)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require gobrightspot/nova-detached-actions
```

Register the tool in `NovaServiceProvider`:

```php
use Brightspot\Nova\Tools\DetachedActions\DetachedActions;
...
    public function tools()
    {
        return [
            new DetachedActions,
            ...
        ];
    }
```

## Usage

Create a custom Nova Action file:

```bash
php artisan nova:action ExportUsers
```

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
                 'label' => 'Export'
             ])->confirmButtonText('Export'),
         ];
     }
 ...
 ```



## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


