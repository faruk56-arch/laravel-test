<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE') }}">
@include('globalScript')
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if(isset($research))
        <title>
            @switch($research->type)
                @case(17)
                {{$research->name}} - {{ __('messages.pice_dorigine_oem_dpoque') }}
                @break
                @case(18)
                {{$research->name}} - {{ __('messages.pice_dorigine_oem_refabrique') }}
                @break
                @case(19)
                {{$research->name}} - {{ __('messages.pice_dorigine_dpoque_reconditionne') }}
                @break
                @case(20)
                {{$research->name}} - {{ __('messages.pice_de_substitution') }}
                @break
                @default
                {{translateObject('name', $research->part)}}
                @break
            @endswitch
        </title>
        <meta name="description" content="{{ translateObject('details', $research) }}">
        <meta property="og:description" content="{{ translateObject('details', $research) }}">
        @if($research->img)
        <meta property="og:image" content="{{ config('app.url') }}{{$research->img[0]}}">
        @endif
        <meta property="og:url" content="{{ config('app.url') }}/research/{{$research->id}}">
        <meta property="og:title" content="@switch($research->type)
        @case(17)
        {{$research->name}} - Pièce d’origine (OEM) d’époque
                @break
        @case(18)
        {{$research->name}} - Pièce d’origine (OEM) refabriquée
                @break
        @case(19)
        {{$research->name}} - Pièce d’origine d’époque reconditionnée
                @break
        @case(20)
        {{$research->name}} - Pièce de substitution
                @break
        @default
            {{translateObject('name', $research->part)}}
            @break
        @endswitch">
    @else
        <title>{{ trans('messages.malheureusement_cette_recherche_nest_plus_disponib') }}</title>
    @endif

    <meta name="theme-color" content="#ffffff">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Classic Parts Finder">


    <link rel="icon" type="image/png" href="/images/logo/icon.png"/>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link href="{{ mix('css/product.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width">

