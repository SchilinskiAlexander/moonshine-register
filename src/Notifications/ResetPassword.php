<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;

final class ResetPassword extends BaseResetPassword
{
    protected function resetUrl($notifiable): string
    {
        return url(route('moonshine.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
