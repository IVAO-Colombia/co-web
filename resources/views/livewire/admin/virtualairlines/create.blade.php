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
                        @endif Virtual Airline
                    </h1>
                </div>

                <div class="mb-4" wire:ignore wire:key='name'>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">NAME:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" wire:model.defer="name">
                    @error('name')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="icao" class="block text-gray-700 text-sm font-bold mb-2">ICAO:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="icao" wire:model.defer="icao">

                    @error('icao')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="iata" class="block text-gray-700 text-sm font-bold mb-2">IATA:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="iata" wire:model.defer="iata">
                    @error('iata')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300"
                        for="file_input">IMAGE:</label>
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





                @if ($imagen)
                    <div class="mb-4">
                        <img src="{{ asset('/storage/virtualairlines/' . $imagen) }}">
                    </div>
                @endif

                <div class="mb-4" wire:ignore wire:key='code'>
                    <label for="code" class="block text-gray-700 text-sm font-bold mb-2">IVAO ID:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="code" wire:model.defer="code">
                    @error('code')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='description'>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">DESCRIPTION:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="description" wire:model.defer="description">
                    @error('description')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='website'>
                    <label for="website" class="block text-gray-700 text-sm font-bold mb-2">WEBSITE:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="website" wire:model.defer="website" placeholder="ex. https://virtualairlines.com">
                    @error('website')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4">
                    <span
                        class="block text-gray-700 text-sm font-bold mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">STATUS:</span>
                    <label for="status" class="inline-flex relative items-center cursor-pointer">
                        <input type="checkbox" id="status" class="sr-only peer" wire:model.defer="status">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>

                    </label>
                </div>


                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" wire:target='store'
                            wire:loading.attr='disabled'
                            class="disabled:opacity-25  inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span>

                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModal()" type="button"
                            class=" inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>

            </div>
        </form>
    </x-jet-modal>
</div>
