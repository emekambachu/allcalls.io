@props(['user' => null])

<x-mail::layout :user="$user ?? null">
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            <img src="https://allcalls.io/img/new-logo-black.png" width="30%" alt="">
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
    <x-slot:subcopy>
        <x-mail::subcopy>
            {{ $subcopy }}
        </x-mail::subcopy>
    </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.') | Unsubscribe
            @isset($user)
            <br>
            <p>{{ $user->unsubscribeToken() }}</p>

            @endisset
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>