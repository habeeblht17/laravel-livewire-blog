<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home' ,[
            'latestPosts' => Post::published()->latest('published_at')->take(6)->get(),
            'featuredPosts' => Post::published()->featured()->latest('published_at')->take(3)->get(),
        ]);
    }
}
