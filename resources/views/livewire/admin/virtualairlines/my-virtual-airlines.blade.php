<div class="px-10">
    <div>
        <h1 class="font-bold text-2xl my-3">{{ __('My Virtual Airlines') }}</h1>
    </div>

    <div class="grid md:grid-cols-3 sm:grid-cols-1 gap-4">
        @foreach ($virtualines as $item)
            <div class="">
                <a href="#"
                    class="block p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $item->name }}
                    </h5>
                    <img src="{{ asset('storage/virtualairlines/' . $item->imagen) }}" />

                </a>
            </div>
        @endforeach


    </div>


</div>
