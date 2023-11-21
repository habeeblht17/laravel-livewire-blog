@props(['category'])
<div wire:key="{{ $category->id }}" class="topics flex flex-wrap justify-start gap-x-2">
    <x-badge :textColor="$category->text_color" :bgColor="$category->bg_color">
        {{ $category->title }}
    </x-badge>
</div>
