<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Airports') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-16">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 ">

            @if (session()->has('message'))
                <div class="bg-teal-100 rounded-b text-teal-900 px-4 py-4 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <h4>{{ session('message') }}</h4>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-between">
                <div>
                    <label for=""> Search:
                        <x-jet-input wire:model="search" type="text" placeholder="Search by ICAO, IATA, Name"
                            class="w-xl">
                        </x-jet-input>
                    </label>
                </div>

                <x-jet-button wire:click="create">New</x-jet-button>
            </div>

            @if ($modal)
                @include('livewire.admin.airports.create')
            @endif
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full my-2 max-w-full">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <td class="px-4 py-2 cursor-pointer" wire:click="order('id')">
                                ID
                            </td>
                            <td class="px-4
                                py-2">
                                ICAO/IATA
                            </td>
                            <td class="px-4 py-2 cursor-pointer" wire:click="order('name')">
                                NAME
                            </td>

                            <td class="px-4 py-2 cursor-pointer" wire:click="order('country')">
                                COUNTRY
                            </td>
                            <td class="px-4 py-2">
                                MUNICIPALITY
                            </td>
                            <td class="px-4 py-2">
                                Actions
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($airports as $item)
                            <tr>
                                <td class="border px-4 py-2" NOWRAP>{{ $item->id }}</td>
                                <td class="border px-4 py-2">
                                    {{ $item->icao ?? '-' }}/{{ $item->iata ?? '-' }}
                                </td>
                                <td class="border px-4 py-2">{{ $item->name }}</td>

                                <td class="border px-4 py-2 ">
                                    {{ $item->country }}
                                </td>

                                <td class="border px-4 py-2  ">
                                    {{ $item->municipality }}

                                </td>
                                <td class="border px-2 py-2 text-center">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 mb-2">Edit</button>
                                    <button wire:click="delete({{ $item->id }})"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mb-2">Delete</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $airports->links() }}
        </div>
    </div>
</div>
