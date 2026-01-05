<div>
    <div class="w-full  flex gap-6">
        <div  class="">
            <x-forms.search property="query" method="search" id="material_search"></x-forms.search>
        </div>
        <div class=" pr-0 md:pr-4 mb-4 md:mb-0">
            <select  wire:model.lazy="amount" id="" name="amount" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="75">75</option>
                <option value="100">100</option>
            </select>
        </div>
        <div  wire:loading >  <x-loader></x-loader> </div>

    </div>
    <table class="w-full relative" x-data="">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $index => $head)
                <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            @foreach($user->roles as $role)
                <tr class="group" wire:key="{{ $user->id }}">
                    <x-table.row> {{$counter}} </x-table.row>
                    <x-table.row> {{$user->name}} </x-table.row>
                    <x-table.row> {{!$user->document ? 'sin dato' : $user->document}} </x-table.row>
                    <x-table.row>  {{$role->name}}  </x-table.row>
                    <x-table.row> {{$user->email}} </x-table.row>
                    <x-table.row> {{$user->phone}} </x-table.row>
                    <x-table.row> <x-buttons.toggle status="{{ $user->status}}" slug="{{$user->slug}}" ></x-buttons.toggle> </x-table.row>
                    <x-table.row>
                        <div class="flex justify-around space-x-1">
                            <x-buttons.edit route="admin.edit.user" entity="user" slug="{{$user->slug}}"></x-buttons.edit>
                        </div>
                    </x-table.row>
                </tr>
                @php $counter++ @endphp
            @endforeach
        @endforeach

        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
        <tr class="group">

        </tr>
        </tfoot>
    </table>

    @if($users->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif
    @script
    <script>
        $wire.on('clear_input', () => {
            document.getElementById('user_search').value = ''
        });

    </script>
    @endscript

</div>
