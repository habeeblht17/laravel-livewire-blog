<div>
    <div class="mb-10">
        <!-- Featured Post-->
        <div class="mb-16">
            <h2 class="mt-16 mb-5 text-3xl text-purple-700 font-bold">Featured Posts</h2>
            <div class="w-full">
                <div class="grid grid-cols-3 gap-10 w-full">
                    @foreach ($featuredPosts as $post)
                        <x-posts.post-card :post="$post" />
                    @endforeach
                </div>
            </div>
            <a href="http://127.0.0.1:8000/blog" class="mt-10 block text-center text-lg text-purple-700 font-semibold">
                More Posts
            </a>
        </div>
        <hr>

        <!-- Latest Post-->
        <h2 class="mt-16 mb-5 text-3xl text-purple-700 font-bold">Latest Posts</h2>
        <div class="w-full mb-5">
            <div class="grid grid-cols-3 gap-10 gap-y-32 w-full">
                @foreach ($latestPosts as $post)
                    <x-posts.post-card :post="$post" />
                @endforeach
            </div>
        </div>
        <a href="http://127.0.0.1:8000/blog" class="mt-10 block text-center text-lg text-purple-700 font-semibold">
            More Posts
        </a>
    </div>
</div>
