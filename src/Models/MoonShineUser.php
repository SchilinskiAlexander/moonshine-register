<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Models;

use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use MoonShine\Laravel\Models\MoonshineUser as BaseMoonShineUser;
use SchilinskiAlexander\MoonShineRegister\Notifications\ResetPassword;

class MoonShineUser extends BaseMoonShineUser implements CanResetPasswordContract
{
    use CanResetPasswordTrait;

    protected $table = 'moonshine_users';

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPassword($token));
    }
}
