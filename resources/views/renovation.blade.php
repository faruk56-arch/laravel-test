
@include('header', ['mainTitle' => "Rénovez votre pièce"])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <section class="container pb-5 mb-5">
        <div class="row">
            <div class="col-md-8 offset-md-2 ">
                <form class="form-md landing-form">

                    <div class="form-group mb-3">
                        <label for="name">Votre nom et prénom <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 mb-3">
                            <label for="email">Adresse e-mail <sup class="text-danger">*</sup></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group col-lg-6 mb-3">
                            <label for="phone">Téléphone <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                    

                    <div class="form-group mb-3">
                        <label for="custom_modele_vehicule">Modèle du véhicule à rénover <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="custom_modele_vehicule" name="custom_modele_vehicule" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="message">Décrivez le type de rénovation souhaitée <sup class="text-danger">*</sup></label>
                        <textarea rows="7" class="form-control pt-3" id="message" name="message" required></textarea>
                    </div>

                    <div class="form-group mt-4">
                        <small><i><sup class="text-danger">*</sup> : Champs obligatoires</i></small>
                    </div>

                    <div class="g-recaptcha" data-sitekey="{{ env('MIX_CAPTCHA_API_KEY') }}"></div>

                    <input type="submit" class="btn btn-default bg-blue float-right px-4 px-md-5 py-3" value="Envoyer">
                </form>

                <div class="text-center landing-form-thanks" style="display: none">
                    <i class="fal fa-check-circle fa-4x text-blue"></i>
                    <h2 class="h3 text-center py-3">Votre demande à bien été<br class="d-none d-lg-block">prise en compte</h2>
                    <p class="text-center">Nos experts en rénovation recherchent pour vous les meilleures solutions pour répondre à vos attentes. Ils vous recontacteront dans les meilleurs délais !</p>
                </div>
            </div>
        </div>
    </section>

<script async src="https://leads.wigo-media.com/cdn/landing-page.js"></script>

@include('footer')

