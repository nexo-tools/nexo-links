<x-app-layout :title="__('Analytics')">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Analytics') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Range filter -->
            <div class="flex gap-2">
                @foreach ($ranges as $range)
                    <a href="{{ route('analytics', ['range' => $range]) }}"
                       @class([
                           'rounded-full px-3 py-1 text-sm transition',
                           'bg-primary text-primary-fg' => $days === $range,
                           'bg-surface text-muted hover:bg-bg-subtle shadow-sm' => $days !== $range,
                       ])>
                        {{ __(':days days', ['days' => $range]) }}
                    </a>
                @endforeach
            </div>

            <!-- Stat tiles -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-muted">{{ __('Total clicks') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums">{{ number_format($totalClicks) }}</p>
                </div>
                <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-muted">{{ __('Unique visitors') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums">{{ number_format($uniqueVisitors) }}</p>
                </div>
            </div>

            <!-- Clicks per day -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-ink">{{ __('Clicks per day') }}</h3>

                {{-- role="group", not role="img": img makes the children
                     presentational, which would hide the per-bar buttons. --}}
                <div class="mt-4 flex h-32 items-end gap-0.5 border-b border-line" role="group" aria-label="{{ __('Clicks per day, last :days days', ['days' => $days]) }}">
                    @foreach ($series as $point)
                        {{-- Focusable, not hover-only: on touch and by keyboard a
                             hover tooltip is the only copy of the number. --}}
                        <button type="button"
                                class="group relative flex-1 cursor-default rounded-t focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
                                aria-label="{{ $point['day'] }} · {{ trans_choice(':count click|:count clicks', $point['total']) }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 hidden -translate-x-1/2 whitespace-nowrap rounded bg-ink px-2 py-1 text-xs text-bg group-hover:block group-focus-visible:block">
                                {{ $point['day'] }} · {{ trans_choice(':count click|:count clicks', $point['total']) }}
                            </span>
                            <span class="block w-full rounded-t bg-primary transition group-hover:bg-primary-hover motion-reduce:transition-none"
                                  @style(['height: '.($point['total'] > 0 ? max(4, round($point['total'] / $maxPerDay * 120)) : 0).'px'])></span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-1 flex justify-between text-xs text-muted">
                    <span>{{ $series->first()['day'] }}</span>
                    <span>{{ $series->last()['day'] }}</span>
                </div>
            </div>

            <!-- Per link -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <h3 class="font-medium text-ink">{{ __('Per link') }}</h3>

                @if ($links->isEmpty())
                    <p class="mt-4 text-sm text-muted">{{ __('No links yet.') }}</p>
                @else
                    <table class="mt-4 w-full text-sm">
                        <thead>
                            <tr class="text-left text-muted">
                                <th scope="col" class="pb-2 font-normal">{{ __('Link') }}</th>
                                <th scope="col" class="pb-2 font-normal text-right">{{ __('Clicks') }}</th>
                                <th scope="col" class="pb-2 font-normal text-right">{{ __('Unique') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($links as $link)
                                <tr class="border-t border-line">
                                    <td class="py-2 pr-4">
                                        <span class="text-ink">{{ $link->title }}</span>
                                        @unless ($link->is_visible)
                                            <span class="ms-1 rounded bg-bg-subtle px-1.5 py-0.5 text-xs text-muted">{{ __('Hidden') }}</span>
                                        @endunless
                                    </td>
                                    <td class="py-2 text-right tabular-nums">{{ number_format($perLink[$link->id]->total ?? 0) }}</td>
                                    <td class="py-2 text-right tabular-nums">{{ number_format($perLink[$link->id]->uniques ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Top referrers -->
            <div class="bg-surface shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-ink">{{ __('Top referrers') }}</h3>

                @if ($referrers->isEmpty())
                    <p class="mt-4 text-sm text-muted">{{ __('No external referrers yet — clicks from your page or shared links without a referrer count as direct.') }}</p>
                @else
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($referrers as $referrer)
                            <li class="flex justify-between">
                                <span class="text-ink">{{ $referrer->referrer_host }}</span>
                                <span class="tabular-nums text-muted">{{ number_format($referrer->total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
