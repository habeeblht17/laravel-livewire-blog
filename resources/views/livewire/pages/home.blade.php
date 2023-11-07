<div>
    <!-- Featured Post-->
    <div class="mb-6">
        <div class="mb-2 justify-start bg-white rounded-e-full w-40 ">
            <h2 class="text-lg text-semibold p-1">Featured Post</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach ($featuredPosts as $post)
                <x-posts.post-card :post="$post" />
            @endforeach
        </div>
    </div>

    <!-- Latest Post-->
    <div>
        <div class="mb-2 justify-start bg-white rounded-e-full w-40 ">
            <h2 class="text-lg text-semibold p-1">Latest Post</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach ($latestPosts as $post)
                <x-posts.post-card :post="$post" />
            @endforeach
        </div>
    </div>

</div>
