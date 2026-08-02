{{-- To the page owner. The reason travels; the reporter does not — a moderation
     tool that hands over identities becomes a retaliation tool. --}}
<x-nexo-mail::layout :title="__('Your page was reported')" :preheader="__('Someone reported your page.')">
    <h1 class="nexo-ink" style="margin:0 0 16px; font-size:20px; line-height:1.3; font-weight:700; color:#18181b;">
        {{ __('Your page was reported') }}
    </h1>

    <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">
        {{ __('Somebody reported :page. Nothing has changed on your page: we are telling you because you should hear it from us and not from an empty profile.', ['page' => '@'.$page->username]) }}
    </p>

    <p style="margin:0 0 4px; font-size:14px; line-height:1.6;"><strong>{{ __('Reason given') }}</strong></p>
    <p class="nexo-panel nexo-ink" style="margin:0 0 20px; padding:12px 14px; background-color:#fafafa; border-radius:8px; font-size:14px; line-height:1.6; white-space:pre-line; color:#18181b;">{{ $reason }}</p>

    <x-nexo-mail::button :url="$reportsUrl">{{ __('See the reports on my page') }}</x-nexo-mail::button>
</x-nexo-mail::layout>
