@extends('front.layouts.app')
@section('content')
<section class="inner_page_head">
    <div class="container_fuild">
        <div class="row">
            <div class="col-md-12">
                <h3>Subscribe to Our Service</h3>
            </div>
        </div>
    </div>
</section>
<section class="why_section layout_padding">
    <div class="container">
        <form id="checkout-form" method="post" action="{{ route('subscriptions.create') }}">
            @csrf    
            <strong>Email:</strong>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" required>
            <span class="text-danger" id="email-error"></span>
            <input type='hidden' name='stripeToken' id='stripe-token-id'>
           
            <br>
            <div id="card-element" class="form-control"></div>
            <div id="error-message" class="text-danger"></div> 
            <button id='pay-btn' type="button" onclick="createToken()" class="btn btn-success mt-3" style="width: 100%; padding: 7px;">Subscribe</button>
        </form>
    </div>
</section>
@endsection
@section('customejs')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    function createToken() {
        $('#error-message').text('');
        var email = $('#email').val();
        if (!email) {
            $('#email-error').text('Please enter your email');
            return;
        }

        document.getElementById("pay-btn").disabled = true;
        stripe.createToken(cardElement).then(function(result) {
            if (result.error) {
                document.getElementById("pay-btn").disabled = false;
                $('#error-message').text(result.error.message);
            } else {
                document.getElementById("stripe-token-id").value = result.token.id;
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>
@endsection
