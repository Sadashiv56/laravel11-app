
<!DOCTYPE html>
<html>
   <head>
      <!-- Basic -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <!-- Site Metas -->
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <link rel="shortcut icon" href="{{ asset('front/images/favicon.png') }}" type="">
      <title>Famms - Fashion HTML Template</title>
      <!-- bootstrap core css -->
      <link rel="stylesheet" type="text/css" href="{{ asset('front/css/bootstrap.css') }}" />
      <!-- font awesome style -->
        <link href="{{ asset('front/css/font-awesome.min.css') }}" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="{{ asset('front/css/style.css') }}" rel="stylesheet" />
      <!-- responsive style -->
      <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
      <link rel="stylesheet" href="{{asset('css/toastr.css')}}">
   </head>
   <body>
      
         <!-- header section strats -->
         @include('front.layouts.header')
         <!-- end header section -->
         <!-- slider section -->
         
         <!-- end slider section -->
      <!-- arrival section -->
      <!-- end arrival section -->
      <!-- product section -->
         @yield('content')
      <!-- end product section -->
      <!-- end subscribe section -->
      <!-- end client section -->
      <!-- footer start -->
      @include('front.layouts.footer')
      <!-- footer end -->
      <!-- jQery -->
      <script src="{{ asset('front/js/jquery-3.4.1.min.js') }}"></script>
      <script src="{{asset('front/js/jquery.validate.min.js')}}"></script>
       <!-- bootstrap js -->
      <script src="{{ asset('front/js/bootstrap.js') }}"></script>
      <!-- custom js -->
      <script src="{{ asset('front/js/custom.js') }}"></script>
      <!-- popper js -->
      <script src="{{ asset('front/js/popper.min.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
      <script src="{{ asset('js/toastr.min.js') }}"></script>
      
     @yield('customejs')
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
   </body>
</html>