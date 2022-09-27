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
                        @endif Airport
                    </h1>
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

                <div class="mb-4" wire:ignore wire:key='name'>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">NAME:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" wire:model.defer="name">
                    @error('name')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='country'>
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">COUNTRY:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="country" wire:model.defer="country">
                    @error('country')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='municipality'>
                    <label for="municipality" class="block text-gray-700 text-sm font-bold mb-2">MUNICIPALITY:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="municipality" wire:model.defer="municipality">
                    @error('municipality')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='latitude'>
                    <label for="latitude" class="block text-gray-700 text-sm font-bold mb-2">LATITUDE:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="latitude" wire:model.defer="latitude">
                    @error('latitude')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='longitude'>
                    <label for="longitude" class="block text-gray-700 text-sm font-bold mb-2">LONGITUDE:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="longitude" wire:model.defer="longitude">
                    @error('longitude')
                        <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>



                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span>

                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModal()" type="button" wire:loading.attr='disabled'
                            wire:target='store'
                            class="disabled:opacity-25  inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>

            </div>
        </form>
    </x-jet-modal>
</div>
