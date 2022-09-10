<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="container mx-auto m-3 p-4">
        <table class="table-fixed">
            <thead>
                <th>
                <td>
                    ID
                </td>
                <td>
                    Title
                </td>
                </th>
            </thead>
            <tbody>

                @foreach ($events as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>


    </div>
</div>
