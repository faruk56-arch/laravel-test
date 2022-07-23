
@include('header', ['mainTitle' => "Nous contacter"])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <section class="container pb-5 mb-5">
        <div class="row">
            <div class="col-md-8 offset-md-2 ">

                <form class="form-md" action="/api/contact" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="name">Votre nom <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="email">Adresse e-mail </label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <div class="form-group mb-2">
                        <label for="phone">Téléphone <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="message">Votre demande <sup class="text-danger">*</sup></label>
                        <textarea rows="7" class="form-control" id="message" name="message" required></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <small><i><sup class="text-danger">*</sup> : Champs obligatoires</i></small>
                    </div>

                    <div class="g-recaptcha" data-sitekey="{{ env('MIX_CAPTCHA_API_KEY') }}"></div>

                    <input type="submit" class="btn btn-default bg-blue float-right px-4 px-md-5 py-3" value="Envoyer">
                </form>

            </div>
        </div>
    </section>

@include('footer')

