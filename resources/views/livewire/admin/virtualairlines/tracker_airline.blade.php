<div>
    <x-jet-modal wire:model='modalinfo'>
        <form>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mb-4">
                    <h1 class="text-gray-900 text-2xl font-bold ">
                        Information
                    </h1>
                </div>

                <div class="mb-4" wire:ignore wire:key='code'>
                    <table class="border-collapse table-fixed w-full text-sm">
                        <thead>
                            <tr>
                                <th>Week</th>
                                <th>Time</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-slate-800">
                            @foreach ($airline_tracker as $item)
                                <tr>
                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        @php
                                            $week_start = (new DateTime())->setISODate(date('Y'), $item->week)->format('Y-m-d');

                                            $start = \Carbon\Carbon::createFromFormat('Y-m-d', $week_start);

                                            // $start
                                            //     ->hour(0)
                                            //     ->minute(0)
                                            //     ->second(0);

                                            $end = $start->copy()->endOfWeek();
                                        @endphp
                                        {{ $start }} -
                                        {{ $end }}

                                    </td>
                                    <td
                                        class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        {{ secontsToHours($item->secondFlight, true) }}</td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>


                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" wire:target='store'
                            wire:loading.attr='disabled'
                            class="disabled:opacity-25  inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Save
                        </button>
                    </span> --}}

                    <span class="mb-2 flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeModalInfo()" type="button"
                            class=" inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            Cancel
                        </button>
                    </span>
                </div>

            </div>
        </form>
    </x-jet-modal>
</div>
