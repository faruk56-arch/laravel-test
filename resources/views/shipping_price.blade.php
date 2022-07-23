@include('header')

<div class="container mt-5">

    <div class="row">

        <div class="col-md-12">
            <h3 class="mb-3">Shipping and product price</h3>
            <hr>

            @if(isset($total_prices['shipment']['offer'][0]))
                <div class="row mt-5">
                    <div class="col-md-6">
                        <h3>Shipping Details</h3>

                        <img src="{{$total_prices['shipment']['offer'][0]['operator']['logo']}}" alt="">

                        <p><strong>Operator : </strong>{{$total_prices['shipment']['offer'][0]['operator']['label']}}</p>
                        <p>
                            <strong>Price with tax : </strong>
                            {{$total_prices['shipment']['offer'][0]['price']['tax-inclusive']}}
                            {{$total_prices['shipment']['offer'][0]['price']['currency']}}
                        </p>

                        <h3>Shipping Characteristics</h3>
                        @foreach($total_prices['shipment']['offer'][0]['characteristics']['label'] as $item)
                            <p>{{$loop->iteration.'- '. $item}}</p>
                        @endforeach

                        {{--dd($total_prices['shipment']['offer'][0]['service']['code'])--}}
                    </div>

                    <div class="col-md-6">
                        <h3 class="mb-5">Shipping and product price</h3>

                        <p><strong>Product name : </strong>{{$prices['product_name']}}</p>
                        <p><strong>Product price with commission : </strong>{{$prices['price_pay']}} EUR</p>
                        <p><strong>Shipping price : </strong>
                            {{$total_prices['shipment']['offer'][0]['price']['tax-inclusive']}}EUR
                        </p>

                        <p><strong>Total price : </strong>
                            {{$prices['price_pay'] + $total_prices['shipment']['offer'][0]['price']['tax-inclusive']}}EUR
                        </p>
                        @php
                            $price_total = $prices['price_pay'] + $total_prices['shipment']['offer'][0]['price']['tax-inclusive'];
                        @endphp

                        <form id="payment-form" method="POST"
                              action="{{route('checkout.cart', ['locale' => app()->getLocale()])}}">
                            @csrf
                            <div class="">
                                <input type="hidden" name="product_amount" value="{{ $price_total}}">
                            </div>

                            <button class="btn btn-primary mb-5 mt-3" id="btn-payment">
                                Submit Payment
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="row mt-5">
                    <div class="col-md-6">
                        <h3>Shipping Details</h3>

                        <img src="{{$total_prices['shipment']['offer']['operator']['logo']}}" alt="">

                        <p><strong>Operator : </strong>{{$total_prices['shipment']['offer']['operator']['label']}}</p>
                        <p>
                            <strong>Price with tax : </strong>
                            {{$total_prices['shipment']['offer']['price']['tax-inclusive']}}
                            {{$total_prices['shipment']['offer']['price']['currency']}}
                        </p>

                        <h3>Shipping Characteristics</h3>
                        @foreach($total_prices['shipment']['offer']['characteristics']['label'] as $item)
                            <p>{{$loop->iteration.'- '. $item}}</p>
                        @endforeach

                    </div>

                    <div class="col-md-6">
                        <h3 class="mb-5">Shipping and product price</h3>

                        <p><strong>Product name : </strong>{{$prices['product_name']}}</p>
                        <p><strong>Product price with commission : </strong>{{$prices['price_pay']}} EUR</p>
                        <p><strong>Shipping price : </strong>
                            {{$total_prices['shipment']['offer']['price']['tax-inclusive']}}EUR
                        </p>

                        <p><strong>Total price : </strong>
                            {{$prices['price_pay'] + $total_prices['shipment']['offer']['price']['tax-inclusive']}}EUR
                        </p>
                        @php
                            $price_total = $prices['price_pay'] + $total_prices['shipment']['offer']['price']['tax-inclusive'];
                        @endphp

                        <form id="payment-form" method="POST"
                              action="{{route('checkout.cart', ['locale' => app()->getLocale()])}}">
                            @csrf
                            <div class="">
                                <input type="hidden" name="product_amount" value="{{ $price_total}}">
                            </div>

                            <button class="btn btn-primary mb-5 mt-3" id="btn-payment">
                                Submit Payment
                            </button>
                        </form>
                    </div>
                </div>
            @endif


        </div>
    </div>

</div>


@include('footer')
