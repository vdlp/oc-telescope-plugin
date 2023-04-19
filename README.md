# Vdlp.Telescope

Provides a seamless integration of [Laravel Telescope 4.0](https://laravel.com/docs/9.x/telescope) inside October CMS.

Laravel Telescope is an elegant debug assistant for the Laravel framework. Telescope provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps and more. Telescope makes a wonderful companion to your local Laravel development environment.

![Laravel Telescope Dashboard](https://plugins.vdlp.nl/octobercms/oc-telescope-plugin/dashboard.png)

## Requirements

- October CMS 3.2 or higher
- PHP 8.0.2 or higher

## Installation

Install the plugin using composer:

```shell
composer require vdlp/oc-telescope-plugin --dev
```

If you plan to use the Telescope plugin on other than your local development environment, you may install the plugin **without** the `--dev` flag.

### Assets

Make sure you have an active theme before publishing the required assets:

```shell
php artisan vendor:publish --tag telescope-assets --force
```

### Database

Run database migrations (when using database driver = default):

```shell
php artisan october:migrate
php artisan migrate
```

## Environment

Make sure your environment is set to `local`.

## Configuration

Create configuration file in `config/telescope.php`:

```shell
php artisan vendor:publish --tag telescope-config
```

## Permissions

- Users must have the proper permissions to access the Telescope Dashboard.
- If backend user is not logged in, access to the Telescope Dashboard will not be granted.

## Switching themes

> Each time you switch the default theme you need to re-publish the Telescope assets.

The assets will be stored in your current theme folder: `themes/mytheme/assets/telescope` folder.

## Documentation

Please go to the Laravel website for detailed documentation about Laravel Telescope.

[Telescope for Laravel 9.x](https://laravel.com/docs/9.x/telescope)

## Questions

If you have any question about how to use this plugin, please don't hesitate to contact us at [octobercms@vdlp.nl](mailto:octobercms@vdlp.nl). We're
happy to help you. You can also visit the support forum and drop your questions/issues there.
