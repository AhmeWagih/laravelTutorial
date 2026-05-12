<section class="border-t border-gray-100 pt-6 mt-8">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Connected accounts') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Link GitHub or Google to sign in faster next time.') }}
        </p>
    </header>

    @if (session('status') === 'oauth-linked')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="mt-4 text-sm text-green-600"
        >{{ __('Account linked successfully.') }}</p>
    @endif

    <x-input-error :messages="$errors->get('oauth')" class="mt-4"/>

    @if ($user->usesPasswordCredential())
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            {{-- GitHub --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-semibold text-gray-800">{{ __('GitHub') }}</p>
                @if ($user->github_id)
                    <div class="mt-3 flex items-center gap-3">
                        @if ($user->github_avatar_url)
                            <img src="{{ $user->github_avatar_url }}" alt=""
                                 width="36" height="36" class="h-9 w-9 rounded-full ring-2 ring-gray-200">
                        @endif
                        <div class="text-sm">
                            <p class="font-medium text-gray-900">{{ $user->github_username ?? __('Linked') }}</p>
                            <p class="text-xs text-gray-500">{{ __('Connected') }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="{{ route('oauth.connect', ['provider' => 'github']) }}">
                            <x-secondary-button type="button">
                                {{ __('Connect GitHub') }}
                            </x-secondary-button>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Google --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-semibold text-gray-800">{{ __('Google') }}</p>
                @if ($user->google_id)
                    <div class="mt-3 flex items-center gap-3">
                        @if ($user->google_avatar_url)
                            <img src="{{ $user->google_avatar_url }}" alt=""
                                 width="36" height="36" class="h-9 w-9 rounded-full ring-2 ring-gray-200">
                        @endif
                        <div class="text-sm">
                            <p class="font-medium text-gray-900">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">{{ __('Signed in with Google') }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="{{ route('oauth.connect', ['provider' => 'google']) }}">
                            <x-secondary-button type="button">
                                {{ __('Connect Google') }}
                            </x-secondary-button>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <p class="mt-4 text-sm text-gray-600">
            {{ __('You signed up with social login. Signing in via email and password lets you manage linked providers here.') }}
        </p>
    @endif
</section>
