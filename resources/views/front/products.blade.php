@extends('front.layouts.app')

@section('content')
@include('front.layouts.slider')
<main>
    <section class="product_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    All <span>Products</span>
                </h2>
            </div>
            <div class="row">
                @foreach($product as $item)
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="box">
                            <div class="option_container">
                                <div class="options">
                                    <a href="{{ route('front.product_detail', $item->id) }}" class="option1">
                                        {{ $item->title }}
                                    </a>
                                    <a href="{{ route('front.show_teachers') }}" class="btn btn-primary book-now-btn" data-product-id="{{ $item->id }}">Buy Now</a>
                                    <input type="hidden" id="product-id" value="{{ $item->id }}">
                                </div>
                            </div>
                            <div class="img-box">
                                <img class="card-img-top" src="{{ asset('products_photo/' . $item->image) }}">
                            </div>
                            <div class="detail-box">
                                <h5>
                                    {{ $item->title }}
                                </h5>
                                <h6>
                                    ${{ $item->price }}
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
@section('customejs')
<script>
$(document).on('click', '.book-now-btn', function(e) {
    e.preventDefault(); 
    var productId = $(this).data('product-id');
    console.log('Product ID:', productId); 
    $.ajax({
        url: '{{ route('front.book_now') }}', 
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', 
            product_id: productId
        },
        success: function(response) {
            if (response.success) {
                window.location.href = response.redirect_url; 
            } else {
                alert(response.message); 
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred: ' + error);
        }
    });
});

</script>
@endsection