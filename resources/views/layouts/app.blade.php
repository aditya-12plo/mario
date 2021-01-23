<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BDsystem') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

     <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('miminium/css/bootstrap.min.css') }}">

    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="{{ asset('miminium/css/plugins/font-awesome.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('miminium/css/plugins/simple-line-icons.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('miminium/css/plugins/animate.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('miminium/css/plugins/icheck/skins/flat/aero.css') }}"/>
    <link href="{{ asset('miminium/css/style.css') }}" rel="stylesheet">
    <!-- end: Css -->
</head>
<body id="mimin" class="dashboard form-signin-wrapper">
    <div class="container">
        @yield('content') 
    </div>


      <!-- start: Javascript -->
      <script src="{{ asset('miminium/js/jquery.min.js') }}"></script>
      <script src="{{ asset('miminium/js/jquery.ui.min.js') }}"></script>
      <script src="{{ asset('miminium/js/bootstrap.min.js') }}"></script>

      <script src="{{ asset('miminium/js/plugins/moment.min.js') }}"></script>
      <script src="{{ asset('miminium/js/plugins/icheck.min.js') }}"></script>

      <!-- custom -->
      <script src="{{ asset('miminium/js/main.js') }}"></script>



</body>
</html>
