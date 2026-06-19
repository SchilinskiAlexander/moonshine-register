<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use MoonShine\Laravel\MoonShineAuth;
use SchilinskiAlexander\MoonShineRegister\Http\Requests\ForgotPasswordRequest;
use SchilinskiAlexander\MoonShineRegister\Http\Requests\ResetPasswordRequest;
use SchilinskiAlexander\MoonShineRegister\Pages\ForgotPasswordPage;
use SchilinskiAlexander\MoonShineRegister\Pages\ResetPasswordPage;
use Symfony\Component\HttpFoundation\Response;

final class PasswordResetController extends Controller
{
    public function create(): Renderable|Response|string
    {
        abort_unless(config('moonshine-register.enabled', true) && config('moonshine-register.password_reset.enabled', true), 404);

        if (MoonShineAuth::getGuard()->check()) {
            return redirect()->route(moonshineConfig()->getHomeRoute());
        }

        $pageClass = config('moonshine-register.password_reset.request_page', ForgotPasswordPage::class);
        $page = moonshine()->getContainer($pageClass);

        if ($page->isResponseModified()) {
            return $page->getModifiedResponse();
        }

        return $page->render();
    }

    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        abort_unless(config('moonshine-register.enabled', true) && config('moonshine-register.password_reset.enabled', true), 404);

        $broker = (string) config('moonshine-register.password_reset.broker', 'moonshine');
        Password::broker($broker)->sendResetLink($request->only('email'));

        return redirect()
            ->route('moonshine.password.request')
            ->with('status', __('moonshine-register::register.reset_link_sent'));
    }

    public function reset(string $token): Renderable|Response|string
    {
        abort_unless(config('moonshine-register.enabled', true) && config('moonshine-register.password_reset.enabled', true), 404);

        if (MoonShineAuth::getGuard()->check()) {
            return redirect()->route(moonshineConfig()->getHomeRoute());
        }

        $pageClass = config('moonshine-register.password_reset.reset_page', ResetPasswordPage::class);
        $page = moonshine()->getContainer($pageClass);

        if ($page->isResponseModified()) {
            return $page->getModifiedResponse();
        }

        return $page->render();
    }

    public function update(ResetPasswordRequest $request): RedirectResponse
    {
        abort_unless(config('moonshine-register.enabled', true) && config('moonshine-register.password_reset.enabled', true), 404);

        $broker = (string) config('moonshine-register.password_reset.broker', 'moonshine');

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password): void {
                $passwordField = moonshineConfig()->getUserField('password', 'password');
                $user->{$passwordField} = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('moonshine.login')
                ->with('status', __('moonshine-register::register.reset_success'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => __('moonshine-register::register.reset_failed'),
            ]);
    }
}
