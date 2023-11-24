@props(['post'])

<div wire:key="{{ $post->id }}" class="md:col-span-1 col-span-3">
    <a wire:navigate href="{{ route('posts.show', $post->slug ) }}" >
        <div>
            <img class="w-full rounded-xl" src="{{ $post->getThumbnailUrl() }}" style="height: 250px;">
        </div>
    </a>
    <div class="mt-3">
        <div class="flex items-center mb-2">
            @foreach ($post->categories as $category)
                <x-badge wire:navigate wire:key="{{ $category->id }}" href="{{ route('blog', ['category' => $category->slug]) }}" :textColor="$category->text_color" :bgColor="$category->bg_color">
                    {{ $category->title }}
                </x-badge>
            @endforeach
            <p class="text-gray-500 text-sm">{{ $post->published_at->format('d-m-Y') }}</p>
        </div>
        <a class="text-xl font-bold text-gray-900">{{ $post->title }}</a>
    </div>

</div>
