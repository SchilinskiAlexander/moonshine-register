# MoonShine Register

[Deutsch](README.de.md) | [Русский](README.ru.md)

Registration and password reset pages for [MoonShine v4](https://moonshine-laravel.com/) authentication.

This package adds a public registration flow to the MoonShine admin login area. It can also replace the default MoonShine login page with a version that links to registration and password reset pages.

## Features

- Admin registration page inside the MoonShine route group
- Optional password reset flow for MoonShine users
- Optional automatic login after registration
- Configurable route names and page classes
- Configurable default MoonShine role
- English and German translations
- Laravel package auto-discovery

## Requirements

- PHP `^8.3`
- Laravel components `^12.0` or `^13.0`
- MoonShine `^4.15`

## Installation

Install the package with Composer:

```bash
composer require schilinskialexander/moonshine-register
```

Laravel discovers the service provider automatically.

Publish the configuration file if you want to customize the package:

```bash
php artisan vendor:publish --tag=moonshine-register-config
```

Publish translations if you want to edit the language files:

```bash
php artisan vendor:publish --tag=moonshine-register-lang
```

Run your migrations if the MoonShine tables are not created yet:

```bash
php artisan migrate
```

## What It Adds

By default, the package registers these routes under your configured MoonShine prefix.

If your MoonShine prefix is `admin`, the routes are:

| Method | URL | Route name |
| --- | --- | --- |
| `GET` | `/admin/register` | `moonshine.register` |
| `POST` | `/admin/register` | `moonshine.register.store` |
| `GET` | `/admin/forgot-password` | `moonshine.password.request` |
| `POST` | `/admin/forgot-password` | `moonshine.password.email` |
| `GET` | `/admin/reset-password/{token}` | `moonshine.password.reset` |
| `POST` | `/admin/reset-password/{token}` | `moonshine.password.update` |

The routes are only registered when MoonShine routes are enabled.

## Configuration

After publishing the config file, you can edit:

```php
// config/moonshine-register.php
return [
    'enabled' => true,
    'route' => 'register',

    'login_link' => [
        'enabled' => true,
    ],

    'password_reset' => [
        'enabled' => true,
        'route' => 'forgot-password',
        'reset_route' => 'reset-password',
        'broker' => 'moonshine',
    ],

    'auto_login' => false,
    'default_role_id' => 1,
    'role_column' => 'moonshine_user_role_id',
];
```

You can also configure the package through environment variables:

```dotenv
MOONSHINE_REGISTER_ENABLED=true
MOONSHINE_REGISTER_ROUTE=register
MOONSHINE_REGISTER_LOGIN_LINK_ENABLED=true

MOONSHINE_REGISTER_PASSWORD_RESET_ENABLED=true
MOONSHINE_REGISTER_PASSWORD_RESET_ROUTE=forgot-password
MOONSHINE_REGISTER_PASSWORD_RESET_RESET_ROUTE=reset-password
MOONSHINE_REGISTER_PASSWORD_RESET_BROKER=moonshine

MOONSHINE_REGISTER_AUTO_LOGIN=false
MOONSHINE_REGISTER_ROLE_ID=1
MOONSHINE_REGISTER_ROLE_COLUMN=moonshine_user_role_id
MOONSHINE_REGISTER_USER_MODEL="SchilinskiAlexander\\MoonShineRegister\\Models\\MoonShineUser"
```

## Usage

After installation, open your MoonShine login page. With the default MoonShine prefix, this is:

```text
/admin/login
```

The login page will show links to:

```text
/admin/register
/admin/forgot-password
```

New users are created in the MoonShine user table and receive the configured default role.

## Password Reset

The package configures a `moonshine` password broker and uses the MoonShine user provider.

The included user model extends MoonShine's default user model and implements Laravel's password reset contract. This is required so MoonShine users can receive reset notifications.

Make sure your application has the standard password reset token table:

```bash
php artisan migrate
```

## Custom Pages

You can replace the included pages by changing the config values:

```php
'page' => App\MoonShine\Pages\Auth\RegisterPage::class,

'login_link' => [
    'page' => App\MoonShine\Pages\Auth\LoginPage::class,
],

'password_reset' => [
    'request_page' => App\MoonShine\Pages\Auth\ForgotPasswordPage::class,
    'reset_page' => App\MoonShine\Pages\Auth\ResetPasswordPage::class,
],
```

Your custom pages should render forms that submit to the package route names.

## Disabling Features

Disable the whole package:

```dotenv
MOONSHINE_REGISTER_ENABLED=false
```

Disable only password reset:

```dotenv
MOONSHINE_REGISTER_PASSWORD_RESET_ENABLED=false
```

Disable only the extra links on the login page:

```dotenv
MOONSHINE_REGISTER_LOGIN_LINK_ENABLED=false
```

## Testing

Run the project tests:

```bash
php artisan test
```

Run only the package-related feature tests:

```bash
php artisan test tests/Feature/MoonShineRegisterTest.php
```

## License

The MIT License.
