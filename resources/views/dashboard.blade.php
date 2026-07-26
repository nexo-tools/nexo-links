<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Links') }}
            </h2>
            <a href="{{ url('/'.$page->username) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                {{ __('View my page') }} ↗
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($openReportsCount > 0)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm text-amber-800">
                        {{ trans_choice('You have :count open report.|You have :count open reports.', $openReportsCount, ['count' => $openReportsCount]) }}
                        <a href="{{ route('reports.index') }}" class="font-medium underline hover:no-underline">{{ __('Review reports') }}</a>
                    </p>
                </div>
            @endif

            @if (session('status') === 'link-created')
                <p class="text-sm text-green-600">{{ __('Link created.') }}</p>
            @elseif (session('status') === 'link-updated')
                <p class="text-sm text-green-600">{{ __('Link updated.') }}</p>
            @elseif (session('status') === 'link-deleted')
                <p class="text-sm text-green-600">{{ __('Link deleted.') }}</p>
            @elseif (session('status') === 'social-created')
                <p class="text-sm text-green-600">{{ __('Social icon added.') }}</p>
            @elseif (session('status') === 'social-deleted')
                <p class="text-sm text-green-600">{{ __('Social icon removed.') }}</p>
            @endif

            <!-- Add link -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6" x-data="{ open: {{ $errors->hasAny(['title', 'url']) && ! old('editing') ? 'true' : 'false' }} }">
                <button type="button" x-show="! open" @click="open = true" x-bind:aria-expanded="open" class="w-full text-center text-sm font-medium text-indigo-600 hover:text-indigo-900">
                    + {{ __('Add link') }}
                </button>

                <form method="POST" action="{{ route('links.store') }}" x-show="open" x-cloak class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required maxlength="120" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div x-data="{ wa: false, code: '+54', phone: '', message: '' }">
                        <x-input-label for="url" :value="__('URL')" />
                        <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url')" required maxlength="2048" placeholder="https://…" />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />

                        <button type="button" @click="wa = ! wa" class="mt-2 text-sm text-green-700 hover:text-green-900 underline">
                            {{ __('Build a WhatsApp link') }}
                        </button>

                        <div x-show="wa" x-cloak class="mt-2 space-y-2 rounded-md bg-green-50 p-3">
                            <div class="flex flex-wrap gap-2">
                                <select x-model="code" class="rounded-md border-line text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                                    @foreach ($phonePrefixes as $prefixCode => $prefixLabel)
                                        <option value="{{ $prefixCode }}">{{ $prefixLabel }}</option>
                                    @endforeach
                                </select>
                                <input type="text" x-model="phone" inputmode="numeric" placeholder="{{ __('1122334455') }}"
                                       class="block flex-1 min-w-32 rounded-md border-line text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                                <input type="text" x-model="message" placeholder="{{ __('Prefilled message (optional)') }}"
                                       class="block w-full rounded-md border-line text-sm shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <button type="button"
                                    @click="$el.closest('form').querySelector('#url').value = 'https://wa.me/' + (code + phone).replace(/[^0-9]/g, '') + (message ? '?text=' + encodeURIComponent(message) : ''); wa = false"
                                    class="rounded-md bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700">
                                {{ __('Use this link') }}
                            </button>
                        </div>
                    </div>

                    @include('links.fields')

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                        <button type="button" @click="open = false" class="text-sm text-muted hover:text-ink">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>

            <!-- Links list -->
            @if ($links->isEmpty())
                <div class="bg-surface shadow-sm sm:rounded-lg p-6 text-center text-muted">
                    {{ __('No links yet. Add your first one!') }}
                </div>
            @else
                <ul id="links-list" data-reorder-url="{{ route('links.reorder') }}" class="space-y-3">
                    @foreach ($links as $link)
                        <li data-link-id="{{ $link->id }}" class="bg-surface shadow-sm sm:rounded-lg p-4" x-data="{ editing: {{ $errors->hasAny(['title', 'url']) && old('editing') == $link->id ? 'true' : 'false' }} }">
                            <div class="flex items-center gap-3">
                                <button type="button" data-drag-handle class="cursor-grab text-muted hover:text-ink touch-none" aria-label="{{ __('Drag to reorder') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm0 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm9-13a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                    </svg>
                                </button>

                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-ink truncate">
                                        @if ($link->is_highlighted)
                                            <span class="text-amber-500" title="{{ __('Highlighted') }}">★</span>
                                        @endif
                                        {{ $link->title }}
                                        @unless ($link->is_visible)
                                            <span class="ms-1 inline-block rounded bg-bg-subtle px-1.5 py-0.5 text-xs text-muted">{{ __('Hidden') }}</span>
                                        @endunless
                                        @if ($link->starts_at?->isFuture())
                                            <span class="ms-1 inline-block rounded bg-indigo-50 px-1.5 py-0.5 text-xs text-indigo-600" title="{{ $link->starts_at }}">{{ __('Scheduled') }}</span>
                                        @endif
                                        @if ($link->ends_at?->isPast())
                                            <span class="ms-1 inline-block rounded bg-amber-50 px-1.5 py-0.5 text-xs text-amber-700" title="{{ $link->ends_at }}">{{ __('Expired') }}</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-muted truncate">{{ $link->url }}</p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-sm text-muted tabular-nums" title="{{ __('Total clicks') }}">{{ $link->clicks_count }} ⟶</span>

                                    <form method="POST" action="{{ route('links.update', $link) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_visible" value="{{ $link->is_visible ? 0 : 1 }}">
                                        <button class="text-sm text-muted hover:text-ink underline">
                                            {{ $link->is_visible ? __('Hide') : __('Show') }}
                                        </button>
                                    </form>

                                    <button type="button" @click="editing = ! editing" x-bind:aria-expanded="editing" class="text-sm text-muted hover:text-ink underline">{{ __('Edit') }}</button>

                                    <form method="POST" action="{{ route('links.destroy', $link) }}" @submit="confirm('{{ __('Delete this link?') }}') || $event.preventDefault()">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm text-red-600 hover:text-red-900 underline">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('links.update', $link) }}" x-show="editing" x-cloak class="mt-4 space-y-4 border-t border-line pt-4">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="editing" value="{{ $link->id }}">
                                <div>
                                    <x-input-label :value="__('Title')" />
                                    <x-text-input name="title" type="text" class="mt-1 block w-full" :value="old('editing') == $link->id ? old('title') : $link->title" required maxlength="120" />
                                </div>
                                <div>
                                    <x-input-label :value="__('URL')" />
                                    <x-text-input name="url" type="text" class="mt-1 block w-full" :value="old('editing') == $link->id ? old('url') : $link->url" required maxlength="2048" />
                                </div>

                                @include('links.fields', ['link' => $link])

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                                    <button type="button" @click="editing = false" class="text-sm text-muted hover:text-ink">{{ __('Cancel') }}</button>
                                </div>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif

            <!-- Social icons -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-ink">{{ __('Social icons') }}</h3>
                <p class="mt-1 text-sm text-muted">{{ __('Shown as icons at the bottom of your page. Prefer a big button? Add it as a regular link instead.') }}</p>

                @if ($socialLinks->isNotEmpty())
                    <ul class="mt-4 flex flex-wrap gap-3">
                        @foreach ($socialLinks as $social)
                            <li class="flex items-center gap-2 rounded-full border border-line py-1 ps-3 pe-1">
                                <span class="text-sm text-ink">{{ $social->label() }}</span>
                                <span class="text-sm text-muted max-w-32 truncate">{{ $social->value }}</span>
                                <form method="POST" action="{{ route('socials.destroy', $social) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="flex h-6 w-6 items-center justify-center rounded-full text-subtle hover:bg-red-50 hover:text-red-600" aria-label="{{ __('Remove :platform', ['platform' => $social->label()]) }}">×</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('socials.store') }}" class="mt-4 flex flex-wrap items-start gap-3"
                      x-data="{
                          platform: '{{ old('platform', 'instagram') }}',
                          code: '+54',
                          national: '',
                          meta: {{ Js::from($socialMeta) }},
                          get isPhone() { return this.meta[this.platform].type === 'phone' },
                          get prefix() { return this.meta[this.platform].prefix },
                          get phoneValue() { return this.code + this.national.replace(/[^0-9]/g, '') },
                      }">
                    @csrf
                    <select name="platform" x-model="platform" aria-label="{{ __('Platform') }}" class="rounded-md border-line text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($socialPlatforms as $key => $platform)
                            <option value="{{ $key }}" @selected(old('platform') === $key)>{{ $platform['label'] }}</option>
                        @endforeach
                    </select>

                    <div class="flex-1 min-w-48">
                        <!-- Handle / email / URL -->
                        <div x-show="! isPhone" class="flex items-center gap-1">
                            <span x-show="prefix" x-text="prefix" class="text-sm text-muted"></span>
                            <x-text-input name="value" type="text" class="block w-full text-sm" :value="old('value')"
                                          x-bind:disabled="isPhone" aria-label="{{ __('Your handle, email or URL') }}"
                                          placeholder="{{ __('Your handle, email or URL') }}" required />
                        </div>

                        <!-- Phone with country selector -->
                        <div x-show="isPhone" x-cloak class="flex items-center gap-2">
                            <select x-model="code" x-bind:disabled="! isPhone" aria-label="{{ __('Country code') }}" class="rounded-md border-line text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($phonePrefixes as $code => $label)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="text" x-model="national" inputmode="numeric" placeholder="{{ __('1122334455') }}"
                                   aria-label="{{ __('Phone number') }}"
                                   class="block w-full rounded-md border-line text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <input type="hidden" name="value" x-bind:value="phoneValue" x-bind:disabled="! isPhone">
                        </div>

                        <x-input-error :messages="$errors->get('platform')" class="mt-1" />
                        <x-input-error :messages="$errors->get('value')" class="mt-1" />
                    </div>
                    <x-primary-button>{{ __('Add') }}</x-primary-button>
                </form>
            </div>

            <!-- Share -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-ink">{{ __('Share your page') }}</h3>

                <div class="mt-4 flex flex-wrap items-center gap-6">
                    <img src="{{ route('qr.show') }}" alt="{{ __('QR code that opens your page') }}"
                         class="h-32 w-32 rounded-lg border border-line" width="128" height="128">

                    <div class="space-y-3" x-data="{ copied: false }">
                        <p class="text-sm text-muted break-all">{{ route('page.show', $page->username) }}</p>

                        <div class="flex flex-wrap gap-3">
                            <button type="button"
                                    @click="navigator.clipboard.writeText('{{ route('page.show', $page->username) }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                    class="rounded-md bg-bg-subtle px-3 py-1.5 text-sm font-medium text-ink hover:bg-line">
                                <span x-show="! copied">{{ __('Copy URL') }}</span>
                                <span x-show="copied" x-cloak class="text-green-700">{{ __('Copied!') }}</span>
                            </button>

                            <a href="{{ route('qr.show', ['download' => 1]) }}"
                               class="rounded-md bg-bg-subtle px-3 py-1.5 text-sm font-medium text-ink hover:bg-line">
                                {{ __('Download QR (SVG)') }}
                            </a>
                        </div>

                        <p class="text-xs text-muted">{{ __('Print it, add it to a business card or a story — it always points to your page.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
