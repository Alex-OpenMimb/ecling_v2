@props(['route','id','entity'])
<a href="{{route( $route,[ $entity => $id])}}" class="p-1 text-blue-600 rounded hover:bg-green-600 hover:text-white" title="sucursales">
    <svg class="h-6 w-5  text-green-500 hover:text-white"  width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="3" y1="21" x2="21" y2="21" />  <line x1="9" y1="8" x2="10" y2="8" />  <line x1="9" y1="12" x2="10" y2="12" />  <line x1="9" y1="16" x2="10" y2="16" />  <line x1="14" y1="8" x2="15" y2="8" />  <line x1="14" y1="12" x2="15" y2="12" />  <line x1="14" y1="16" x2="15" y2="16" />  <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
</a>
