<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Documentation') }}
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


            {{-- Search input --}}
             <div class="flex justify-between">
                {{--<div class="max-w-full">
                    <label for=""> Search:
                        <x-jet-input wire:model.debounce.500ms="search" type="text"
                            placeholder="Search by Name" class="max-w-full">
                        </x-jet-input>
                    </label>
                </div> --}}

                <x-jet-button wire:click="create">New</x-jet-button>
            </div>

            @if ($modal)
                @include('livewire.admin.documentation.create')
            @endif
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full my-2 max-w-full">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <td class="px-4 py-2">
                                ID
                            </td>
                            <td class="px-4 py-2">
                                NAME
                            </td>
                            <td class="px-4 py-2">
                                DESCRIPTION
                            </td>
                            <td class="px-4 py-2">
                                CATEGORY
                            </td>
                            <td class="px-4 py-2">
                                FILE
                            </td>
                            <td class="px-4 py-2">
                                Actions
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $item)
                            <tr>
                                <td class="border px-4 py-2" NOWRAP>{{ $item->id }}</td>
                                <td class="border px-4 py-2">
                                    {{ $item->name}}
                                </td>
                                <td class="border px-4 py-2">{{ $item->description }}</td>

                                <td class="border px-4 py-2 ">
                                    {{ $item->type }}
                                </td>
                                @if($item->url != NULL)
                                    <td class="border px-4 py-2  ">
                                        <a href="{{ $item->url }}" target="_blank" ><i class="fa-solid fa-file-pdf fa-lg"></i> View Document</a>
                                     </td>
                                @endif
                                @if($item->file != NULL)
                                    <td class="border px-4 py-2  ">
                                        <a href="{{ asset('storage/documents/' . $item->file) }}" target="_blank" ><i class="fa-solid fa-file-pdf fa-lg"></i> View Document</a>
                                     </td>
                                @endif

                                <td class="border px-2 py-2 text-center w-40">
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
        </div>
    </div>
</div>
