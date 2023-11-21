<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Url]
    public $sort = 'DESC';

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';


    public function setSort($sort)
    {
        $this->sort = ($sort === 'DESC') ? 'DESC' : 'ASC';
    }

    #[On('search')]
    public function updateSearch($search)
    {
        $this->search = $search;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->resetPage();
    }

    #[Computed()]
    public function posts()
    {
        return Post::published()->orderBy('published_at', $this->sort)
        ->when($this->activeCategory, function ($query) {
            $query->withCategory($this->category);
        })->where('title', 'like', "%{$this->search}%")
        ->paginate(10);
    }

    #[Computed()]
    public function activeCategory()
    {
        return Category::where('slug', $this->category)->first();
    }

    #[Computed()]
    public function categories()
    {
        return Category::whereHas('posts', function ($query) {
                $query->published();
            })
            ->take(9)->get();
    }
};
?>

<div>
    <!-- Post List -->
    <div class="w-full grid grid-cols-4 gap-10">
        <div class="md:col-span-3 col-span-4">
            <div class=" px-3 lg:px-7 py-6">
                <div class="flex justify-between items-center border-b border-gray-100">
                    <div>
                        @if ($this->activeCategory || $search)
                            <button wire:click="clearFilters" class="text-purple-950 mr-2 text-base">x</button>
                        @endif
                        @if ($this->activeCategory)
                            <x-badge wire:navigate href="{{ route('blog', ['category' => $this->activeCategory->slug]) }}"  :textColor="$this->activeCategory->text_color" :bgColor="$this->activeCategory->bg_color">
                                {{ $this->activeCategory->title }}
                            </x-badge>
                        @endif
                        @if ($search)
                            <span>containing: {{ $search }}</span>
                        @endif
                    </div>
                    <div id="filter-selector" class="flex items-center space-x-4 font-light ">
                        <button wire:click="setSort('DESC')" class="{{ $sort === 'DESC' ? 'text-purple-950 py-4 border-b-2 border-purple-950' : 'text-purple-600'}} py-4">Latest</button>
                        <button wire:click="setSort('ASC')" class="{{ $sort === 'ASC' ? 'text-purple-950 py-4 border-b-2 border-purple-950' : 'text-purple-600'}} py-4">Oldest</button>
                    </div>
                </div>
                <div class="py-4">
                    @foreach ($this->posts as $post)
                        <x-posts.postList-card :post="$post" />
                    @endforeach

                    <div class="mt-5">
                        <span>{{ $this->posts->onEachSide(1)->links() }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="side-bar" class="border-t border-t-gray-100 md:border-t-none col-span-4 md:col-span-1 px-2 md:px-4  space-y-10 py-6 pt-10 md:border-l border-gray-100 h-screen sticky top-0">
            <livewire:search-box />

            <div id="recommended-topics-box">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Recommended Topics</h3>
                <div class="topics flex flex-wrap justify-start gap-2">
                    @foreach ($this->categories as $category)
                        <x-badge wire:navigate href="{{ route('blog', ['category' => $category->slug]) }}"  :textColor="$category->text_color" :bgColor="$category->bg_color">
                            {{ $category->title }}
                        </x-badge>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
