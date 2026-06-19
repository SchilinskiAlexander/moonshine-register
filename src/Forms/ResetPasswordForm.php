<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Forms;

use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Contracts\UI\FormContract;
use MoonShine\Support\Traits\Makeable;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Hidden;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\PasswordRepeat;

final class ResetPasswordForm implements FormContract
{
    use Makeable;

    public function __construct(
        private readonly string $action,
        private readonly string $email,
        private readonly string $token,
    ) {
    }

    public function __invoke(): FormBuilderContract
    {
        return FormBuilder::make()
            ->class('authentication-form')
            ->action($this->action)
            ->errorsAbove(false)
            ->fields([
                Hidden::make('token')
                    ->setValue($this->token),

                Email::make(__('moonshine-register::register.email'), 'email')
                    ->required()
                    ->setValue($this->email)
                    ->customAttributes([
                        'autocomplete' => 'email',
                    ]),

                Password::make(__('moonshine-register::register.password'), 'password')
                    ->required()
                    ->customAttributes([
                        'autofocus' => true,
                        'autocomplete' => 'new-password',
                    ]),

                PasswordRepeat::make(__('moonshine-register::register.password_confirmation'), 'password_confirmation')
                    ->required()
                    ->customAttributes([
                        'autocomplete' => 'new-password',
                    ]),
            ])
            ->submit(__('moonshine-register::register.reset_submit'), [
                'class' => 'btn-primary btn-lg w-full',
            ]);
    }
}
