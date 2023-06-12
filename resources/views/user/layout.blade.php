@extends('dashboard.Main.index')
@section('user-content')
<!DOCTYPE html>
<html>
<head>
    <title>User Table</title>
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <script src="//code.jquery.com/jquery-1.12.3.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/d98a6653af.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap5.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bulma.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bulma.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.dataTables.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.foundation.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.foundation.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.jqueryui.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.jqueryui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.semanticui.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.semanticui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap5.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bulma.js')}}"></script>
    <script src="{{asset('js/dataTables.bulma.min.js')}}"></script>
    <script src="{{asset('js/dataTables.dataTables.js')}}"></script>
    <script src="{{asset('js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.foundation.js')}}"></script>
    <script src="{{asset('js/dataTables.foundation.min.js')}}"></script>
    <script src="{{asset('js/dataTables.jqueryui.js')}}"></script>
    <script src="{{asset('js/dataTables.jqueryui.min.js')}}"></script>
    <script src="{{asset('js/dataTables.semanticui.js')}}"></script>
    <script src="{{asset('js/dataTables.semanticui.min.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script> --}}
</head>
<body>
<div class="container">
    @yield('content')
</div>
@endsection
</body>
</html>
