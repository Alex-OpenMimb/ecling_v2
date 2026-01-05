    <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="{{asset('storage/image/logo/logo.jpeg')}}" type="image/x-icon">
    <style>
        .absolute{
            position: absolute;
        }
        .bg-blue-300{
            background-color: #8bc1fa;
        }
        .border{
            border: solid 1px #2B6CB0;
        }
        .border-l{
            border-left: solid 1px #2B6CB0;
        }
        .border-r{
            border-right: solid 1px #2B6CB0;
        }
        .border-b{
            border-bottom: solid 1px #2B6CB0;
        }
        .border-t{
            border-top: solid 1px #2B6CB0;
        }

        .border-b-blue{
            border-bottom: solid 1px #2B6CB0;
        }

        .bg-gray-500{
            background-color: rgb(211, 211, 211);
        }
        .bg-gray-700{
            background-color: rgb(240, 240, 240);
        }
        .cell {
            display: table-cell;
            vertical-align: middle;
        }

        .d-line-block{
            display: inline-block;
        }
        .font-bold{
            font-weight: bold;
        }

        .font-normal{
            font-weight: normal;
        }
        .h-30{
            height: 30px;
        }
        .h-80{
            height: 80px;
        }
        .m-t-2{
            margin-top: 2px;
        }
        .m-l-5{
            margin-left: 5px;
        }
        .m-l-50{
            margin-left: 50px;
        }
        .m-r-10{
            margin-right: 10px;
        }
        .m-y-10{
            margin: 10px 0;
        }
        .m-b-10{
            margin-bottom: 10px;
        }
        .p-y-2{
            padding: 2px 0;
        }
        .p-l-5{
            padding-left: 5px;
        }
        .p-l-20{
            padding-left: 20px;
        }

        .relative{
            position: relative;
        }


        .text-base{
            font-size: 0.5rem;
        }
        .text-8rem{
            font-size: 0.8rem;
        }

       .text-9rem{
           font-size: 0.9rem;
        }

        .text-center{
            text-align: center;
        }
        .text-blue-500{
            color: #2B6CB0;
        }
        .text-blue-400{
            color: #3461AC
        }
        .text-black{
            color: black;
        }
        .text-red-500{
            color: #ff4d4d;
        }

        .container {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .d-table{
            display: table;
        }
        .image {
            width: 200px;
            height: auto;
        }
        .p-y-3{
            padding: 3px 3px;
        }

        .row {
            display: table-row;
        }

        .w-20 {
            width: 20%;
        }
        .w-50-px {
            width: 50px;
        }
        .w-60-px {
            width: 60px;
        }
        .w-60 {
            width: 60%;
        }
        .w-70-px {
            width: 70px;
        }
        .w-80-px {
            width: 80px;
        }
        .w-100-px{
            width: 100px;
        }
        .w-110-px{
            width: 110px;
        }

        .w-120-px{
            width: 120px;
        }
        .w-150-px {
            width: 150px;
        }

        .w-200-px {
            width: 200px;
        }
        .z-0{
            z-index: 0;
        }
        .z-10{
            z-index: 10;
        }
        .page-break {
            page-break-after: avoid;
        }
        .white-space{
            white-space: nowrap;
        }


        h1,p {
            margin: 0;
        }


    </style>
</head>
<body class=" relative text-9rem" >

@yield('content')

</body>
</html>

