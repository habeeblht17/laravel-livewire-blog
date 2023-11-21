<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination, WithFileUploads;

    public $title = '';
    public $slug = '';
    public $content = '';
    public bool $featured = false;
    public $image;
    public $published_at;
    public $postId;

    public $category = [];

    public $perPage = 5;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'published_at';

    #[Url]
    public $sortDir = 'DESC';


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updateTitle()
    {
        $this->slug = Str::slug($this->title);
    }

    #[Computed()]
    public function posts()
    {
        return Post::search($this->search)->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    #[Computed()]
    public function categories()
    {
        return Category::all();
    }

    public function createPost()
    {
        $this->reset();

        $this->validate([
            'title' => 'required|string|max:225|min:3',
            'slug' => 'required|string|max:225|min:3|unique:categories,slug',
            'content' => 'required|string|min:10',
            'image' => 'required|image|max:1024',
            'featured' => 'boolean',
            'published_at' => 'nullable',
        ]);

        if($this->image){
           $postImage = $this->image->store('uploads\images', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $postImage,
            'featured' => $this->featured,
            'published_at' => $this->published_at,
        ]);

        $post->categories()->syncWithoutDetaching($this->category);

        $this->dispatch('close');
        $this->redirect(route('post'), navigate: true);
        session()->flash('success', 'Post created successfully!!');
        $this->reset();


    }

    public function edit($postId)
    {

        $post = Post::findOrFail($postId);

        if(!$post)
        {
            session()->flash('error', 'Post does not exist.');

        } else {
            $this->dispatch('open-modal', 'edit-post');

            $this->postId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->content = $post->content;
            $this->published_at = $post->published_at->format("d-m-Y h:i:sa");
            $this->featured = $post->featured;
            $this->image = Storage::disk('public')->url($post->image);
        }

    }

    public function update()
    {
        $this->validate([
            'title' => 'string|max:225|min:3',
            'slug' => 'string|max:225|min:3|unique:categories,slug',
            'content' => 'string|min:10',
            'image' => 'nullable|image|max:1024',
            'featured' => 'boolean',
            'published_at' => 'nullable',
        ]);

        $post = Post::findOrFail($this->postId);

        if(!$post){
            session()->flash('error', 'Post not found!');
        }

        if( !$this->image){
            $photo = $post->image;

        } else {

            //Delete Old image
            Storage::disk('public')->delete('uploads/images/'.$post->image);

            //store new image
            $photo = $this->image->store('uploads\images', 'public');
        }

        $post->update([
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'image' => $photo,
            'featured' => $this->featured,
            'published_at' => $this->published_at,
        ]);

        $post->categories()->syncWithoutDetaching($this->category);

        $this->dispatch('close');
        $this->redirect(route('post'), navigate: true);
        session()->flash('success', 'Post updated successfully!!');
        $this->reset();


    }

    public function deletePost_Confirmation($postId)
    {
        $post = Post::findOrFail($postId);

        if(!$post){
            session()->flash('error', 'Post not found!');
        }

        $this->dispatch('open-modal', 'confirm-post-deletion');
        $this->postId = $post->id;
    }

    public function destroy()
    {
        $post = Post::findOrFail($this->postId);

        Storage::disk('public')->delete('uploads/images'.$post->image);
        $post->delete();

        $this->dispatch('close');
        $this->redirect(route('post'), navigate: true);
        session()->flash('success', 'Post deleted successfully!!');
        $this->reset();
        $this->resetPage();
    }
};
?>

