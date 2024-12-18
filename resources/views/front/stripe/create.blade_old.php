@extends('front.layouts.app')

@section('content')
 <section class="inner_page_head">
         <div class="container_fuild">
            <div class="row">
               <div class="col-md-12">
                  <div class="full">
                     <h3>Payment Details</h3>
                  </div>
               </div>
            </div>
         </div>
      </section>

   <section class="why_section layout_padding">
      
       <div class="container">
           <div class="row">
               <div class="col-lg-8 offset-lg-2">
                   <div class="full">  
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif  
                        <form 
                            role="form" 
                            action="{{ route('stripe.create.payment.intent') }}" 
                            method="post" 
                            class="require-validation"
                            data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                            id="payment-form">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Name on Card</label>
                                <input type="text" name="name" id="name" class='form-control'>
                            </div>
                            <div class="form-group">
                                <label for="card_number" class="font-weight-bold">Card number</label>
                                <input type="text" name="card_number" id="card_number" class='form-control card-number'>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="cvc" class="font-weight-bold">Cvc</label>
                                    <input type="text" name="cvc" id="cvc" class='form-control card-cvc' placeholder='ex. 311'>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="card-exp-month" class="font-weight-bold">Expiration Month</label>
                                    <input type="text" name="card-exp-month" id="card-exp-month" class='form-control card-expiry-month' placeholder='MM'>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="card-exp-year" class="font-weight-bold">Expiration Year</label>
                                    <input type="text" name="card-exp-year" id="card-exp-year" class='form-control card-expiry-year' placeholder='YYYY'>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($100)</button>
                                </div>
                            </div>
                        </form>


                   </div>
               </div>
           </div>
       </div>
   </section>
@endsection
@section('customejs')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
<script type="text/javascript">
  
$(function() {
  
    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    
    var $form = $(".require-validation");
     
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('hide');
    
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
     
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
    
    });
      
    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
     
});
</script>
@endsection
