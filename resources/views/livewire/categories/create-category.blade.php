<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends Component
{
    use WithPagination;

    public string $title = '';
    public string $slug = '';
    public string $categoryId;
    public string $text_color = '';
    public string $bg_color = '';

    public $perPage = 5;

    #[Url(history:true)]
    public $search = '';

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
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
    public function categories()
    {
        return Category::search($this->search)->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }


    public function createCategory()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:225|min:3',
            'slug' => 'required|string|max:225|min:3|unique:categories,slug',
            'text_color' => 'nullable|string',
            'bg_color' => 'nullable|string',
        ]);

        Category::create($validated);
        session()->flash('success', 'Category created successfully!!');
        $this->reset();
        $this->redirect(route('category'), navigate: true);

    }

    public function edit($categoryId)
    {

        $category = Category::findOrFail($categoryId);

        if(!$category)
        {
            session()->flash('error', 'Category does not exist.');

        } else {
            $this->dispatch('open-modal', 'edit-category');

            $this->categoryId = $category->id;
            $this->title = $category->title;
            $this->slug = $category->slug;
            $this->text_color = $category->text_color;
            $this->bg_color = $category->bg_color;
        }

    }


    public function update()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:225|min:3',
            'slug' => 'required|string|max:225|min:3|unique:categories,slug',
            'text_color' => 'nullable|string',
            'bg_color' => 'nullable|string',
        ]);


        $category = Category::findOrFail($this->categoryId);

        if(!$category){
            session()->flash('error', 'Category not found!');
        }

        $category->update($validated);
        session()->flash('success', 'Category created successfully!!');
        $this->reset();
        $this->redirect(route('category'), navigate:true);

    }

    public function deleteCategory_Confirmation($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        if(!$category){
            session()->flash('error', 'Category not found!');
        }

        $this->dispatch('open-modal', 'confirm-category-deletion');
        $this->categoryId = $category->id;
    }

    public function destroy()
    {
        Category::findOrFail($this->categoryId)->delete();
        $this->resetPage();
        $this->redirect(route('category'), navigate: true);
        session()->flash('success', 'Category deleted successfully!!');
    }
}; ?>

