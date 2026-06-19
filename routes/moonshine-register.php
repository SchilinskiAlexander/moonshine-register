<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use SchilinskiAlexander\MoonShineRegister\Http\Controllers\PasswordResetController;
use SchilinskiAlexander\MoonShineRegister\Http\Controllers\RegisterController;

if (config('moonshine-register.enabled', true) && moonshineConfig()->isUseRoutes()) {
    Route::moonshine(static function (Router $router): void {
        $route = trim((string) config('moonshine-register.route', 'register'), '/');
        $passwordResetRoute = trim((string) config('moonshine-register.password_reset.route', 'forgot-password'), '/');
        $passwordResetTokenRoute = trim((string) config('moonshine-register.password_reset.reset_route', 'reset-password'), '/');

        Route::middleware('guest:' . moonshineConfig()->getGuard())
            ->controller(RegisterController::class)
            ->group(static function () use ($route): void {
                Route::get($route, 'create')->name('register');
                Route::post($route, 'store')->name('register.store');
            });

        if (config('moonshine-register.password_reset.enabled', true)) {
            Route::middleware('guest:' . moonshineConfig()->getGuard())
                ->controller(PasswordResetController::class)
                ->group(static function () use ($passwordResetRoute, $passwordResetTokenRoute): void {
                    Route::get($passwordResetRoute, 'create')->name('password.request');
                    Route::post($passwordResetRoute, 'store')->name('password.email');
                    Route::get($passwordResetTokenRoute . '/{token}', 'reset')->name('password.reset');
                    Route::post($passwordResetTokenRoute . '/{token}', 'update')->name('password.update');
                });
        }
    });
}
