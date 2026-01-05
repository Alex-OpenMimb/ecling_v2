@extends('layouts.general_report')

@section('content')


    <div class="container ">
         <div class="relative text-blue-500 font-bold" style="left: 520px;">ORDEN DE SERVICIO</div>
        <div class="row ">
            <div class="cell " style="width: 250px;">
               <div>  <img src="{{ public_path('image/logo/logo_form.jpeg') }}" alt="Logo" class="image" style="color: white"> </div>
                <div class="font-bold text-blue-400" style="font-size: 0.6rem">EQUIPOS PARA LA INDUSTRIA ALIMENTARIA</div>
                <div class="font-bold  text-blue-400" style="font-size: 0.6rem"><span style="margin-left: 20px">Nit. 830.505632 - 4</span>  <span>  <img width="15" height="auto" src="{{ public_path('image/logo/blue-wapp.png') }}">  301 433 23 03</span> </div>
            </div>

            <div class="cell   ">
                <div>
                    <div class="d-line-block text-blue-500 font-bold " style="">FECHA:  <span class="font-normal  d-line-block  border-b text-black " style="width: 130px;padding-left: 30px">{{$date}}</span> </div>
                    <div class="d-line-block text-red-500 " style="width:200px" > <span class="" style="margin-right:10px;margin-left: 60px">No</span>  <span class="" > {{$serial_service_order}}</span> </div>
                </div>
                <div class="m-b-10" style="">
                    <div class="d-line-block text-blue-500 font-bold">CLIENTE:  <span class="font-normal d-line-block border-b text-black p-l-20 " style="width:380px">{{$client}}</span> </div>

                </div>
                <div class="m-b-10">
                    <div class="d-line-block text-blue-500 font-bold">TELÉFONO:  <span class="font-normal  d-line-block border-b text-black " style="width: 168px;padding-left: 5px"> {{$phone}} </span> </div>
                    <div class="d-line-block text-blue-500 font-bold" >NIT:  <span class="font-normal  d-line-block border-b text-black p-l-20 " style="width: 155px">{{$nit}} </span> </div>
                </div>
                <div class="m-b-10  text-blue-500 font-bold">DIRECCIÓN:  <span class="font-normal  d-line-block border-b text-black  p-l-20 " style="width: 368px;padding-left: 5px">{{ $address }} </span> </div>
                <div  class="m-b-10 text-blue-500 font-bold">SOLICITADO POR:  <span class="font-normal  d-line-block border-b text-black  " style="width: 280px">{{$request_name}}</span> </div>
            </div>

        </div>

    </div>

    <div style="margin-top: 20px">
        {{--Star: hidden elemen by request of client --}}
        <div class="d-line-block text-blue-500 font-bold" style="display: none">Hora Inicio:  <span class="font-normal d-line-block border-b text-black p-l-20" style="width:100px">{{$start_hour}}</span> </div>
        <div class="d-line-block text-blue-500 font-bold" style="display: none">Hora salida:  <span class="font-normal  d-line-block border-b text-black p-l-20" style="width:100px">{{$end_hour}}</span> </div>
        {{--End --}}
        <div class="d-line-block text-blue-500 font-bold">No Técnicos:  <span class="font-normal  d-line-block border-b text-black p-l-20" style="width:100px">{{$operator}}</span> </div>
        <div class="d-line-block text-blue-500 font-bold">Sede:  <span class="font-normal  d-line-block border-b text-black " style="width:300px">{{$headquarter_name}}</span> </div>
    </div>
    <div class="border" style="width: 100%" >
        <div class="text-8rem border-b text-blue-500">
          <p class="m-l-5 font-bold">  DESCRIPCIÓN DEL EQUIPO</p>
        </div>
        <div style="height: 40px">
            <p class="m-l-5 " style="margin-top: 2px">
               {{$client_equipment}}
            </p>
        </div>

        <div class="text-8rem border-b border-t text-blue-500">
            <p class="m-l-5 font-bold">  DESCRIPCIÓN DEL SERVICIO</p>
        </div>
        <div style="height: 140px">
            <p class="m-l-5 m-t-2" style="">
                {{ $description_service  }}
            </p>
        </div>

        <div class="text-8rem border-b border-t text-blue-500">
            <p class="m-l-5 font-bold">  DESCRIPCIÓN TÉCNICO</p>
        </div>
        <div style="height: 100px">
            <p class="m-l-5 m-t-2">
                 {{$observations}}
            </p>
        </div>

        <div class="text-8rem border-b border-t text-blue-500">
            <p class="m-l-5 font-bold"> NOTAS</p>
        </div>
        <div style="height: 100px">
            <p class="m-l-5 m-t-2">
                {{$pending_note}}
            </p>
        </div>


        <div class="container" style="">
            <div class="row text-blue-500 text-8rem">
                <div class="cell border-t border-r" style="width: 284px">
                    <p class="m-l-5  font-bold">MATRIALES UTILIZADOS</p>
                </div>
                <div class="cell border-t">
                    <p class="m-l-5 font-bold">CANT</p>
                </div>

                <div class="cell border-t border-l" style="width: 284px">
                    <p class="m-l-5 font-bold">REPUESTOS UTILIZADOS</p>
                </div>
                <div class="cell border-t border-l">
                    <p class="m-l-5 font-bold">CANT</p>
                </div>
            </div>
        </div>

        <div class="container">
         @foreach($materials_spare_parts as $element  )
                <div class="row text-blue-500 text-8rem">

                    <div class="cell border-t border-r" style="height: 17px;width: 283px">
                        <p class="m-l-5 text-black">{{$element['material']}} </p>
                    </div>

                    <div class="cell border-t" style="width: 76px">
                        <p class="m-l-5 text-black"> {{$element['cant_m']}} {{$element['unit']}} </p>
                    </div>

                    <div class="cell border-t border-l" style="width: 283px">
                        <p class="m-l-5 text-black">{{$element['spare']}} </p>
                    </div>

                    <div class="cell border-t border-l" style="width: 76px">
                        <p class="m-l-5 text-black"> {{$element['cant_s']}}</p>
                    </div>

                </div>
         @endforeach
        </div>
    </div>

    <div class="" style="margin-top: 70px">
        <div class="d-line-block text-blue-500  " style="margin-right: 50px">
            <div class="border-b m-b-10 w-200-px text-center" >{{$receptor_name}}</div>
            <div class="m-l-50 font-bold" >Nombre Cliente</div>
        </div>

        <div class="d-line-block text-blue-500  relative" style="margin-right: 50px">
            <div class="border-b m-b-10 w-200-px" > <img class="absolute"  @if( $receptor_signature ) src="{{ public_path('storage/signatures/general_report/'. $id .'/signature.png') }}" @else  @endif  height="auto" width="200" alt="firma" style="top: -50px;color: white" > </div>
            <div class="m-l-50 font-bold" >Autorizado por</div>
        </div>

        <div class="d-line-block text-blue-500  relative ">
            <div class="border-b m-b-10 w-200-px text-center" >{{$technic_name}} </div>
            <div class="m-l-50 font-bold" >Nombre del Técnico</div>
        </div>

    </div>

    <div class="" style="margin-top: 10px">
        <p class="text-center text-blue-400 p-y-2 bg-blue-300" style="">Cra. 63C No 96 A 220 Bloque 50 Int 303 - Med-Ant technicservicemedellin@gmail.com </p>
    </div>


    @if(!empty($photos))
        <div  class="page-break"></div>

        <div class="container border text-center" style="">
            <h2 class="text-blue-500" >Evidencias </h2>

        </div>

        <div class="m-t-100">
            @foreach(array_chunk($photos, 2) as $chunk)
                <div class="text-center">
                    @foreach($chunk as $index => $photo)
                        <div style="margin: 0 auto 50px; max-width: 420px;">
                            @if(!empty($photo['title']))
                                <p class="text-blue-500 font-bold" style="margin-bottom: 10px;">{{ $photo['title'] }}</p>
                            @endif
                            <p>
                                <img
                                     width="340"
                                     height="340"
                                     src="{{ public_path('storage/' . $photo['path']) }}"
                                     alt="Evidencia">
                            </p>
                        </div>
                    @endforeach
                </div>

                @if(!$loop->last)
                    <div class="page-break"></div>
                @endif
            @endforeach
        </div>

    @endif

@endsection



