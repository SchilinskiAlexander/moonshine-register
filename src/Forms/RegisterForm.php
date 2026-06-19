<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Forms;

use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Contracts\UI\FormContract;
use MoonShine\Support\Traits\Makeable;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;
use MoonShine\UI\Fields\Text;

final class RegisterForm implements FormContract
{
    use Makeable;

    public function __construct(
        private readonly string $action,
    ) {
    }

    public function __invoke(): FormBuilderContract
    {
        $fields = [
            Text::make(__('moonshine-register::register.name'), 'name')
                ->required()
                ->customAttributes([
                    'autofocus' => true,
                    'autocomplete' => 'name',
                ]),

            Email::make(__('moonshine-register::register.email'), 'username')
                ->required()
                ->customAttributes([
                    'autocomplete' => 'username',
                ]),

            Password::make(__('moonshine-register::register.password'), 'password')
                ->required()
                ->customAttributes([
                    'autocomplete' => 'new-password',
                ]),

            PasswordRepeat::make(__('moonshine-register::register.password_confirmation'), 'password_confirmation')
                ->required()
                ->customAttributes([
                    'autocomplete' => 'new-password',
                ]),
        ];

        return FormBuilder::make()
            ->class('authentication-form')
            ->action($this->action)
            ->errorsAbove(false)
            ->fields($fields)
            ->submit(__('moonshine-register::register.submit'), [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
