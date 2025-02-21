<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Virtual Airlines') }}
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

            <x-jet-button wire:click="create">New</x-jet-button>

            @if ($modal)
                @include('livewire.admin.virtualairlines.create')
            @endif
            @if ($modalinfo)
                @include('livewire.admin.virtualairlines.tracker_airline')
            @endif
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full my-2 max-w-full">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <td class="px-4 py-2 cursor-pointer">
                                ID
                            </td>
                            <td class="px-4 py-2">
                                LOGO
                            </td>
                            <td class="px-4 py-2 cursor-pointer">
                                NAME
                            </td>

                            <td class="px-4 py-2">
                                ICAO/IATA
                            </td>
                            <td class="px-4 py-2">
                                STATUS
                            </td>
                            <td class="px-4 py-2">
                                Actions
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($virtualairlines as $item)
                            <tr>
                                <td class="border px-4 py-2" NOWRAP>{{ $item->id }}</td>
                                <td class="border px-4 py-2">
                                    <img src="{{ asset('storage/virtualairlines/' . $item->imagen) }}" class="w-40">
                                </td>
                                <td class="border px-4 py-2">{{ $item->name }}</td>

                                <td class="border px-4 py-2 max-w-xs">
                                    {{ $item->icao ?? '-' }}/ {{ $item->iata ?? '-' }}
                                </td>

                                <td class="border px-4 py-2 max-w-xs ">
                                    @if ($item->status)
                                        <svg class="w-6 h-6 mx-auto text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 mx-auto text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    @endif
                                </td>
                                <td class="border px-2 py-2 text-center w-40">
                                    <button wire:click="information({{ $item }})" class="btn btn-blue"
                                        wire:key='$item->id'>
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-green">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})" class="btn btn-red">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $virtualairlines->links() }}
        </div>
    </div>
</div>
