{{-- Scheduling and highlight fields shared by the create and edit link forms. --}}
@php($editing = $link ?? null)
{{-- The dashboard renders this once per link plus once for the create form, so
     every id has to be suffixed or the labels point at the first copy. --}}
@php($uid = $editing?->id ?? 'new')

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <x-input-label :for="'starts_at-'.$uid" :value="__('Starts at (optional)')" />
        <x-text-input :id="'starts_at-'.$uid" type="datetime-local" name="starts_at" class="mt-1 block w-full"
                      :value="old('starts_at', $editing?->starts_at?->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
    </div>
    <div>
        <x-input-label :for="'ends_at-'.$uid" :value="__('Ends at (optional)')" />
        <x-text-input :id="'ends_at-'.$uid" type="datetime-local" name="ends_at" class="mt-1 block w-full"
                      :value="old('ends_at', $editing?->ends_at?->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
    </div>
</div>

<div class="flex flex-wrap gap-x-6 gap-y-2">
    <label class="flex items-center gap-2 text-sm text-ink">
        <input type="hidden" name="is_highlighted" value="0">
        <input type="checkbox" name="is_highlighted" value="1"
               class="rounded border-control text-primary shadow-sm focus:ring-ring"
               @checked((bool) old('is_highlighted', $editing?->is_highlighted))>
        {{ __('Highlight this link') }}
    </label>

    <label class="flex items-center gap-2 text-sm text-ink">
        <input type="hidden" name="show_countdown" value="0">
        <input type="checkbox" name="show_countdown" value="1"
               class="rounded border-control text-primary shadow-sm focus:ring-ring"
               @checked((bool) old('show_countdown', $editing?->show_countdown))>
        {{ __('Show countdown before it starts') }}
    </label>
</div>
