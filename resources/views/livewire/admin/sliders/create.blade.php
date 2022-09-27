<div>
    <x-jet-modal wire:model='modal' id="slider">
        <form>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mb-4">
                    <h1 class="text-gray-900 text-2xl font-bold ">
                        @if ($editing)
                            Updating
                        @else
                            Creating
                        @endif Slider
                    </h1>
                </div>

                <div class="mb-4">
                    <label for="order" class="block text-gray-700 text-sm font-bold mb-2">Order:</label>
                    <input type="number" min="0" step="1"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="order" wire:model.defer="order">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Position Number. Ex: 1
                    </p>
                    @error('order')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title" wire:model.defer="title">
                    @error('title')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4" wire:ignore wire:key='description'>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="description" wire:model.defer='description'></textarea>

                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300"
                        for="file_input">Image:</label>
                    <input
                        class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="file_input_help" id="file_input" type="file" wire:model='imagename'>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or
                        GIF (Size. 1920x1080px).</p>
                    @error('imagename')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                    <div wire:loading wire:target="imagename" class=""><img
                            src="{{ asset('img/Spinner-1s-200px.svg') }}" alt=""></div>

                    @if ($imagename)
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300"
                                for="file_input">Preview:</label>
                            <img src="{{ $imagename->temporaryUrl() }}">
                        </div>
                    @endif
                </div>
                @if ($image)
                    <div class="mb-4">
                        <img src="{{ asset('/storage/sliders/' . $image) }}">
                    </div>
                @endif

                <hr class="mb-4">



                <div class="mb-4">
                    <label for="btn_text" class="block text-gray-700 text-sm font-bold mb-2">Button Text:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="btn_text" wire:model.defer="btn_text">
                </div>
                <div class="mb-4">
                    <label for="btn_url" class="block text-gray-700 text-sm font-bold mb-2">Button Url:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="btn_url" wire:model.defer="btn_url">
                </div>

                <div class="mb-4">
                    <span
                        class="block text-gray-700 text-sm font-bold mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Status:</span>
                    <label for="status" class="inline-flex relative items-center cursor-pointer">
                        <input type="checkbox" id="status" class="sr-only peer" wire:model.defer="status">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>

                    </label>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" wire:loading.attr='disabled'
                            wire:target='store'
                            class="disabled:opacity-25  inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span>

                    <span class=" mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModal()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>

            </div>
        </form>
    </x-jet-modal>
</div>
