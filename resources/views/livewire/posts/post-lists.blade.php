<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Url]
    public $category = '';

    #[Computed]
    public function posts()
    {
        return Post::latest()
        ->when(Category::where('slug', $this->category)->first(), function ($query) {
            $query->withCategory($this->category);
        })
        ->paginate(10);
    }
}; ?>

<div>
    <!-- Post List -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 lg:gap-8">
            @foreach ($this->posts as $post)
                <x-posts.post-card :post="$post" />
            @endforeach
        </div>

        <div class="mt-5">
            <span>{{ $this->posts->links() }}</span>
        </div>
    </div>
</div>
