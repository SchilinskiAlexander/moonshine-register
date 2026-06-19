<?php

declare(strict_types=1);

use MoonShine\Laravel\Models\MoonshineUserRole;
use SchilinskiAlexander\MoonShineRegister\Models\MoonShineUser;
use SchilinskiAlexander\MoonShineRegister\Pages\ForgotPasswordPage;
use SchilinskiAlexander\MoonShineRegister\Pages\LoginPage;
use SchilinskiAlexander\MoonShineRegister\Pages\ResetPasswordPage;
use SchilinskiAlexander\MoonShineRegister\Pages\RegisterPage;

return [
    'enabled' => env('MOONSHINE_REGISTER_ENABLED', true),

    'route' => env('MOONSHINE_REGISTER_ROUTE', 'register'),

    'page' => RegisterPage::class,

    'login_link' => [
        'enabled' => env('MOONSHINE_REGISTER_LOGIN_LINK_ENABLED', true),
        'page' => LoginPage::class,
    ],

    'password_reset' => [
        'enabled' => env('MOONSHINE_REGISTER_PASSWORD_RESET_ENABLED', true),
        'route' => env('MOONSHINE_REGISTER_PASSWORD_RESET_ROUTE', 'forgot-password'),
        'reset_route' => env('MOONSHINE_REGISTER_PASSWORD_RESET_RESET_ROUTE', 'reset-password'),
        'broker' => env('MOONSHINE_REGISTER_PASSWORD_RESET_BROKER', 'moonshine'),
        'user_model' => env('MOONSHINE_REGISTER_USER_MODEL', MoonShineUser::class),
        'request_page' => ForgotPasswordPage::class,
        'reset_page' => ResetPasswordPage::class,
    ],

    'auto_login' => env('MOONSHINE_REGISTER_AUTO_LOGIN', false),

    'default_role_id' => env('MOONSHINE_REGISTER_ROLE_ID', MoonshineUserRole::DEFAULT_ROLE_ID),

    'role_column' => env('MOONSHINE_REGISTER_ROLE_COLUMN', 'moonshine_user_role_id'),
];
