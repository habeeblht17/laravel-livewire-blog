@props(['post'])

<div wire:key="{{ $post->id }}" class="flex-col scale-100 p-3 bg-white ring-1 ring-inset ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
    <div>
        <a href="#">
            <img src="{{ $post->getThumbnailUrl() }}" alt="" style="height: 270px;" class="w-full rounded-xl">
        </a>
    </div>
    <div class="inline-flex space-x-2 mt-1">
        <div class="h-10 w-10 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <span class="mt-2">{{ $post->published_at->format('d-m-Y') }}</span>
    </div>
    <a href="#" class="mt-3 text-xl font-semibold text-gray-900">
        <h2>{{ $post->title }}</h2>
    </a>
    <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
        {{ $post->getExcerpt() }}
    </p>

</div>
