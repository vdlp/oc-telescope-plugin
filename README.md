# Vdlp.Telescope

Provides a seamless integration of [Laravel Telescope 5.0](https://laravel.com/docs/12.x/telescope) inside October CMS.

Laravel Telescope is an elegant debug assistant for the Laravel framework. Telescope provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps and more. Telescope makes a wonderful companion to your local Laravel development environment.

![Laravel Telescope Dashboard](https://plugins.vdlp.nl/octobercms/oc-telescope-plugin/dashboard.png)

## Requirements

- October CMS 4.x or higher
- PHP 8.2.0 or higher

## Installation

Install the plugin using composer:

```shell
composer require vdlp/oc-telescope-plugin --dev
```

If you plan to use the Telescope plugin on other than your local development environment, you may install the plugin **without** the `--dev` flag.

### Composer.json

Make sure that you're not autodiscovering the `laravel/telescope` package in your `composer.json` file. 

```json
"extra": {
    "laravel": {
        "dont-discover": [
            "laravel/telescope"
        ]
    }
}
```
    
Run the `install` command to install the Telescope assets:
- This will publish the Telescope configuration `config/telescope.php` file 
- This will publish the Telescope migrations `database/migrations/` files

```shell
php artisan telescope:install
```

### Database
Update the migrations:

```shell
php artisan october:migrate
php artisan migrate
```

## Environment

Make sure your environment is set to `local`.

## Permissions

- Users must have the proper permissions to access the Telescope Dashboard.
- If backend user is not logged in, access to the Telescope Dashboard will not be granted.

## Documentation

Please go to the Laravel website for detailed documentation about Laravel Telescope.

[Telescope for Laravel 12.x](https://laravel.com/docs/12.x/telescope)

## Questions

If you have any question about how to use this plugin, please don't hesitate to contact us at [octobercms@vdlp.nl](mailto:octobercms@vdlp.nl). We're
happy to help you. You can also visit the support forum and drop your questions/issues there.
