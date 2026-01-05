@props(['content',])
<div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 ">
    <button wire:loading.class="opacity-50" type="submit" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">{{$content}}</button>
</div>
