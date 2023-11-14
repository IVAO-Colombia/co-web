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
                        @endif Document
                    </h1>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" wire:model.defer="name">
                    @error('name')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4" wire:ignore wire:key='description'>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="description" wire:model.defer="description">
                    @error('description')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4" wire:ignore wire:key='type'>
                    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an option</label>
                    <select id="countries" wire:model='type' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                      <option selected>Choose a category</option>
                      <option value="ATC">ATC</option>
                      <option value="PILOT">Pilot</option>
                      <option value="TRAINING ATC">Training ATC</option>
                      <option value="TRAINING PILOT">Training Pilot</option>
                      <option value="MASTER THE TOPIC">Master The Topic</option>
                      <option value="CIRCULAR">Circulares</option>
                      <option value="LOCAL REGULATIONS">Regulacion local</option>
                      <option value="OACI">OACI</option>
                      <option value="ALL">ALL</option>
                    </select>
                    @error('type')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4" wire:ignore wire:key='url'>
                    <label for="url" class="block text-gray-700 text-sm font-bold mb-2">Link File:</label>
                    <input type="url" name="url"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="url" wire:model="url">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">example: www.example.com/document<b>.pdf</b></p>
                    @error('url')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-gray-300"
                        for="file_input">File:</label>
                    <input
                        class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer dark:text-gray-400 focus:outline-none"
                        aria-describedby="file_input_help" id="file_input" type="file" wire:model='file'>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Only PDF.</p>
                    @error('file')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                    <div wire:loading wire:target="file" class=""><img
                        src="{{ asset('img/Spinner-1s-200px.svg') }}" alt="carga"></div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span>

                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModal()" type="button" wire:loading.attr='disabled' wire:target='store'
                            class="disabled:opacity-25  inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>

            </div>
        </form>
    </x-jet-modal>
</div>
