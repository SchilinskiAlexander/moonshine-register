# MoonShine Register

[English](README.md) | [Русский](README.ru.md)

Registrierungs- und Passwort-zurücksetzen-Seiten für die Authentifizierung von [MoonShine v4](https://moonshine-laravel.com/).

Dieses Package ergänzt den MoonShine-Admin-Login um eine öffentliche Registrierung. Optional ersetzt es die Standard-Login-Seite von MoonShine durch eine Login-Seite mit Links zur Registrierung und zum Zurücksetzen des Passworts.

## Funktionen

- Admin-Registrierungsseite innerhalb der MoonShine-Routengruppe
- Optionaler Passwort-Reset für MoonShine-Benutzer
- Optionaler automatischer Login nach der Registrierung
- Konfigurierbare Routen und Page-Klassen
- Konfigurierbare Standardrolle für neue MoonShine-Benutzer
- Deutsche und englische Übersetzungen
- Automatische Laravel Package Discovery

## Voraussetzungen

- PHP `^8.3`
- Laravel-Komponenten `^12.0` oder `^13.0`
- MoonShine `^4.15`

## Installation

Installiere das Package mit Composer:

```bash
composer require schilinskialexander/moonshine-register
```

Laravel erkennt den Service Provider automatisch.

Veröffentliche die Konfigurationsdatei, wenn du das Package anpassen möchtest:

```bash
php artisan vendor:publish --tag=moonshine-register-config
```

Veröffentliche die Übersetzungen, wenn du die Sprachdateien bearbeiten möchtest:

```bash
php artisan vendor:publish --tag=moonshine-register-lang
```

Führe die Migrationen aus, falls die MoonShine-Tabellen noch nicht erstellt wurden:

```bash
php artisan migrate
```

## Was hinzugefügt wird

Standardmäßig registriert das Package diese Routen unter deinem konfigurierten MoonShine-Prefix.

Wenn dein MoonShine-Prefix `admin` ist, entstehen diese Routen:

| Methode | URL | Routenname |
| --- | --- | --- |
| `GET` | `/admin/register` | `moonshine.register` |
| `POST` | `/admin/register` | `moonshine.register.store` |
| `GET` | `/admin/forgot-password` | `moonshine.password.request` |
| `POST` | `/admin/forgot-password` | `moonshine.password.email` |
| `GET` | `/admin/reset-password/{token}` | `moonshine.password.reset` |
| `POST` | `/admin/reset-password/{token}` | `moonshine.password.update` |

Die Routen werden nur registriert, wenn MoonShine-Routen aktiviert sind.

## Konfiguration

Nach dem Veröffentlichen der Config kannst du diese Datei bearbeiten:

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

Du kannst das Package auch über Umgebungsvariablen konfigurieren:

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

## Verwendung

Nach der Installation öffnest du deine MoonShine-Login-Seite. Mit dem Standard-Prefix ist das:

```text
/admin/login
```

Die Login-Seite zeigt Links zu:

```text
/admin/register
/admin/forgot-password
```

Neue Benutzer werden in der MoonShine-Benutzertabelle erstellt und erhalten die konfigurierte Standardrolle.

## Passwort zurücksetzen

Das Package konfiguriert einen `moonshine` Password Broker und verwendet den MoonShine User Provider.

Das enthaltene User Model erweitert das Standardmodell von MoonShine und implementiert Laravels Passwort-Reset-Contract. Das ist notwendig, damit MoonShine-Benutzer Reset-Benachrichtigungen erhalten können.

Stelle sicher, dass deine Anwendung die Standardtabelle für Passwort-Reset-Tokens hat:

```bash
php artisan migrate
```

## Eigene Pages

Du kannst die enthaltenen Pages über die Config ersetzen:

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

Deine eigenen Pages sollten Formulare rendern, die an die Routen dieses Packages senden.

## Funktionen deaktivieren

Das gesamte Package deaktivieren:

```dotenv
MOONSHINE_REGISTER_ENABLED=false
```

Nur den Passwort-Reset deaktivieren:

```dotenv
MOONSHINE_REGISTER_PASSWORD_RESET_ENABLED=false
```

Nur die zusätzlichen Links auf der Login-Seite deaktivieren:

```dotenv
MOONSHINE_REGISTER_LOGIN_LINK_ENABLED=false
```

## Tests

Alle Tests ausführen:

```bash
php artisan test
```

Nur die Feature-Tests für dieses Package ausführen:

```bash
php artisan test tests/Feature/MoonShineRegisterTest.php
```

## Lizenz

MIT License.
