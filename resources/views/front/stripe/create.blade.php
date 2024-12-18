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
                    <form id="checkout-form" method="post" action="{{ route('stripe.create.payment.intent') }}">
                        @csrf    
                        <strong>Name:</strong>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required>
                        <span class="text-danger" id="name-error"></span>
                        <input type='hidden' name='stripeToken' id='stripe-token-id'>
                        <input type='hidden' name='amount' value="{{ session('bookingData.total') * 100 }}"> 
                        <input type="hidden" name="subscription_id" value="{{ $subscriptionId }}">
                        <input type="hidden" name="price_id" value="{{ $priceId }}">
                        <input type="hidden" name="quantity" value="{{ $quantity }}">

                        <br>
                        <div id="card-element" class="form-control"></div>
                        <div id="error-message" class="text-danger"></div> 
                        <button id='pay-btn' type="button" onclick="createToken()" class="btn btn-success mt-3" style="margin-top: 20px; width: 100%; padding: 7px;">PAY ${{ session('bookingData.total') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('customejs')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    // alert(stripe);
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    // alert('test');
    console.log('stripekey', 'stripe'); 
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');
    function createToken() {
        $('#name-error').text('');
        $('#error-message').text('');
        var name = $('#name').val();
        if (!name) {
            $('#name-error').text('Please enter your name');
            return;
        }
        document.getElementById("pay-btn").disabled = true;
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                document.getElementById("pay-btn").disabled = false;
                $('#error-message').text(result.error.message);
                // console.log('test', result.error);
            } else {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>
@endsection
