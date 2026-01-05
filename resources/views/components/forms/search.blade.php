@props(['property','method','id'])
<form  class="flex gap-2">
    <div class="relative ">
        <input wire:model="{{$property}}" wire:keydown.debounce.300ms="{{$method}}"  type="text" id="{{$id}}" class="py-2 pr-4 pl-10 bg-gray-50 w-full outline-none border border-gray-300 rounded-md text-sm focus:border-blue-500" placeholder="Buscar...">
        <i class="ri-search-line absolute top-1/2 left-4 -translate-y-1/2 text-gray-900"></i>
    </div>
</form>
