<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Pages;

use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Core\Attributes\Layout;
use MoonShine\Laravel\Layouts\LoginLayout;
use MoonShine\Laravel\Pages\Page;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\FlexibleRender;
use SchilinskiAlexander\MoonShineRegister\Forms\ResetPasswordForm;

#[SkipMenu]
#[Layout(LoginLayout::class)]
final class ResetPasswordPage extends Page
{
    protected function booted(): void
    {
        $this->title(__('moonshine-register::register.reset_title'));
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $token = (string) request()->route('token');
        $email = (string) request()->string('email')->toString();

        $components = [];

        if (session()->has('status')) {
            $components[] = FlexibleRender::make(
                '<div class="mb-4 rounded-lg border border-base-stroke bg-base-100 px-4 py-3 text-sm text-base-text">'
                . e((string) session('status'))
                . '</div>'
            );
        }

        $components[] = ResetPasswordForm::make(
            action: route('moonshine.password.update', ['token' => $token]),
            email: $email,
            token: $token
        )();

        $components[] = FlexibleRender::make(
            '<div class="authentication-footer description text-center">'
            . '<div><a href="'
            . e(route('moonshine.login'))
            . '">'
            . e(__('moonshine-register::register.login_link'))
            . '</a></div>'
            . '<div><a href="'
            . e(route('moonshine.register'))
            . '">'
            . e(__('moonshine-register::register.register_link'))
            . '</a></div>'
            . '</div>'
        );

        return $components;
    }

    protected function modifyLayout(\MoonShine\Contracts\UI\LayoutContract $layout): \MoonShine\Contracts\UI\LayoutContract
    {
        if ($layout instanceof LoginLayout) {
            $layout
                ->title(__('moonshine-register::register.reset_title'))
                ->description(
                    __('moonshine-register::register.reset_description', [
                        'email' => (string) request()->string('email'),
                    ])
                );
        }

        return $layout;
    }
}
