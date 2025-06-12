@props(['name', 'options' => [], 'selected' => []])
@php
    $placeholder = 'Выбрать...';
    if ($name === 'masters') $placeholder = 'Мастер...';
    elseif ($name === 'shops') $placeholder = 'Точка...';
    $selected = array_map('strval', $selected);
@endphp
<div class="relative"
    x-data="{
        open: false,
        tempSelected: @js($selected),
        options: @js($options),
        getDisplayText() {
            if (this.tempSelected.length === 0) return @js($placeholder);
            const selectedOptions = this.options.filter(o => this.tempSelected.includes(o.id.toString()));
            if (selectedOptions.length === 0) return @js($placeholder);
            const first = selectedOptions[0];
            let display = first.name;
            if (@js($name) === 'masters') {
                const parts = display.split(' ');
                if (parts.length > 1) {
                    display = parts[0] + ' ' + (parts[1][0] ? parts[1][0] + '.' : '');
                    if (parts[2] && parts[2][0]) display += ' ' + parts[2][0] + '.';
                }
            }
            return selectedOptions.length === 1 ? display : display + ' ...';
        }
    }"
>
    <button type="button" class="input-header flex items-center gap-2 w-[300px] pl-4 pr-3 py-2 relative" @click="open = !open">
        <span x-text="getDisplayText()"></span>
        <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" @click.outside="open = false" class="absolute z-50 mt-2 bg-white rounded-md shadow-lg p-4 w-[300px] max-h-60 overflow-y-auto" style="display: none;">
        <template x-for="option in options" :key="option.id">
            <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50">
                <input type="checkbox"
                    :value="option.id"
                    x-model="tempSelected"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span x-text="option.name" class="select-none"></span>
            </label>
        </template>
    </div>
</div> 