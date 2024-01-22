<x-mail::layout>
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
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
            @if(isset($user))
            <!-- <a href="{{ url('/unsubscribe-to-emails/' . $user->unsubscribeToken) }}" style="color: #999999; text-decoration: underline;">Unsubscribe</a>
            <br> -->
            Unsubscribe
            @endif


        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>