{{-- Goes to the OLD address: the new one already knows. --}}
<x-nexo-mail::layout
    :title="__('The email of your account was changed')"
    :preheader="__('The email of your account was changed')">

    <h1 class="nexo-ink" style="margin:0 0 16px; font-size:20px; line-height:1.3; font-weight:700; color:#18181b;">
        {{ __('The email of your account was changed') }}
    </h1>

    <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">
        {{ __('Your account now uses :new. This message goes to :old, the address it used before.', ['new' => $newEmail, 'old' => $previousEmail]) }}
    </p>

    <p class="nexo-muted nexo-rule" style="margin:20px 0 0; padding-top:16px; border-top:1px solid #e4e4e7; font-size:12px; line-height:1.6; color:#a1a1aa;">
        {{ __('If it was not you, write to us now: whoever changed it can already receive everything we send about this account.') }}
    </p>
</x-nexo-mail::layout>
