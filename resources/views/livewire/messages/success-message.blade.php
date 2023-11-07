<div id="alert-3"
    x-data="{ showAlert: true }"
    x-init="setTimeout(() => showAlert = false, 5000)"
    x-show="showAlert"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-60 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="flex items-center p-4 mb-4 text-green-600 rounded-lg bg-white shadow-md"
    style="position: fixed; top: 50px; right: 10px; z-index: 50; width: 500px;" role="alert">
    <div class="mr-2 text-base text-green-600 rounded-full">
        <svg width="20" height="20" viewBox="0 0 20 20" class="fill-current">
            <path d="M10 19.625C4.6875 19.625 0.40625 15.3125 0.40625 10C0.40625 4.6875 4.6875 0.40625 10 0.40625C15.3125 0.40625 19.625 4.6875 19.625 10C19.625 15.3125 15.3125 19.625 10 19.625ZM10 1.5C5.3125 1.5 1.5 5.3125 1.5 10C1.5 14.6875 5.3125 18.5312 10 18.5312C14.6875 18.5312 18.5312 14.6875 18.5312 10C18.5312 5.3125 14.6875 1.5 10 1.5Z"/>
            <path d="M8.9375 12.1875C8.71875 12.1875 8.53125 12.125 8.34375 11.9687L6.28125 9.96875C6.0625 9.75 6.0625 9.40625 6.28125 9.1875C6.5 8.96875 6.84375 8.96875 7.0625 9.1875L8.9375 11.0312L12.9375 7.15625C13.1563 6.9375 13.5 6.9375 13.7188 7.15625C13.9375 7.375 13.9375 7.71875 13.7188 7.9375L9.5625 12C9.34375 12.125 9.125 12.1875 8.9375 12.1875Z"/>
        </svg>
    </div>

    <span class="sr-only">Info</span>
    <div class="mx-3 text-sm font-medium">
        {{ session()->get('success') }}
    </div>

    <button @click="showAlert = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-green-500 rounded-full p-1 hover:bg-green-200 inline-flex items-center justify-center h-5 w-5" data-dismiss-target="#alert-3" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>

