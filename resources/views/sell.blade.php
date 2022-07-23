@include('header')

<div class="merchant-page-header pt-4" style="background-image: url('/images/merchant_page_bg.jpg')">
    <div class="container position-relative">
        <div class="col-md-6 offset-md-6 informations">
            <h1 class="text-white h3 h2-carter mb-4">Avec
                <span class="text-gold">Classic Parts Finder</span>, vendre ses pièces détachées est un jeu d’enfants !
            </h1>
            <h2 class="h4 text-white mb-3">Créez votre boutique<br>gratuitement en 2 minutes !</h2>
            <div>
                <a class="btn bg-gold btn-default mr-3 mb-3 mb-xl-0 mb-xs-0 mb-md-3 px-md-5 py-3 " href="/{{ app()->getLocale() }}/merchant/settings/Pro" rel="nofollow">Je suis un professionnel</a><br class="d-md-none">
                @if (!Auth::check() || (Auth::check() && !Auth::user()->merchant_id))
                    <a class="btn bg-gold px-md-5 py-3 btn-default" href="/{{ app()->getLocale() }}/merchant/settings/Particulier" rel="nofollow">Je suis un particulier</a>
                @endif
            </div>
            @if (!Auth::check())
                <a class="basic text-white mt-3 d-block" href="/login" rel="nofollow">J'ai déjà un compte</a>
            @endif
        </div>
    </div>
</div>

<div class="container">
    <div class="row py-5 my-4">
        <div class="col-md-6 d-flex justify-content-center flex-column">
            <div class="row">
                <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                    <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                        <i class="fal fa-bullhorn text-white"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <h2 class="text-blue h3 font-family-reset">Recevez des alertes quand un acheteur se présente</h2>
                    <p>Vous avez des produits en stock mais pas le temps de constituer un catalogue ? On vous comprend !
                        Dites-nous simplement le type de pièce ou la catégorie de produit que vous souhaitez vendre, nous vous notifions dès que nous trouvons un acheteur.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img class="w-100" src="/images/merchant/merchant_2.svg">
        </div>
    </div>
    <div class="row py-5">
        <div class="col-md-6 order-md-1 order-2">
            <img class="w-100" src="/images/merchant/merchant_1.svg">
        </div>
        <div class="col-md-6 d-flex justify-content-center flex-column order-md-2 order-1">
            <div class="row">
                <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                    <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                        <i class="fal fa-store text-white"></i>
                    </div>
                </div>
                <div class="col-md-10">

                    <h2 class="text-blue h3 font-family-reset">Diffusez vos produits rapidement et simplement</h2>
                    <p>Vous souhaitez vendre 1 ou même 1000 produits ? Aucun problème ! Diffusez vos annonces sur Classic Parts Finder et vendez vos produits en toute sérénité sur notre réseau de recherche.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row py-5 my-4">
        <div class="col-md-6 d-flex justify-content-center flex-column">
            <div class="row">
                <div class="col-4 mb-3 mb-md-0 col-md-2 pl-md-0 pr-md-0">
                    <div class="working-wheel" style="background-image: url('/images/home/working-wheel.svg')">
                        <i class="fal fal fa-shield-check text-white"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <h2 class="text-blue h3 font-family-reset">Paiement sécurisé et
                        garanti par <img class="px-1" src="/images/logo-obvy.png"></h2>
                    <p>Vendez vos pièces en toute sécurité avec Obvy, notre partenaire qui sécurise vos ventes !</p>

                    <a class="basic text-gold" href="/{{ app()->getLocale() }}/merchant/obvy" rel="nofollow">En savoir plus</a>

                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center flex-column">
            <img class="w-75" src="/images/merchant/merchant_3.svg">
        </div>
    </div>
</div>

<div class="bg-gold text-white py-5">
    <div class="container">
        <h2 class="text-center h3 text-white font-family-reset mb-1">Prêt à vendre vos pièces ?</h2>
        <p class="lead font-weight-normal text-center mb-4">L'inscription ne vous prendra que quelques instants ! </p>
        <div class="d-flex align-items-center justify-content-center flex-wrap">
            <a class="btn bg-white px-md-5 py-3  mr-md-3 mb-3 mb-md-0 btn-default" href="/{{ app()->getLocale() }}/merchant/settings/Pro">Je suis un professionnel</a>
            @if (!Auth::check() || (Auth::check() && !Auth::user()->merchant_id))
                <a class="btn bg-white px-md-5 py-3 btn-default" href="/{{ app()->getLocale() }}/merchant/settings/Particulier">Je suis un particulier</a>
            @endif

        </div>
    </div>
</div>

@include('footer')

