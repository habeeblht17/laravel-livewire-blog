<?php

use App\Models\Post;
use Livewire\Volt\Component;
use Livewire\Attributes\Reactive;

new class extends Component {
    #[Reactive]
    public Post $post;

    public function toggleLike()
    {
        if (auth()->guest()) {
            return $this->redirect(route('login'), true);
        }

        $user = auth()->user();

        if($user->hasLiked($this->post)){
            $user->likes()->detach($this->post->id);
            return;
        }

        $user->likes()->attach($this->post->id);
    }
};
?>

<div>
    <button wire:click="toggleLike" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6 {{ Auth::user()?->hasLiked($post) ? 'text-red-600' : 'text-purple-600' }} hover:text-purple-950">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
        </svg>
        <span class="text-gray-600 ml-2">
            {{ $post->likes()->count() }}
        </span>
    </button>
</div>
