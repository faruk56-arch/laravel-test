@include('header')

<div class="container mt-5">

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">{{trans('messages.your_cart')}}</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Product name</h6>
                        <small class="text-muted">{{session('prices')['product_name']}}</small>
                    </div>
                    <span class="text-muted">{{session('prices')['price_pay']}} €</span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (EUR)</span>
                    <strong>{{session('prices')['price_pay']}} €</strong>
                </li>
            </ul>
        </div>

        <div class="col-md-8 order-md-1">
            <h3 class="mb-3">Billing Information</h3>
            <hr>

            @if(count($errors) > 0 )
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="p-0 m-0" style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{route("checkout.post",['locale'=>app()->getLocale()])}}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <input type="hidden" name="product_price" value="{{$product->price}}">
                <input type="hidden" name="merchant_id" value="{{$product->merchant_id}}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstName"
                               name="firstname" value="{{old('firstname')}}">

                        @error('firstname')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror

                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastName"
                               name="lastname" value="{{old('lastname')}}">
                        @error('lastname')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="username"
                               name="phone" value="{{old('phone')}}">
                        @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               name="email" value="{{old('email')}}">
                        @error('email')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                              rows="5">{{old('address')}}</textarea>
                    @error('address')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="country">Country</label>
                        <select class="form-control @error('country_id') is-invalid @enderror"
                                id="country" name="country_id">
                            <option value="">Choose...</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach

                        </select>
                        @error('country_id')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="region">State</label>
                        <select class="form-control @error('region') is-invalid @enderror" id="region"
                                name="region">

                        </select>
                        @error('region')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        {{--
                         <label for="address2">City <span class="text-muted">(Optional)</span></label>
                        --}}
                        <label for="address2">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="address2"
                               name="city" value="{{old('city')}}">
                        @error('city')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip"
                               value="{{old('zip')}}">
                        @error('zip')
                        <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                {{--
                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address">
                    <label class="custom-control-label" for="same-address">Shipping address is the same as my billing
                        address</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Save this information for next time</label>
                </div>
                <hr class="mb-4">
                --}}
                {{--
                <h4 class="mb-3">Payment</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cc-name">Name on card</label>
                        <input type="text" class="form-control" id="cc-name" placeholder="">
                        <small class="text-muted">Full name as displayed on card</small>
                        <div class="invalid-feedback">
                            Name on card is required
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cc-number">Credit card number</label>
                        <input type="text" class="form-control" id="cc-number" placeholder="">
                        <div class="invalid-feedback">
                            Credit card number is required
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cc-expiration">Expiration</label>
                        <input type="text" class="form-control" id="cc-expiration" placeholder="">
                        <div class="invalid-feedback">
                            Expiration date required
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cc-cvv">CVV</label>
                        <input type="text" class="form-control" id="cc-cvv" placeholder="">
                        <div class="invalid-feedback">
                            Security code required
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                --}}

                <h3 class="mb-3">Shipping company</h3>
                <hr>
                <div class="col-md-12 mb-3">
                    <label for="country">Shipping company</label>
                    <select class="form-control @error('shipping') is-invalid @enderror" id="country" name="shipping">
                        <option value="">Choose...</option>
                        <option value="CHRP">Chronopost</option>
                        <option value="COPR">Colis Privé</option>
                        <option value="DLVG">Delivengo</option>
                        <option value="DHLE">DHL</option>
                        <option value="FEDX">Fedex</option>
                        <option value="IMXE">Happy Post</option>
                        <option value="MONR">Mondial Relay</option>
                        <option value="POFR">Colissimo</option>
                        <option value="SOGP">Relais Colis</option>
                        <option value="TNTE">TNT</option>
                        <option value="UPSE">UPS</option>
                    </select>
                    @error('shipping')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror

                </div>

                <button class="btn btn-primary btn-lg btn-block mb-5" type="submit">Continue to checkout</button>
            </form>

        </div>
    </div>

</div>


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('#country').on('change', function (e) {
                const cat_id = e.target.value;
                $.ajax({
                    url: "{{ route('get-regions',['locale' => app()->getLocale()]) }}",
                    type: "POST",
                    data: {
                        cat_id: cat_id
                    },
                    success: function (data) {
                        $('#region').empty();

                        if (data.regions.length > 0) {
                            $('#region').append('<option value="">----Regions-----</option>');
                        }

                        $.each(data.regions, function (index, region) {
                            $('#region').append('<option value="' + region.id + '">' + region.name + '</option>');
                        })
                    }
                })
            });
        });
    </script>
@endpush
@include('footer')
