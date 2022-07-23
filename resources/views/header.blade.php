<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('globalScript')
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title') }}</title>
    <meta name="description" content="Service de recherche de pièces détachées entre particuliers et professionnels. Pièces d’origine ou rééditions.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Classic Parts Finder | Vente et achat de pièces détachées pour véhicules anciens">
    <meta property="og:description" content="Service de recherche de pièces détachées entre particuliers et professionnels. Pièces d’origine ou rééditions.">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:site_name" content="Classic Parts Finder">
    <meta property="og:image" content="{{ config('app.url') }}/images/home/og-img.jpg">
    <meta property="og:image:width" content="1920">
    <meta property="og:image:height" content="1081">
    <link rel="icon" type="image/png" href="images/logo/icon.png"/>
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ mix('css/home.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width">

</head>

<body class="website" id="{{Route::currentRouteName()}}-page">

<header class="container-fluid px-0 @if (Route::currentRouteName() != 'sell') bg-grad-grey-light @endif">

    <nav class="navbar navbar-expand-lg zi-5 py-4 @if (Route::currentRouteName() === 'home') mb-5 @endif">
        <div class="container position-static">
            <div class="logo navbar-brand">
                <a href="/{{ app()->getLocale() }}">
                    @if (Route::currentRouteName() === 'sell')
                        <img src="/images/logo/logo_classic_parts_finder_white.svg" alt="Classic Parts Finder" class="logo">
                    @else
                        @if (Route::currentRouteName() === 'home')
                            <img src="/images/logo/logo_classic_parts_finder_bicolor_white.svg" alt="Classic Parts Finder" class="logo">
                            <img src="/images/logo/logo_classic_parts_finder_bicolor.svg" alt="Classic Parts Finder" class="logo logo-blue d-lg-none">
                        @else
                            <img src="/images/logo/logo_classic_parts_finder_bicolor.svg" alt="Classic Parts Finder" class="logo">
                        @endif
                    @endif
                </a>
            </div>
            <div class="navbar-collapse justify-content-end">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link px-3" onclick="mobileMenu()" href="/{{ App::getLocale() }}/search/model">{{ trans('messages.confier_une_recherche') }}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" onclick="mobileMenu()" href="/{{ App::getLocale() }}/je-vends-des-pieces">{{ trans('messages.vendre_des_pices') }}</a>
                    </li>

                 <!--
                    <li class="nav-item">
                        <a class="nav-link px-3" onclick="mobileMenu()" href="https://dz8uzarjxc.preview.infomaniak.website/shop-cpf/">{{ trans('messages.shop') }}</a>
                    </li>

                 -->


                    @if (!Auth::check())
                        <li class="nav-item">
                            <a class="nav-link px-3" href="/{{ App::getLocale() }}/newAccount" rel="nofollow">{{ trans('messages.inscription') }}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        @if (!Auth::check())
                            <a class="btn btn-default {{(Route::currentRouteName() === 'home') ? "bg-white" : "bg-blue" }} px-3 mt-4 mt-md-0 ml-md-3" href="/{{ App::getLocale() }}/login" rel="nofollow"><i class="fal fa-user-circle"></i> <span>{{ trans('messages.connexion') }}</span></a>
                        @else
                            <a class="btn btn-default {{(Route::currentRouteName() === 'home') ? "bg-white" : "bg-blue" }} px-3 ml-3" href="/{{ App::getLocale() }}/finder/dashboard" rel="nofollow"><i class="fal fa-user-circle"></i> <span>{{ trans('messages.tableau_de_bord') }}</span></a>
                        @endif
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="/fr">
                            FR
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/en">
                            EN
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/de">
                            DE
                        </a>
                    </li>
                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link" href="/de">--}}
                    {{--                            DE--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                </ul>
            </div>
            <div class="menu-switch d-block d-lg-none" onclick="mobileMenu()">
                @if (Route::currentRouteName() === 'home')
                    <i class="fal fa-bars fa-2x text-white open"></i>
                @else
                    <i class="fal fa-bars fa-2x text-blue open"></i>
                @endif
                <i class="fal fa-times fa-2x text-blue close-m"></i>
            </div>
        </div>
    </nav>

    @if (Route::currentRouteName() === 'home')
        {{-- <div class="head-home">

            <div class="container pb-5 py-lg-5 position-relative zi-3">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <h1 class="mb-lg-5 pb-5">
                            <small class="d-block text-uppercase">Nous sommes des</small>chasseurs de<br class="d-none d-lg-block"/> pièces détachées !
                        </h1>
                        <p class="text-blue h5">
                            <strong>Besoin de la meilleure pièce détachée pour<br class="d-none d-lg-block"/> restaurer votre véhicule ancien ou youngtimer ?</strong>
                        </p>
                        <p class="mt-4">
                            <a class="btn btn-default px-4 px-md-5 py-3 bg-gold shadow mr-3 mb-3 mb-md-0" href="/model" rel="nofollow">Je vous confie ma recherche</a>
                            @if (!Auth::check() || (Auth::check() && !Auth::user()->merchant_id))
                                <a class="btn btn-default w-lg-100 px-4 px-md-5 py-3 bg-gold shadow" href="/je-vends-des-pieces">Je vends des pièces</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="parts zi-1"></div>
            <div class="chasseurs zi-2"></div>

        </div> --}}

        <section class="head-home" style="background-image: url('/images/home/head-home-illustration.png');">
            <div class="container">
                <div class="row py-md-5 py-0 ">
                    <div class="col-12 col-lg-7 py-0 py-md-5 pr-0 d-flex justify-content-center flex-column">
                        <p class="lead text-uppercase text-gold"><strong>{!! trans('messages.la_premire_plateforme_internationale_de_mise_en_re') !!}</strong></p>
                        <h1 class="text-white"><strong>{!! trans('messages.trouver_vendre_et_rnover_vos_pices_dtaches') !!}</strong></h1>
                    </div>
                    <div class="col-12 col-lg-5 d-none d-lg-flex align-items-end">
                        <img src="/images/SearchCarPartsCategories/simple_car_classic_parts_finder.png" class="car-home-img">
                        <img src="/images/SearchCarPartsCategories/interieur_car_classic_parts_finder.png" class="car-home-img">
                        <img src="/images/SearchCarPartsCategories/liaison_car_classic_parts_finder.png" class="car-home-img">
                        <img src="/images/SearchCarPartsCategories/moteur_car_classic_parts_finder.png" class="car-home-img">
                        <img src="/images/SearchCarPartsCategories/documentation_car_classic_parts_finder.png" class="car-home-img">
                    </div>
                </div>
            </div>
        </section>


    @elseif(Route::currentRouteName() != 'sell'  &&
Route::currentRouteName() != 'checkout.get' &&
Route::currentRouteName() != 'checkout.post' &&
Route::currentRouteName() != 'checkout.cart' &&
Route::currentRouteName() != 'payment'  &&
Route::currentRouteName() != 'result' &&
Route::currentRouteName() != 'checkout.shipping'
)
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center h2 py-5"><strong> {{ $mainTitle }} </strong></h1>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script type="text/javascript">

        function mobileMenu(){
            document.querySelector('body').classList.toggle('menu-active');
            document.querySelector('header .menu-switch').classList.toggle('active');
            document.querySelector('header .navbar-collapse').classList.toggle('active');
        }

    </script>

</header>

