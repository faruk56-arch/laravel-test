@include('header')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 order-md-1">
            <h1 class="mb-3">Payment form</h1>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    Soyez rassuré, votre argent est sécurisé durant toute la durée de la transaction. <br>
                    Votre paiement ne sera remis au vendeur qu'une fois le bien en votre possession.
                </div>
            </div>
            <hr>

            <div class="col-md-8">

                <form id="payment-form" method="POST" action="{{route('payment',['locale'=>app()->getLocale()])}}">
                    @csrf
                    <div class="">
                        <input type="hidden" name="product_amount" value="{{ $product_price}}">
                        <input type="hidden" name="product_id" value="{{$product_id}}">
                        <input type="hidden" name="merchant_id" value="{{session('prices')['merchant_id']}}">
                        <h4 class="mb-4">Total amount of payment :
                            <strong class="text-gold">{{ $product_price}} EUR</strong>
                        </h4>
                        <label for="card-element">

                        </label>
                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors. -->
                        <div id="card-errors" class="text-danger" role="alert"></div>
                    </div>

                    <h5 id="loading" class="text-gold mt-4" style="display:none;"> Payment is in process . please
                        wait...</h5>
                    <button class="btn btn-primary mb-5 mt-3" id="btn-payment">
                        Submit Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>


@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        window.onload = function () {
            var stripe = Stripe("{{env('STRIPE_PUBLIC_API_KEY')}}");
            var elements = stripe.elements();
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            var card = elements.create('card', {
                style: style
            });
            document.getElementById("btn-payment").disabled = false;
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            // Handle form submission.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                var loading = document.getElementById('loading')
                loading.style.display = "block";
                document.getElementById("btn-payment").disabled = true;
                form.submit();

            }
        }
    </script>
@endpush
@include('footer')

