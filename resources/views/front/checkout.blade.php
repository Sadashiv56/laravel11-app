
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <link rel="shortcut icon" href="images/favicon.png" type="">
      <title>Famms - Fashion HTML Template</title>
      <!-- bootstrap core css -->
      <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
      <!-- font awesome style -->
      <link href="css/font-awesome.min.css" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="css/style.css" rel="stylesheet" />
      <!-- responsive style -->
      <link href="css/responsive.css" rel="stylesheet" />
      <link rel="stylesheet" href="{{asset('css/toastr.css')}}">
      <style>
        .form-control {
            text-transform: none;
        }
        #create_user {
    width: 100%; /* Set width to 100% */
    /* Additional styles */
    background: #fff;
    border: solid #ccc 1px;
    padding: 15px; /* Padding can affect the appearance */
    font-size: 14px;
    margin-bottom: 20px;
    text-transform: capitalize;
    line-height: normal;
}
     </style>
   </head>
   <body class="sub_page">
      <div class="hero_area">
         <!-- header section strats -->
         <header class="header_section">
            <div class="container">
               <nav class="navbar navbar-expand-lg custom_nav-container ">
                  <a class="navbar-brand" href="index.html"><img width="250" src="images/logo.png" alt="#" /></a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class=""> </span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                     <ul class="navbar-nav">
                        <li class="nav-item">
                           <a class="nav-link" href="{{route('front.home')}}">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown">
                           <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Pages <span class="caret"></span></a>
                           <ul class="dropdown-menu">
                              <li><a href="about.html">About</a></li>
                              <li><a href="testimonial.html">Testimonial</a></li>
                           </ul>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="{{route('front.products')}}">Products</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="blog_list.html">Blog</a>
                        </li>
                        <li class="nav-item active">
                           <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#">
                              <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                 <g>
                                    <g>
                                       <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                                          c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                                    </g>
                                 </g>
                                 <g>
                                    <g>
                                       <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                                          C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                                          c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                                          C457.728,97.71,450.56,86.958,439.296,84.91z" />
                                    </g>
                                 </g>
                                 <g>
                                    <g>
                                       <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                                          c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                                    </g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                                 <g>
                                 </g>
                              </svg>
                           </a>
                        </li>
                        <form class="form-inline">
                           <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                           <i class="fa fa-search" aria-hidden="true"></i>
                           </button>
                        </form>
                     </ul>
                  </div>
               </nav>
            </div>
         </header>
         <!-- end header section -->
      </div>
      <!-- inner page section -->
      <section class="inner_page_head">
         <div class="container_fuild">
            <div class="row">
               <div class="col-md-12">
                  <div class="full">
                     <h3>Checkout</h3>
                  </div>
               </div>
            </div>
         </div>
      </section>

   <section class="why_section layout_padding">
       <div class="container">
           <div class="row">
               <div class="col-lg-8 offset-lg-2">
                  <center><h1>Booking Summary</h1></center>
                  <p>Product Name: {{ Session::get('bookingData.product_name') }}</p>
                  <p>Teacher Name: {{ Session::get('bookingData.teacher_name') }}</p>
                  <p>date: {{ Session::get('bookingData.date') }}</p>
                 <p>Product Price: ${{ Session::get('bookingData.product_price') }}</p> 
                  <p>Booking Date and Time:</p>
                   <ul>
                     @php
                        $selectedSlots= Session::get('bookingData.selected_slots');
                     @endphp
                     @foreach($selectedSlots as $slot)
                           <li>{{$slot}}</li>  
                     @endforeach
                   </ul>
               </div>
           </div>
       </div>
       <br><br><br>
       <div class="container">
           <div class="row">
               <div class="col-lg-8 offset-lg-2">
                   <div class="full">
                       <form action="{{ route('checkout.store') }}" method="POST" id="checkout">
                           @csrf
                           <input type="hidden" name="product_id" value="{{ Session::get('bookingData.product_id') }}">
                           <input type="hidden" name="teacher_id" value="{{ Session::get('bookingData.teacher_id') }}">
                           <input type="hidden" name="date" value="{{ Session::get('bookingData.date') }}">
                           <input type="hidden" name="product_price" value="{{ Session::get('bookingData.product_price') }}">
                            @foreach($selectedSlots as $slot)
                           <input type="hidden" name="selected_slots[]" value="{{ $slot }}">
                           @endforeach
                               <div class="form-group">
                                   <label for="first_name" class="font-weight-bold">First Name</label>
                                   <input type="text" name="first_name" id="first_name" class="form-control" >
                                    @if ($errors->has('first_name'))
                                       <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                               </div>
                               <div class="form-group">
                                   <label for="last_name" class="font-weight-bold">Last Name</label>
                                   <input type="text" name="last_name" id="last_name" class="form-control" >
                                   @if ($errors->has('last_name'))
                                       <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                    @endif
                               </div>
                               <div class="form-group">
                                   <label for="email" class="font-weight-bold">Email</label>
                                   <input type="email" name="email" id="email" class="form-control" >
                                    @if ($errors->has('email'))
                                       <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                               </div>
                               <div class="form-group">
                                   <label for="phone_number" class="font-weight-bold">Phone Number</label>
                                   <input type="text" name="phone_number" id="phone_number" class="form-control" >
                                   @if ($errors->has('phone_number'))
                                       <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                    @endif
                               </div>
                               <div class="form-group">
                                   <label for="address" class="font-weight-bold">Address</label>
                                   <input type="text" name="address" id="address" class="form-control" >
                                   @if ($errors->has('address'))
                                       <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                               </div>
                              <div class="form-group">
                                  <label for="create_user" class="font-weight-bold">Create New User</label>
                                  <input type="checkbox" name="create_user" id="create_user">
                              </div>
                              <div class="form-group" id="password-field" style="display: none;">
                                  <label for="password" class="font-weight-bold">Password</label>
                                  <input type="password" name="password" id="password" class="form-control">
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                               <div class="form-group">
                                   <input type="submit" value="Book" class="btn btn-primary mt-3" />
                               </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
   </section>
<script src="{{ asset('front/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{asset('front/js/jquery.validate.min.js')}}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script>
$(document).ready(function () {
    const formValidator = $("#checkout").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "{{ route('check.email') }}",
                    type: "POST",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        _token: "{{ csrf_token() }}"
                    },
                    dataFilter: function (response) {
                        if (response === "true") {
                            return true;
                        } else {
                            return "\"This email is already in use.\""; 
                        }
                    }
                }
            },
            phone_number: {
                required: true,
                minlength: 2
            },
            address: {
                required: true,
            }
        },
        messages: {
            first_name: {
                required: "First name is required",
            },
            last_name: {
                required: "Last name is required",
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email",
                remote: "This email is already in use."
            },
            phone_number: {
                required: "Phone Number is required",
            },
            address: {
                required: "Address is required",
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass('text-danger');
            error.insertAfter(element);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });

    $('#create_user').change(function() {
        if ($(this).is(':checked')) {
            $('#password-field').show();
            formValidator.settings.rules.password = {
                required: true,
                minlength: 6
            };
            formValidator.settings.messages.password = {
                required: "Password is required",
                minlength: "Password must be at least 6 characters long"
            };
        } else {
            $('#password-field').hide();
            formValidator.settings.rules.password = {}; 
            formValidator.settings.messages.password = {}; 
        }
        formValidator.element('#password'); 
    });

    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif
});
</script>
</body>
</html>