<div>
    <!--Create Category Button-->
    <div class="pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-end items-center p-6 text-gray-900">
                    <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-category')">
                        {{ __('Create Category') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <!--Categories Table-->
    <div class="pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-auto sm:rounded-lg">
                <div class="bg-white rounded-lg shadow-md p-4">
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
                                    class="border border-slate-600 text-gray-900 text-sm rounded-lg focus:ring-slate-600 focus:border-slate-900 block w-full pl-10 p-2 "
                                    placeholder="Search" />
                            </div>
                        </div>

                        <!-- Search type -->
                        <div class="flex space-x-3">
                            <div class="flex space-x-3 items-center">
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
                    <div class="overflow-x-auto px-4">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-white uppercase bg-slate-300">
                                <tr>
                                    <th scope="col" class="px-4 py-3" >Title</th>
                                    <th scope="col" class="px-4 py-3" >Slug</th>
                                    <th scope="col" class="px-4 py-3" >Published</th>
                                    <th scope="col" class="px-4 py-3">Last Updated</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($this->categories) > 0)
                                    @foreach ($this->categories as $category)
                                        <tr wire:key="{{ $category->id }}" class="border-b">
                                            <td class="px-4 py-3 font-medium text-slate-600 whitespace-nowrap">{{ $category->title }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $category->slug }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $category->created_at->format("d-m-Y h:i:sa") }}</td>
                                            <td class="px-4 py-3 ont-medium text-slate-600 whitespace-nowrap">{{ $category->updated_at->format("d-m-Y h:i:sa") }}</td>
                                            <td class="px-4 py-3 flex items-center justify-start space-x-2">
                                                <button wire:click="edit({{$category->id}})" class="px-3 py-1 bg-green-500 text-white rounded">Edit</button>
                                                <button wire:click="deleteCategory_Confirmation({{ $category->id }})" class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b">
                                        <td colspan="6" class="text-center px-4 py-3 font-medium text-slate-600 whitespace-nowrap">No posts found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Per page -->
                    <div class="pt-4 px-4">
                        <div class="flex ">
                            <div class="flex space-x-4 items-center mb-3">
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

                    <div class="pb-5 px-4">
                        <span>{{ $this->categories->links() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- create-category Modal -->
    <x-modal name="create-category" maxWidth="4xl" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="createCategory" class="p-6">
            <!-- Head -->
            <div>
                <h2 class="">
                    {{ __('Create Category') }}
                </h2>
            </div>


            <!-- Body -->
            <div>
                <div class="flex justify-start space-x-4 mt-2">
                    <!-- Title -->
                    <div class="w-1/2">
                        <x-input-label for="title" :value="__('Post Title')" />
                        <x-text-input wire:model="title" wire:keyup="updateTitle" id="title" class="block mt-1 w-full" type="text" name="title" required autofocus autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <!-- Slug -->
                    <div class="w-1/2">
                        <x-input-label for="slug" :value="__('Post Slug')" />
                        <x-text-input wire:model="slug" id="slug" class="block mt-1 w-full" type="text" name="slug" required autofocus autocomplete="slug" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start space-x-4 mt-2">
                    <!-- Text Color -->
                    <div class="w-1/2">
                        <x-input-label for="text_color" :value="__('Post Text Color')" />
                        <x-text-input wire:model="text_color" id="text_color" class="block mt-1 w-full" type="text" name="text_color" required autofocus autocomplete="text_color" />
                        <x-input-error :messages="$errors->get('text_color')" class="mt-1" />
                    </div>

                    <!-- Bg Color -->
                    <div class="w-1/2">
                        <x-input-label for="bg_color" :value="__('Background Color')" />
                        <x-text-input wire:model="bg_color" id="bg_color" class="block mt-1 w-full" type="text" name="bg_color" required autofocus autocomplete="bg_color" />
                        <x-input-error :messages="$errors->get('bg_color')" class="mt-1" />
                    </div>
                </div>
            </div>


            <!-- Footer -->
            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click.prevent="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>


                <x-primary-button wire:click="createCategory" wire:loading.attr="disabled">Save</x-primary-button>

                <div class="text-slate-700" wire:loading wire:target="createCategory">Saving...</div>

            </div>

        </form>
    </x-modal>


    <!-- edit-category Modal -->
    <x-modal name="edit-category" maxWidth="4xl" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="update" class="p-6">
            <!-- Head -->
            <div>
                <h2 class="">
                    {{ __('Update Category') }}
                </h2>
            </div>


            <!-- Body -->
            <div>
                <div class="flex justify-start space-x-4 mt-2">
                    <!-- Title -->
                    <div class="w-1/2">
                        <x-input-label for="title" :value="__('Post Title')" />
                        <x-text-input wire:model="title" wire:keyup="updateTitle" id="title" class="block mt-1 w-full" type="text" name="title" required autofocus autocomplete="title" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <!-- Slug -->
                    <div class="w-1/2">
                        <x-input-label for="slug" :value="__('Post Slug')" />
                        <x-text-input wire:model="slug" id="slug" class="block mt-1 w-full" type="text" name="slug" required autofocus autocomplete="slug" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-1" />
                    </div>
                </div>

                <div class="flex justify-start space-x-4 mt-2">
                    <!-- Text Color -->
                    <div class="w-1/2">
                        <x-input-label for="text_color" :value="__('Post Text Color')" />
                        <x-text-input wire:model="text_color" id="text_color" class="block mt-1 w-full" type="text" name="text_color" required autofocus autocomplete="text_color" />
                        <x-input-error :messages="$errors->get('text_color')" class="mt-1" />
                    </div>

                    <!-- Bg Color -->
                    <div class="w-1/2">
                        <x-input-label for="bg_color" :value="__('Background Color')" />
                        <x-text-input wire:model="bg_color" id="bg_color" class="block mt-1 w-full" type="text" name="bg_olor" required autofocus autocomplete="bg_color" />
                        <x-input-error :messages="$errors->get('bg_color')" class="mt-1" />
                    </div>
                </div>
            </div>


            <!-- Footer -->
            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button x-on:click.prevent="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>


                <x-primary-button wire:click="update" wire:loading.attr="disabled">Update</x-primary-button>

                <div class="text-slate-700" wire:loading wire:target="update">Updating...</div>

            </div>

        </form>
    </x-modal>


    <!-- confirm-category-deletion -->
    <x-modal name="confirm-category-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="destroy" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
               <h3>Are you sure you want to delete this category ?</h3>
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once the category is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Category') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