</head>
<body class="single-product single-research">
<main class="container">
    <div class="row">
        <div class="offset-lg-3 col-lg-6">
            <div class="bg-white rounded my-3 my-md-5 px-4">

                <section class="text-center py-4">
                    <a href="{{ config('app.url') }}/" aria-label="{{ trans('messages.lien_vers_classic_parts_finder') }}"
                       class="logo">
                        <img src="/images/logo/logo_classic_parts_finder_bicolor.svg" alt="{{ trans('messages.logo_classic_parts_finder') }}"
                             class="py-2">
                    </a>
                </section>

                @if(isset($research))

                    <section>
                        <div class="float-right">
                            <div class="dropdown">
                                <button class="bg-white shadow rounded-circle share text-primary border-0"><i
                                        class="fas fa-share-alt"></i></button>
                                <div class="dropdown-content px-3 py-2 rounded-little">
                                    <p class="lead mb-2 pt-1">{{ trans('messages.partager_la_recherche') }}</p>
                                    <a id="copy" onclick="copy()" class="text-primary"><i
                                            class="fas fa-share-alt fa-fw mr-1"></i> {{ __('messages.copier_le_lien') }}</a>
                                    <a id="is_copy" onclick="copy()" style="display: none;" class="text-success"><i
                                            class="fas fa-check fa-fw mr-1"></i> {{ __('messages.lien_copi') }}</a>
                                    <a target="_blank" class="text-primary"
                                       href="https://www.facebook.com/sharer.php?u={{env('APP_URL')}}/{{ app()->getLocale() }}/research/{{ $research->id }}"><i
                                            class="fab fa-facebook fa-fw mr-1"></i> Facebook</a>
                                </div>
                            </div>
                        </div>

                        <h1 class="text-primary">{{ translateObject('name', $research->part) }}</h1>
                        @if($research->modele)
                        <p class="text-secondary">
                            {{ __('messages.for') }} {{ $research->modele->modele->brand->name }}
                            {{ $research->modele->modele->name }} {{ $research->modele->version }}
                            {{ __('messages.from') }} {{ $research->modele->year }}
                            {{-- <span class="badge badge-pill badge-success">État neuf</span>--}}
                        </p>
                        @endif

                        <div class="img-wrapper mb-3">


                            @if ($research->img && count($research->img) == 1)
                                <div class="h-100 w-100">
                                    <a href="{{$research->img[0]}}" data-lightbox="research-lightbox">
                                        <img src="{{$research->img[0]}}">
                                    </a>
                                </div>
                            @elseif($research->img && count($research->img) > 1)
                                <div class="swiper-container img-wrapper">
                                    <div class="swiper-wrapper">
                                        @if ($research->img)
                                            @for ($i = 0; $i < count($research->img); $i++)
                                                <div class="swiper-slide img-wrapper">
                                                    <div class="h-100 w-100">
                                                        <a href="{{$research->img[$i]}}" data-lightbox="research-lightbox">
                                                            <img src="{{$research->img[$i]}}">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>

                            @else
                            <img class="cloud" src="/images/public-research/cloud.svg">

                            <img class="car" src="{{ $research->modele->modele->img ? '/images/Cars/' . $research->modele->modele->img . '.png' : '/images/Cars/tn/emptycar.png'}}">
                            @endif

                        </div>



                        <table class="table text-primary table-striped">
                            <tbody>


                            @if(translateObject('details', $research))
                            <tr>
                                <td class="border-0">
                                    <strong>{{ trans('messages.description') }} </strong>{{ translateObject('details', $research) }}
                                </td>
                            </tr>
                            @endif

                            @if($research->reference)
                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ trans('messages.rfrence_du_produit') }}</strong> {{ $research->reference }}
                                </td>
                            </tr>
                            @endif

                            @if($research->compatible_suggestion)
                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ trans('messages.probablement_compatible_avec') }}</strong> {{ $research->compatible_suggestion }}
                                </td>
                            </tr>
                            @endif

                            @if(count($research->types) > 0)
                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ trans('messages.types_de_pice_recherchs') }}</strong>
                                    <ul>
                                    @foreach ($research->types as $type)
                                        @switch($type)
                                            @case(17)
                                            <li>{{ trans('messages.pice_dorigine_oem_dpoque') }}</li>
                                            @break
                                            @case(18)
                                            <li>{{ trans('messages.pice_dorigine_oem_refabrique') }}</li>
                                            @break
                                            @case(19)
                                            <li>{{ trans('messages.pice_dorigine_dpoque_reconditionne') }}</li>
                                            @break
                                            @case(20)
                                            <li>{{ trans('messages.pice_de_substitution') }}</li>
                                            @break
                                        @endswitch
                                    @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ trans('messages.a_propos_de_lutilisateur') }}</strong>
                                    @if($research->user->country)
                                    <div>
                                        {{ $research->user->country->name }}
                                        <img class="flag" src="/images/flags/{{ $research->user->country->code }}.svg">
                                    </div>
                                    @endif
                                </td>
                            </tr>

                            </tbody>
                        </table>

                        <h1 class="text-center text-primary mt-4 mb-3">{{ trans('messages.vous_avez_ce_produit') }}</h1>
                        @if (Auth::check())
                            <a href="{{ config('app.url') . '/' . app()->getLocale() . '/finder/catalog/addProduct' }}" target="_blank"
                               aria-label="{{ trans('messages.lien_de_connexion_classic_parts_finder') }}"
                               class="btn btn-lg btn-primary w-100 py-3">{{ __('messages.sell_it') }}</a>
                        @else
                        <a href="{{ config('app.url') }}/{{ app()->getLocale() }}/login" target="_blank"
                           aria-label="{{ trans('messages.lien_de_connexion_classic_parts_finder2') }}"
                           class="btn btn-lg btn-primary w-100 py-3">{{ trans('messages.crez_votre_compte_gratuitement_et_vendezle') }}</a>
                        @endif
                        <div class="text-center mt-3">
                            <a href="/{{ app()->getLocale() }}/search/model" target="_blank" class="text-secondary"><u>{{ trans('messages.je_recherche_une_autre_pice') }}</u></a>
                        </div>


                    </section>

                @else
                    <section class="text-center pb-4">
                        <img class="w-50 py-4" src="/images/missing-product.svg"
                             alt="{{ trans('messages.malheureusement_ce_produit_nest_plus_disponible') }}">
                        <h1 class="text-center text-primary py-2">{!! trans('messages.malheureusement_cette_recherche_nest_plus_disponib2') !!}
                        </h1>
                        <a href="{{ config('app.url') }}/" target="_blank"
                           aria-label="{{ trans('messages.lien_de_connexion_classic_parts_finder3') }}"
                           class="btn btn-lg btn-secondary py-3 mt-3">{{ trans('messages.dcouvrir_dautres_recherches') }}</a>
                        <div class="text-center mt-3">
                            <a href="/{{ app()->getLocale() }}/search/model" target="_blank" class="text-secondary"><u>{{ trans('messages.je_recherche_une_pice') }}</u></a>
                        </div>
                    </section>
                @endif


                <section class="text-center pt-4 pb-4 text-secondary">
                    <small>© <?php echo date('Y'); ?> {{ __('messages.all_right_reserved') }}</small>
                </section>

            </div>
        </div>
    </div>
</main>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js" integrity="sha512-6gudNVbNM/cVsLUMOb8g2b/RBqtQJ3aDfRFgU+5paeaCTtbYY/Dg00MzZq7r6RvJGI2KKtPBhjkHGTL/iOe21A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://kit.fontawesome.com/0bf2c054ae.js" crossorigin="anonymous"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    function copy() {
        let text = "{{env('APP_URL')}}/{{ app()->getLocale() }}/research/{{ $research->id ?? '' }}";
        if (window.clipboardData && window.clipboardData.setData) {
            return window.clipboardData.setData("Text", text);
        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.querySelector('#copy').style.display = "none";
                document.querySelector('#is_copy').style.display = "block";
                return document.execCommand("copy");
                // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }

</script>
</body>
</html>
