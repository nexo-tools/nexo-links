<x-app-layout :title="__('Reports')">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-ink leading-tight">
            {{ __('Reports') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status') === 'report-resolved')
                <p class="nexo-flash" role="status">{{ __('Report marked as resolved.') }}</p>
            @endif

            @if ($reports->isEmpty())
                <div class="bg-surface shadow-sm sm:rounded-lg p-6 text-center text-muted">
                    {{ __('No reports. All good!') }}
                </div>
            @else
                @foreach ($reports as $report)
                    <div @class(['bg-surface shadow-sm sm:rounded-lg p-6', 'opacity-60' => $report->status === 'resolved'])>
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-medium text-ink">
                                    {{ __($reasons[$report->reason] ?? $report->reason) }}
                                    @if ($report->status === 'resolved')
                                        <span class="ms-1 rounded bg-success-subtle px-1.5 py-0.5 text-xs text-success-subtle-fg">{{ __('Resolved') }}</span>
                                    @else
                                        <span class="ms-1 rounded bg-warning-subtle px-1.5 py-0.5 text-xs text-warning-subtle-fg">{{ __('Open') }}</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-sm text-muted">
                                    {{ $report->link?->title ?? __('The whole page') }}
                                    · {{ $report->created_at->diffForHumans() }}
                                </p>
                                @if ($report->details)
                                    <p class="mt-2 text-sm text-ink">{{ $report->details }}</p>
                                @endif
                            </div>

                            @if ($report->status !== 'resolved')
                                <form method="POST" action="{{ route('reports.update', $report) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-md bg-bg-subtle px-3 py-1.5 text-sm font-medium text-ink hover:bg-line">
                                        {{ __('Mark as resolved') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
