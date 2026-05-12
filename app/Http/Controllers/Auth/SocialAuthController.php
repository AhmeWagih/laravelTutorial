<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as OAuthUser;

class SocialAuthController extends Controller
{
    private const ALLOWED = ['github', 'google'];

    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $this->assertAllowedProvider($provider);

        if ($request->routeIs('oauth.connect')) {
            session(['oauth_linking' => true]);
        } else {
            session()->forget('oauth_linking');
        }

        // Fix: Socialite v5+ no longer has a ->scopes() method (renamed to ->setScopes()).
        $driver = Socialite::driver($provider);

        if ($provider === 'github') {
            if (method_exists($driver, 'setScopes')) {
                $driver->setScopes(['read:user', 'user:email']);
            }
        }

        return $driver->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $this->assertAllowedProvider($provider);

        /** @var OAuthUser $oauthUser */
        $oauthUser = Socialite::driver($provider)->user();

        $linking = (bool) session()->pull('oauth_linking', false);

        if ($linking) {
            return $this->linkAuthenticatedUser($request, $provider, $oauthUser);
        }

        $user = $this->findOrCreateUser($provider, $oauthUser);
        $this->syncProviderProfile($user, $provider, $oauthUser);
        $user->save();

        Auth::login($user, true);

        return redirect()->intended(route('tasks.index'));
    }

    private function linkAuthenticatedUser(Request $request, string $provider, OAuthUser $oauthUser): RedirectResponse
    {
        abort_unless(Auth::check(), 403);

        /** @var User $account */
        $account = Auth::user();

        $providerIdColumn = $this->providerIdColumn($provider);
        $socialId = (string) $oauthUser->getId();

        $conflict = User::query()
            ->where($providerIdColumn, $socialId)
            ->whereKeyNot($account->getKey())
            ->exists();

        if ($conflict) {
            return redirect()
                ->route('profile.edit')
                ->withErrors([
                    'oauth' => __('This :provider account is already linked to another user.', ['provider' => $provider]),
                ]);
        }

        $this->syncProviderProfile($account, $provider, $oauthUser);
        $account->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'oauth-linked');
    }

    private function findOrCreateUser(string $provider, OAuthUser $oauthUser): User
    {
        $providerIdColumn = $this->providerIdColumn($provider);
        $socialId = (string) $oauthUser->getId();
        $email = $oauthUser->getEmail();

        /** @var User|null $user */
        $user = User::query()->where($providerIdColumn, $socialId)->first();

        if (! $user && $email) {
            $user = User::query()->where('email', $email)->first();
        }

        if ($user) {
            return $user;
        }

        $resolvedEmail = $email ?: $this->uniquePlaceholderEmail($provider, $socialId);

        return User::create([
            'name' => $oauthUser->getName()
                ?: $oauthUser->getNickname()
                ?: Str::before($resolvedEmail, '@'),
            'email' => $resolvedEmail,
            'password' => null,
            'email_verified_at' => $email ? now() : null,
        ]);
    }

    private function syncProviderProfile(User $user, string $provider, OAuthUser $oauthUser): void
    {
        if ($provider === 'github') {
            $user->github_id = (string) $oauthUser->getId();
            $user->github_username = $oauthUser->getNickname();
            $user->github_avatar_url = $oauthUser->getAvatar();

            return;
        }

        $user->google_id = (string) $oauthUser->getId();
        $user->google_avatar_url = $oauthUser->getAvatar();
    }

    private function uniquePlaceholderEmail(string $provider, string $socialId): string
    {
        $base = "{$provider}_{$socialId}@oauth.invalid";
        if (! User::query()->where('email', $base)->exists()) {
            return $base;
        }

        return "{$provider}_{$socialId}_".Str::lower(Str::random(8)).'@oauth.invalid';
    }

    private function providerIdColumn(string $provider): string
    {
        return $provider === 'google' ? 'google_id' : 'github_id';
    }

    private function assertAllowedProvider(string $provider): void
    {
        abort_unless(in_array($provider, self::ALLOWED, true), 404);
    }
}