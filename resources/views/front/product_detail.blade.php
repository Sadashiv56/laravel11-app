@extends('front.layouts.app')
@section('content')
@include('front.layouts.slider')
<main>
    <section class="product_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    <span>{{ $product->title }}</span>
                </h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="img-box">
                        <img class="img-fluid" src="{{ asset('products_photo/' . $product->image) }}" alt="{{ $product->title }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <h5>Product name:{{ $product->title }}</h5>
                        <h6>Price: ${{ $product->price }}</h6>
                        <p>{{ $product->short_description }}</p>
                        <a href="{{ route('front.show_teachers') }}" id="book-now-btn" class="btn btn-primary">Book Now</a>
                        <input type="hidden" id="product-id" value="{{ $product->id }}">
                    </div>
                </div>
            </div>

            <!-- Tabs for Description and Specification -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="specification-tab" data-toggle="tab" href="#specification" role="tab" aria-controls="specification" aria-selected="false">Specification</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <p>{{ $product->description }}</p>
                        </div>
                        <div class="tab-pane fade" id="specification" role="tabpanel" aria-labelledby="specification-tab">
                            @if($product->specifications)
                                @php
                                    $specs = json_decode($product->specifications, true);
                                @endphp
                                @if(is_array($specs))
                                    <ul>
                                        @foreach($specs as $spec)
                                            <li>
                                                <strong>{{ $spec['title'] ?? 'No Title' }}:</strong> {{ $spec['value'] ?? 'No Value' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No specifications available.</p>
                                @endif
                            @else
                                <p>No specifications available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
@endsection
@section('customejs')
<script>
$(document).ready(function() {
    $('#book-now-btn').on('click', function(e) {
        // console.log('test');
        e.preventDefault(); 

        var productId = $('#product-id').val(); 
         console.log('prodcutid', productId); 
        $.ajax({
            url: '{{ route('front.book_now') }}', 
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', 
                product_id: productId
            },
             success: function(response) {
                console.log('AJAX Response:', response);
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
});
</script>
@endsection
