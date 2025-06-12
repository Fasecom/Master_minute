@props(['name', 'options' => [], 'selected' => []])
@php
    $placeholder = 'Выбрать...';
    if ($name === 'masters') $placeholder = 'Мастер...';
    elseif ($name === 'shops') $placeholder = 'Точка...';
@endphp
<div class="relative" x-data="{ open: false, selected: @js($selected), options: @js($options) }">
    <button type="button" class="input-header flex items-center gap-2 w-[300px] pl-4 pr-3 py-2 relative" @click="open = !open">
        <span x-text="
            selected.length === 0
                ? @js($placeholder)
                : (() => {
                    let first = options.find(o => o.id == selected[0]);
                    if (!first) return @js($placeholder);
                    let display = first.name;
                    if (name === 'masters') {
                        let parts = display.split(' ');
                        if (parts.length > 1) {
                            display = parts[0] + ' ' + (parts[1][0] ? parts[1][0] + '.' : '');
                            if (parts[2] && parts[2][0]) display += ' ' + parts[2][0] + '.';
                        }
                    }
                    return selected.length === 1 ? display : display + ' ...';
                })()
        "></span>
        <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>
    <div x-show="open" @click.outside="open = false" class="absolute z-50 mt-2 bg-white rounded-md shadow-lg p-4 w-[300px] max-h-60 overflow-y-auto" style="display: block;">
        <template x-for="option in options" :key="option.id">
            <label class="flex items-center gap-2 py-1 cursor-pointer">
                <input type="checkbox" :value="option.id" x-model="selected" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span x-text="option.name"></span>
            </label>
        </template>
    </div>
    <template x-for="id in selected" :key="id">
        <input type="hidden" :name="`${name}[]`" :value="id">
    </template>
</div> 