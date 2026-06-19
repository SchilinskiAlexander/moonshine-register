# MoonShine Register

[English](README.md) | [Deutsch](README.de.md)

Страницы регистрации и сброса пароля для аутентификации [MoonShine v4](https://moonshine-laravel.com/).

Этот пакет добавляет публичную регистрацию в область входа MoonShine Admin. Также он может заменить стандартную страницу входа MoonShine на страницу со ссылками на регистрацию и сброс пароля.

## Возможности

- Страница регистрации администратора внутри группы маршрутов MoonShine
- Опциональный сброс пароля для пользователей MoonShine
- Опциональный автоматический вход после регистрации
- Настраиваемые маршруты и классы страниц
- Настраиваемая роль по умолчанию для новых пользователей MoonShine
- Английские и немецкие переводы
- Автоматическое обнаружение пакета Laravel

## Требования

- PHP `^8.3`
- Компоненты Laravel `^12.0` или `^13.0`
- MoonShine `^4.15`

## Установка

Установите пакет через Composer:

```bash
composer require schilinskialexander/moonshine-register
```

Laravel автоматически обнаружит Service Provider.

Опубликуйте конфигурационный файл, если хотите изменить настройки пакета:

```bash
php artisan vendor:publish --tag=moonshine-register-config
```

Опубликуйте переводы, если хотите изменить языковые файлы:

```bash
php artisan vendor:publish --tag=moonshine-register-lang
```

Выполните миграции, если таблицы MoonShine еще не созданы:

```bash
php artisan migrate
```

## Что добавляет пакет

По умолчанию пакет регистрирует эти маршруты внутри настроенного префикса MoonShine.

Если префикс MoonShine равен `admin`, будут доступны такие маршруты:

| Метод | URL | Имя маршрута |
| --- | --- | --- |
| `GET` | `/admin/register` | `moonshine.register` |
| `POST` | `/admin/register` | `moonshine.register.store` |
| `GET` | `/admin/forgot-password` | `moonshine.password.request` |
| `POST` | `/admin/forgot-password` | `moonshine.password.email` |
| `GET` | `/admin/reset-password/{token}` | `moonshine.password.reset` |
| `POST` | `/admin/reset-password/{token}` | `moonshine.password.update` |

Маршруты регистрируются только тогда, когда маршруты MoonShine включены.

## Конфигурация

После публикации конфигурации можно изменить файл:

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

Пакет также можно настроить через переменные окружения:

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

## Использование

После установки откройте страницу входа MoonShine. При стандартном префиксе это:

```text
/admin/login
```

На странице входа появятся ссылки:

```text
/admin/register
/admin/forgot-password
```

Новые пользователи создаются в таблице пользователей MoonShine и получают настроенную роль по умолчанию.

## Сброс пароля

Пакет настраивает Password Broker `moonshine` и использует MoonShine User Provider.

Встроенная модель пользователя расширяет стандартную модель MoonShine и реализует Laravel contract для сброса пароля. Это нужно, чтобы пользователи MoonShine могли получать уведомления для сброса пароля.

Убедитесь, что в приложении есть стандартная таблица токенов сброса пароля:

```bash
php artisan migrate
```

## Пользовательские страницы

Вы можете заменить встроенные страницы через конфигурацию:

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

Ваши пользовательские страницы должны рендерить формы, которые отправляются на маршруты этого пакета.

## Отключение функций

Отключить весь пакет:

```dotenv
MOONSHINE_REGISTER_ENABLED=false
```

Отключить только сброс пароля:

```dotenv
MOONSHINE_REGISTER_PASSWORD_RESET_ENABLED=false
```

Отключить только дополнительные ссылки на странице входа:

```dotenv
MOONSHINE_REGISTER_LOGIN_LINK_ENABLED=false
```

## Тестирование

Запустить все тесты:

```bash
php artisan test
```

Запустить только feature-тесты для этого пакета:

```bash
php artisan test tests/Feature/MoonShineRegisterTest.php
```

## Лицензия

MIT License.
