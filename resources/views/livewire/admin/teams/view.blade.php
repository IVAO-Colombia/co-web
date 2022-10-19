<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
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

            @if ($modal)
                @include('livewire.admin.teams.members')
            @endif
            <div class="overflow-x-auto w-full">
                <table class="table-auto w-full my-2 max-w-full">
                    <thead>
                        <tr class="bg-indigo-600 text-white">
                            <td class="px-4 py-2 cursor-pointer">
                                ID
                            </td>

                            <td class="px-4 py-2 cursor-pointer">
                                Name
                            </td>
                            <td class="px-4 py-2">
                                Actions
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $item)
                            <tr>
                                <td class="border px-4 py-2" NOWRAP>{{ $item->id }}</td>
                                <td class="border px-4 py-2">{{ $item->name }}</td>
                                <td class="border px-2 py-2 text-center w-40">
                                    <button wire:click="show_members({{ $item->id }})" class="btn btn-blue">
                                        <i class="fas fa-users"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $teams->links() }}
        </div>
