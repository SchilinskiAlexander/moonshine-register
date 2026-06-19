<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\ConfiguratorContract;
use MoonShine\Laravel\Models\MoonshineUser as MoonShineBaseUser;
use MoonShine\Laravel\Pages\LoginPage as MoonShineLoginPage;
use SchilinskiAlexander\MoonShineRegister\Models\MoonShineUser;
use SchilinskiAlexander\MoonShineRegister\Pages\LoginPage;

final class MoonShineRegisterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/moonshine-register.php', 'moonshine-register');
        $this->configureMoonShineAuth();
        $this->registerLoginPageLink();
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/moonshine-register.php');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'moonshine-register');
        $this->configureMoonShineAuth();
        $this->registerLoginPageLink();

        $this->publishes([
            __DIR__ . '/../config/moonshine-register.php' => config_path('moonshine-register.php'),
        ], 'moonshine-register-config');

        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/moonshine-register'),
        ], 'moonshine-register-lang');
    }

    private function registerLoginPageLink(): void
    {
        if (! config('moonshine-register.enabled', true)) {
            return;
        }

        if (! config('moonshine-register.login_link.enabled', true)) {
            return;
        }

        $loginPage = config('moonshine-register.login_link.page', LoginPage::class);
        $currentLoginPage = config('moonshine.pages.login', MoonShineLoginPage::class);

        if (! $this->canReplaceLoginPage($currentLoginPage, $loginPage)) {
            return;
        }

        config([
            'moonshine.pages.login' => $loginPage,
        ]);

        if ($this->app->bound(ConfiguratorContract::class)) {
            $config = $this->app->make(ConfiguratorContract::class);
            $configLoginPage = $config->get('pages.login', MoonShineLoginPage::class);

            if ($this->canReplaceLoginPage($configLoginPage, $loginPage)) {
                $pages = $config->get('pages', []);
                $pages['login'] = $loginPage;

                $config->set('pages', $pages);
            }
        }
    }

    private function canReplaceLoginPage(mixed $currentLoginPage, string $loginPage): bool
    {
        return \in_array($currentLoginPage, [
            MoonShineLoginPage::class,
            $loginPage,
        ], true);
    }

    private function configureMoonShineAuth(): void
    {
        if (! config('moonshine-register.enabled', true)) {
            return;
        }

        $defaultModel = MoonShineBaseUser::class;
        $configuredModel = (string) config('moonshine.auth.model', $defaultModel);
        $packageModel = (string) config('moonshine-register.password_reset.user_model', MoonShineUser::class);

        if ($configuredModel === $defaultModel) {
            Config::set('moonshine.auth.model', $packageModel);
            Config::set('auth.providers.moonshine.model', $packageModel);
        }

        Config::set('auth.passwords.moonshine', [
            'provider' => 'moonshine',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ]);
    }
}
