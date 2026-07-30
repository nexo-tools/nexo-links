<x-app-layout :title="__('Design')">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Design') }}
            </h1>
            <a href="{{ url('/'.$page->username) }}" target="_blank" class="text-sm text-primary hover:text-primary-hover underline">
                {{ __('View my page') }} ↗
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('design.update') }}" enctype="multipart/form-data"
                  x-data="{ backgroundType: '{{ old('background_type', $page->background_type) }}', sending: false }"
                  @submit="sending = true"
                  class="space-y-6">
                @csrf
                @method('PATCH')

                @if (session('status') === 'design-updated')
                    <p class="nexo-flash" role="status">{{ __('Design updated.') }}</p>
                @endif

                <!-- Profile -->
                <div class="bg-surface shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="font-medium text-ink">{{ __('Profile') }}</h3>

                    <div>
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea id="bio" name="bio" rows="2" maxlength="160"
                                  class="mt-1 block w-full rounded-md border-control bg-bg text-ink shadow-sm focus:border-primary focus:ring-ring">{{ old('bio', $page->bio) }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="avatar" :value="__('Avatar')" />
                        @if ($page->avatar_path)
                            <div class="mt-2 flex items-center gap-4">
                                <img src="{{ Storage::url($page->avatar_path) }}" alt="" class="h-16 w-16 rounded-full object-cover">
                                <label class="flex items-center gap-2 text-sm text-ink">
                                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-control text-danger focus:ring-danger">
                                    {{ __('Remove avatar') }}
                                </label>
                            </div>
                        @endif
                        <input id="avatar" type="file" name="avatar" accept="image/*" class="mt-2 block w-full text-sm text-muted file:me-4 file:rounded-md file:border-0 file:bg-primary-subtle file:px-3 file:py-1.5 file:text-primary-subtle-fg">
                        <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="banner" :value="__('Banner')" />
                        @if ($page->banner_path)
                            <div class="mt-2 space-y-2">
                                <img src="{{ Storage::url($page->banner_path) }}" alt="" class="h-24 w-full rounded-lg object-cover">
                                <label class="flex items-center gap-2 text-sm text-ink">
                                    <input type="checkbox" name="remove_banner" value="1" class="rounded border-control text-danger focus:ring-danger">
                                    {{ __('Remove banner') }}
                                </label>
                            </div>
                        @endif
                        <input id="banner" type="file" name="banner" accept="image/*" class="mt-2 block w-full text-sm text-muted file:me-4 file:rounded-md file:border-0 file:bg-primary-subtle file:px-3 file:py-1.5 file:text-primary-subtle-fg">
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />
                    </div>
                </div>

                <!-- Theme -->
                <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-ink">{{ __('Accent palette') }}</h3>
                    <p class="mt-1 text-sm text-muted">{{ __('Used for your avatar ring and highlighted links.') }}</p>

                    <div class="mt-4 flex flex-wrap gap-4">
                        @foreach ($themes as $key => $theme)
                            <label class="flex cursor-pointer flex-col items-center gap-1">
                                <input type="radio" name="theme" value="{{ $key }}" class="peer sr-only"
                                       @checked(old('theme', $page->theme) === $key)>
                                <span class="h-10 w-10 rounded-full ring-2 ring-transparent ring-offset-2 peer-checked:ring-ring"
                                      style="background-image: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }})"></span>
                                <span class="text-xs text-muted">{{ $theme['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                </div>

                <!-- Background -->
                <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-ink">{{ __('Background') }}</h3>

                    <div class="mt-4 flex flex-wrap gap-6">
                        @foreach (['default' => __('Default'), 'solid' => __('Solid color'), 'gradient' => __('Gradient')] as $value => $label)
                            <label class="flex items-center gap-2 text-sm text-ink">
                                <input type="radio" name="background_type" value="{{ $value }}" x-model="backgroundType"
                                       class="border-control text-primary focus:ring-ring">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap gap-6" x-show="backgroundType !== 'default'" x-cloak>
                        <div>
                            <x-input-label for="background_start" :value="__('Color')" />
                            <input id="background_start" type="color" name="background_start"
                                   value="{{ old('background_start', $page->background_start ?? config('nexo.themes.default.from')) }}"
                                   class="mt-1 h-10 w-16 cursor-pointer rounded border border-control">
                            <x-input-error :messages="$errors->get('background_start')" class="mt-2" />
                        </div>
                        <div x-show="backgroundType === 'gradient'" x-cloak>
                            <x-input-label for="background_end" :value="__('To')" />
                            <input id="background_end" type="color" name="background_end"
                                   value="{{ old('background_end', $page->background_end ?? config('nexo.themes.default.to')) }}"
                                   class="mt-1 h-10 w-16 cursor-pointer rounded border border-control">
                            <x-input-error :messages="$errors->get('background_end')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div>
                    <x-primary-button x-bind:disabled="sending" x-bind:aria-busy="sending">{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
