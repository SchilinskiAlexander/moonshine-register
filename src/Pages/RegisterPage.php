<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Pages;

use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Core\Attributes\Layout;
use MoonShine\Laravel\Layouts\LoginLayout;
use MoonShine\Laravel\Pages\Page;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\FlexibleRender;
use SchilinskiAlexander\MoonShineRegister\Forms\RegisterForm;

#[SkipMenu]
#[Layout(LoginLayout::class)]
final class RegisterPage extends Page
{
    protected function booted(): void
    {
        $this->title(__('moonshine-register::register.title'));
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $components = [];

        if (session()->has('status')) {
            $components[] = FlexibleRender::make(
                '<div class="mb-4 rounded-lg border border-base-stroke bg-base-100 px-4 py-3 text-sm text-base-text">'
                . e((string) session('status'))
                . '</div>'
            );
        }

        $components[] = RegisterForm::make(
            action: route('moonshine.register.store')
        )();

        $components[] = FlexibleRender::make(
            '<div class="authentication-footer description text-center"><a href="'
            . e(route('moonshine.login'))
            . '">'
            . e(__('moonshine-register::register.login_link'))
            . '</a></div>'
        );

        return [
            ...$components,
        ];
    }

    protected function modifyLayout(\MoonShine\Contracts\UI\LayoutContract $layout): \MoonShine\Contracts\UI\LayoutContract
    {
        if ($layout instanceof LoginLayout) {
            $layout
                ->title(__('moonshine-register::register.title'))
                ->description(__('moonshine-register::register.description'));
        }

        return $layout;
    }
}
