@props(['route','content'])

<a  href="{{route($route)}}" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">
    {{$content}}
</a>
