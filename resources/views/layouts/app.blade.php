<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.css')}}">
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/select2-bootstrap.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <!-- Scripts -->
    <style>
        .error{
        color: #FF0000;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div id="app">
        <div class="wrapper">
          @include('layouts.header')
            @include('layouts.sidebar')
            <div class="content-wrapper">
                @yield('content')
                @yield('admimHome')
            </div>
            @include('layouts.footer')
        </div>
    </div>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    <!-- <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script> -->
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <!-- select2 js -->
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    </script>
    <!-- datatable -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script>
      @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
      @endif
      @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
      @endif
      @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
      @endif
      @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
      @endif
    </script>
    @yield('customejs')
</body>
</html>



