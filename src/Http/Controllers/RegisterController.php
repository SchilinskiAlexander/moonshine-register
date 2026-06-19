<?php

declare(strict_types=1);

namespace SchilinskiAlexander\MoonShineRegister\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use MoonShine\Laravel\MoonShineAuth;
use SchilinskiAlexander\MoonShineRegister\Http\Requests\RegisterFormRequest;
use SchilinskiAlexander\MoonShineRegister\Pages\RegisterPage;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController extends Controller
{
    public function create(): Renderable|Response|string
    {
        abort_unless(config('moonshine-register.enabled', true), 404);

        if (MoonShineAuth::getGuard()->check()) {
            return redirect()->route(moonshineConfig()->getHomeRoute());
        }

        $pageClass = config('moonshine-register.page', RegisterPage::class);
        $page = moonshine()->getContainer($pageClass);

        if ($page->isResponseModified()) {
            return $page->getModifiedResponse();
        }

        return $page->render();
    }

    public function store(RegisterFormRequest $request): RedirectResponse
    {
        abort_unless(config('moonshine-register.enabled', true), 404);

        $user = MoonShineAuth::getModel();

        $usernameField = moonshineConfig()->getUserField('username', 'email');
        $passwordField = moonshineConfig()->getUserField('password', 'password');
        $nameField = moonshineConfig()->getUserField('name', 'name');

        $user->{$usernameField} = $request->string('username')->toString();
        $user->{$passwordField} = Hash::make($request->string('password')->toString());

        if ($nameField !== false) {
            $user->{$nameField} = $request->string('name')->toString();
        }

        if (filled(config('moonshine-register.default_role_id'))) {
            $user->{(string) config('moonshine-register.role_column', 'moonshine_user_role_id')} = config('moonshine-register.default_role_id');
        }

        $user->save();

        if (config('moonshine-register.auto_login', false)) {
            MoonShineAuth::getGuard()->login($user);

            return redirect()->route(moonshineConfig()->getHomeRoute());
        }

        return redirect()
            ->route('moonshine.login')
            ->with('status', __('moonshine-register::register.created'));
    }
}