<div>
    <!--Create post Button-->
    <div class="pt-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-end p-6 text-gray-900">
                    <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-post')">
                        {{ __('Create Post') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <!--Posts Table-->
    <div class="pt-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-auto sm:rounded-lg">
                <div class="p-4 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4">
                        <!-- Search -->
                        <div class="flex">
                            <div class="relative w-full">
                                <!-- Search icon -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-slate-600"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <!-- Search input -->
                                <input  type="text" wire:model.live="search"
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border rounded-lg border-slate-600 focus:ring-slate-600 focus:border-slate-900 "
                                    placeholder="Search" />
                            </div>
                        </div>

                        <!-- Search type -->
                        <div class="flex space-x-3">
                            <div class="flex items-center space-x-3">
                                <label class="w-40 text-sm font-medium text-slate-900">Type :</label>
                                <select
                                    class="border border-slate-600 text-slate-900 text-sm rounded-lg focus:ring-slate-600 focus:border-slate-900 block w-full p-2.5 ">
                                    <option value="">All</option>
                                    <option value="0">category</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="px-4 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-white uppercase bg-slate-300">
                                <tr>
                                    <th scope="col" class="px-4 py-3" >Title</th>
                                    <th scope="col" class="px-4 py-3" >Slug</th>
                                    <th scope="col" class="px-4 py-3" >Content</th>
                                    <th scope="col" class="px-4 py-3" >Featured</th>
                                    <th scope="col" class="px-4 py-3" >Published</th>
                                    <th scope="col" class="px-4 py-3">Last Updated</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($this->posts) > 0)
                                    @foreach ($this->posts as $post)
                                        <tr wire:key="{{ $post->id }}" class="border-b">
                                            <td class="px-4 py-3 font-medium text-slate-600 whitespace-nowrap">{{ $post->title }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $post->slug }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $post->getExcerpt2() }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $post->featured }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $post->published_at->format("d-m-Y h:i:sa") }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $post->updated_at->format("d-m-Y h:i:sa") }}</td>
                                            <td class="flex items-center justify-start px-4 py-3 space-x-2">
                                                <button wire:click="edit({{$post->id}})" class="px-3 py-1 text-white bg-green-500 rounded">Edit</button>
                                                <button wire:click="deletePost_Confirmation({{ $post->id }})" class="px-3 py-1 text-white bg-red-500 rounded">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b">
                                        <td colspan="7" class="px-4 py-3 font-medium text-center text-slate-600 whitespace-nowrap">No posts found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Per page -->
                    <div class="px-4 pt-4">
                        <div class="flex ">
                            <div class="flex items-center mb-3 space-x-4">
                                <label class="w-32 text-sm font-medium text-slate-900">Per Page</label>
                                <select wire:model.live="perPage"
                                    class="border border-slate-600 text-slat-500 text-sm rounded-lg focus:ring-slate-600 focus:border-slate-600 block w-full p-2.5 ">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--End Per page -->

                    <div class="px-4 pb-5">
                        <span>{{ $this->posts->onEachSide(1)->links() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- create-post Modal -->
    <x-modal name="create-post" maxWidth="4xl" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="createPost">
            <!-- Head -->
            <div class="p-5 shadow-sm bg-gray-50">
                <h2 class="">
                    {{ __('Create Post') }}
                </h2>
            </div>
            <!-- End Head -->

            <!-- Body -->
            <div class="p-5 ">
                <div class="flex justify-start mt-4 space-x-4">
                    <!-- Title -->
                    <div class="w-1/2">
                        <x-input-label for="title" :value="__('Post Title')" />
                        <x-text-input wire:model="title" wire:keyup="updateTitle" id="title" class="block w-full mt-1" type="text" name="title" required autofocus autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <!-- Slug -->
                    <div class="w-1/2">
                        <x-input-label for="slug" :value="__('Post Slug')" />
                        <x-text-input wire:model="slug" id="slug" class="block w-full mt-1" type="text" name="slug" required autofocus autocomplete="slug" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start mt-4 space-x-4">
                    <!-- category -->
                    <div class="w-1/2">
                        <x-input-label for="category" :value="__('Post Category')" />
                        <select multiple wire:model="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option disabled>Select Category...</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-1" />
                    </div>

                    <!-- Published_at -->
                    <div class="w-1/2">
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input wire:model="published_at" id="published_at" class="block w-full mt-1" type="datetime-local" name="published_at" required autofocus autocomplete="published_at" />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start mt-4 space-x-4">
                    <!-- Image -->
                    <div class="w-1/2 mt-4">
                        <x-input-label for="image" :value="__('Post Image')" />
                        <input wire:model="image" id="" class="block w-full p-2 mt-1 border-2 border-purple-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="file" required />
                        <x-input-error :messages="$errors->get('image')" class="mt-1" />
                    </div>

                    {{-- <div class="w-1/2 mt-4">
                        @if ($image)
                            <h3>New Image</h3>
                            <img src="{{ $image->temporaryUrl() }}" class="h-30 w-40 rounded-lg mt-1">
                        @endif
                    </div> --}}
                </div>


                <!-- Content -->
                <div class="mt-4">
                    <x-input-label for="content" :value="__('Post Content')" />
                    <x-textarea wire:model="content" id="content" class="block w-full mt-1" type="text" name="content" required autofocus autocomplete="content" />
                    <x-input-error :messages="$errors->get('content')" class="mt-1" />
                </div>

                <!-- Featured -->
                <div class="mt-4">
                    <div class="flex space-x-2">
                        <x-text-input wire:model="featured" id="featured" class="block" type="checkbox" name="featured" required autofocus autocomplete="featured" />
                        <x-input-label for="featured" :value="__('Featured Post')" />
                    </div>
                    <x-input-error :messages="$errors->get('featured')" class="mt-1" />
                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 p-5 mt-4 bg-gray-50">
                <x-secondary-button x-on:click.prevent="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button wire:click="createPost" wire:loading.remove wire:loading.attr="disabled">Save</x-primary-button>

                <div class="text-slate-700" wire:loading wire:target="createPost">
                    <svg aria-hidden="true" class="w-6 h-6 mr-2 text-gray-200 animate-spin fill-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>

            </div>
        </form>
    </x-modal>

    <!-- edit-post Modal -->
    <x-modal name="edit-post" maxWidth="4xl" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="update" class="">
            <!-- Head -->
            <div class="p-5 shadow-sm bg-gray-50">
                <h2 class="">
                    {{ __('Update Post') }}
                </h2>
            </div>
            <!-- End Head -->

            <!-- Body -->
            <div class="p-5">
                <div class="flex justify-start mt-4 space-x-4">
                    <!-- Title -->
                    <div class="w-1/2">
                        <x-input-label for="title" :value="__('Post Title')" />
                        <x-text-input wire:model="title" wire:keyup="updateTitle" id="title" class="block w-full mt-1" type="text" name="title" required autofocus autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <!-- Slug -->
                    <div class="w-1/2">
                        <x-input-label for="slug" :value="__('Post Slug')" />
                        <x-text-input wire:model="slug" id="slug" class="block w-full mt-1" type="text" name="slug" required autofocus autocomplete="slug" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start mt-4 space-x-4">
                    <!-- category -->
                    <div class="w-1/2">
                        <x-input-label for="category" :value="__('Post Category')" />
                        <select multiple wire:model="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option disabled>Select Category...</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category')" class="mt-1" />
                    </div>

                    <!-- Published_at -->
                    <div class="w-1/2">
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input wire:model="published_at" id="published_at" class="block w-full mt-1" type="datetime-local" name="published_at" required autofocus autocomplete="published_at" />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start mt-4 space-x-4">
                    <!-- Image -->
                    <div class="w-1/2 mt-4">
                        <x-input-label for="image" :value="__('Post Image')" />
                        <input wire:model="image" id="" class="block w-full p-2 mt-1 border-2 border-purple-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="file" required />
                        <x-input-error :messages="$errors->get('image')" class="mt-1" />
                    </div>

                </div>

                <!-- Content -->
                <div class="mt-4">
                    <x-input-label for="content" :value="__('Post Content')" />
                    <x-textarea wire:model="content" id="content" class="block w-full mt-1" type="text" name="content" required autofocus autocomplete="content" />
                    <x-input-error :messages="$errors->get('content')" class="mt-1" />
                </div>

                <!-- Featured -->
                <div class="mt-4">
                    <div class="flex space-x-2">
                        <x-text-input wire:model="featured" id="featured" class="block" type="checkbox" name="featured" required autofocus autocomplete="featured" />
                        <x-input-label for="featured" :value="__('Featured Post')" />
                    </div>
                    <x-input-error :messages="$errors->get('featured')" class="mt-1" />
                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 p-5 mt-5 bg-gray-50">
                <x-secondary-button x-on:click.prevent="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button wire:click="update" wire:loading.remove wire:loading.attr="disabled">Update</x-primary-button>

                <div class="text-slate-700" wire:loading wire:target="update">
                    <svg aria-hidden="true" class="w-6 h-6 mr-2 text-gray-200 animate-spin fill-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
            </div>
        </form>
    </x-modal>

    <!-- confirm-post-deletion -->
    <x-modal name="confirm-post-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="destroy" class="">
            <!-- Head -->
            <div class="p-5 shadow-sm bg-gray-50">
                <h2>
                    {{ __('Delete Confirmation') }}
                </h2>
            </div>

            <!-- Body -->
            <div class="p-5">
                <h2 class="text-lg font-medium text-gray-900">
                    <h3>Are you sure you want to delete this Post?</h3>
                 </h2>

                 <p class="mt-1 text-sm text-gray-600">
                     {{ __('Once the post is deleted, all of its resources and data will be permanently deleted.') }}
                 </p>
            </div>

            <!-- footer -->
            <div class="flex justify-end gap-3 p-5 mt-5 shadow-sm bg-gray-50">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button wire:click="destroy" wire:loading.remove wire:loading.attr="disabled" class="">
                    {{ __('Delete Post') }}
                </x-danger-button>

                <div class="text-slate-700" wire:loading wire:target="destroy">
                    <svg aria-hidden="true" class="w-6 h-6 mr-2 text-gray-200 animate-spin fill-red-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
            </div>
        </form>
    </x-modal>
</div>
