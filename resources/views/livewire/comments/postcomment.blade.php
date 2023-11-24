<?php

use App\Models\Post;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Withpagination;

new class extends Component {

    use WithPagination;

    public Post $post;

    #[Rule('required|string|min:3|max:200')]
    public $comment;


    public function postComment()
    {
        $this->validateOnly('comment');

        $this->post->comments()->create([
            'comment' => $this->comment,
            'user_id' => auth()->user()->id,
        ]);

        $this->reset('comment');
    }


    #[Computed()]
    public function comments()
    {
        return $this->post->comments()->latest()->paginate(5);
    }
}; ?>

<div class="mt-10 comments-box border-t border-gray-100 pt-10">
    <h2 class="text-2xl font-semibold text-gray-900 mb-5">Discussions</h2>
    @auth
       <textarea wire:model="comment"
            class="w-full rounded-lg p-4 bg-gray-50 focus:outline-none text-sm text-gray-700 border-gray-200 placeholder:text-gray-400"
            cols="30" rows="7">
        </textarea>
        <button wire:click="postComment"
            class="mt-3 inline-flex items-center justify-center h-10 px-4 font-medium tracking-wide text-white transition duration-200 bg-purple-950 rounded-lg hover:bg-purple-800 focus:shadow-outline focus:outline-none">
            Post Comment
        </button>
    @else
        <a wire:navigate class="text-purple-500 underline py-1" href="{{ route('login') }}"> Login to Post Comments</a>
    @endauth

    <div class="user-comments px-3 py-2 mt-5">
        @forelse ($this->comments as $comment)
            <div wire:key="{{ $comment->id }}" class="comment [&:not(:last-child)]:border-b border-gray-100 py-5">
                <div class="user-meta flex mb-4 text-sm items-center">
                    <img class="w-7 h-7 rounded-full mr-3" src="" alt="mn">
                    <span class="mr-1">user name</span>
                    <span class="text-gray-500">. {{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-justify text-gray-700  text-sm">
                    {{ $comment->comment }}
                </div>
            </div>
        @empty
            <div class="text-gray-500 text-center">
                <span> No Comments Posted</span>
            </div>
        @endforelse
    </div>
    <div class="mt-2">
        <span>{{ $this->comments->links() }}</span>
    </div>
</div>
