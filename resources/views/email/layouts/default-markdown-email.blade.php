{{-- @formatter:off --}}
<x-mail::message>

    <x-slot:header>
        <x-mail::header :url="config('app.frontend_url')"></x-mail::header>
    </x-slot:header>

    @yield("body")

</x-mail::message>
{{-- @formatter:on --}}
