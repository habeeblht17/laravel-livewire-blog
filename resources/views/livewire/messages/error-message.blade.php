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
    class="flex items-center p-4 mb-4 text-red-600 rounded-lg bg-white shadow-md"
    style="position: fixed; top: 50px; right: 10px; z-index: 50; width: 500px;" role="alert">
    <div class="mr-2 text-base text-red-600 rounded-full">
        <svg width="20" height="20" viewBox="0 0 20 20" class="fill-current">
            <path d="M10 0.40625C4.6875 0.40625 0.40625 4.6875 0.40625 10C0.40625 15.3125 4.6875 19.625 10 19.625C15.3125 19.625 19.625 15.3125 19.625 10C19.625 4.6875 15.3125 0.40625 10 0.40625ZM10 18.5312C5.3125 18.5312 1.5 14.6875 1.5 10C1.5 5.3125 5.3125 1.5 10 1.5C14.6875 1.5 18.5312 5.3125 18.5312 10C18.5312 14.6875 14.6875 18.5312 10 18.5312Z"/>
            <path d="M12.875 7.125C12.6563 6.90625 12.3125 6.90625 12.0938 7.125L10 9.21875L7.90625 7.125C7.6875 6.90625 7.34375 6.90625 7.125 7.125C6.90625 7.34375 6.90625 7.6875 7.125 7.90625L9.21875 10L7.125 12.0937C6.90625 12.3125 6.90625 12.6562 7.125 12.875C7.21875 12.9687 7.375 13.0312 7.5 13.0312C7.625 13.0312 7.78125 12.9687 7.875 12.875L9.96875 10.7812L12.0625 12.875C12.1563 12.9687 12.3125 13.0312 12.4375 13.0312C12.5625 13.0312 12.7188 12.9687 12.8125 12.875C13.0313 12.6562 13.0313 12.3125 12.8125 12.0937L10.7813 10L12.875 7.90625C13.0625 7.6875 13.0625 7.34375 12.875 7.125Z"/>
        </svg>
    </div>

    <span class="sr-only">Info</span>
    <div class="mx-3 text-sm font-medium">
        {{ session()->get('error') }}
    </div>

    <button @click="showAlert = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-red-500 rounded-full p-1 hover:bg-red-200 inline-flex items-center justify-center h-5 w-5" data-dismiss-target="#alert-3" aria-label="Close">
        <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
    </button>
</div>
