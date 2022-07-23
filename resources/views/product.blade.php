<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE') }}">
@include('globalScript')
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if(isset($product))
        <title>
            @switch($product->type)
                @case(17)
                {{$product->name}} - {{ __('messages.pice_dorigine_oem_dpoque') }}
                @break
                @case(18)
                {{$product->name}} - {{ __('messages.pice_dorigine_oem_refabrique') }}
                @break
                @case(19)
                {{$product->name}} - {{ __('messages.pice_dorigine_dpoque_reconditionne') }}
                @break
                @case(20)
                {{$product->name}} - {{ __('messages.pice_de_substitution') }}
                @break
            @endswitch
        </title>
        <meta name="description" content="{{ translateObject('description', $product) }}">
        <meta property="og:description" content="{{ translateObject('description', $product) }}">
        <meta property="og:image" content="{{ config('app.url') }}{{$product->img[0]}}">
        <meta property="og:url" content="{{ config('app.url') }}/product/{{$product->id}}">
        <meta property="og:title" content="@switch($product->type)
        @case(17)
        {{$product->name}} - {{ __('messages.pice_dorigine_oem_dpoque') }}
        @break
        @case(18)
        {{$product->name}} - {{ __('messages.pice_dorigine_oem_refabrique') }}
        @break
        @case(19)
        {{$product->name}} - {{ __('messages.pice_dorigine_dpoque_reconditionne') }}
        @break
        @case(20)
        {{$product->name}} - {{ __('messages.pice_de_substitution') }}
        @break
        @endswitch">
    @else
        <title>{{ trans('messages.malheureusement_ce_produit_nest_plus_disponible2') }}</title>
    @endif

    <meta name="theme-color" content="#ffffff">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Classic Parts Finder">


    <link rel="icon" type="image/png" href="/images/logo/icon.png"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <link href="{{ mix('css/product.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width">

</head>
<body class="single-product">
<main class="container">
    <div class="row">
        <div class="offset-lg-3 col-lg-6">
            <div class="bg-white rounded my-3 my-md-5 px-4">

                <section class="text-center py-4">
                    <a href="https://classic-parts-finder.com" aria-label="{{ trans('messages.lien_vers_classic_parts_finder') }}"
                       class="logo">
                        <img src="/images/logo/logo_classic_parts_finder_bicolor.svg" alt="{{ trans('messages.logo_classic_parts_finder') }}"
                             class="py-2">
                    </a>
                </section>

                @if (isset($product))

                    <section>
                        <div class="float-right">
                            <div class="dropdown" style="z-index: 1;">
                                <button class="bg-white shadow rounded-circle share text-primary border-0"><i
                                        class="fas fa-share-alt"></i></button>
                                <div class="dropdown-content px-3 py-2 rounded-little">
                                    <p class="lead mb-2 pt-1">{{ trans('messages.partager_le_produit') }}</p>
                                    <a id="copy" onclick="copy()" class="text-primary"><i
                                            class="fas fa-share-alt fa-fw mr-1"></i> {{ __('messages.copier_le_lien') }}</a>
                                    <a id="is_copy" onclick="copy()" style="display: none;" class="text-success"><i
                                            class="fas fa-check fa-fw mr-1"></i> {{ __('messages.lien_copi') }}</a>
                                    <a target="_blank" class="text-primary"
                                       href="https://www.facebook.com/sharer.php?u={{env('APP_URL')}}/{{ app()->getLocale() }}/product/{{$product->id}}">
                                        <i
                                            class="fab fa-facebook fa-fw mr-1"></i> Facebook</a>
                                </div>
                            </div>
                        </div>

                        <h1 class="text-primary">{{translateObject('name', $product)}}</h1>
                        <p class="text-secondary"><strong>{{translateObject('name', $product->part)}} {{ __('messages.for') }} {{$product->modele[0]->brand->name}} {{$product->modele[0]->name}} @if($product->reference) (ref: {{ $product->reference }}) @endif</strong></p>
                        <p class="text-secondary">
                            @switch($product->type)
                                @case(17)
                                {{ __('messages.pice_dorigine_oem_dpoque') }}
                                @break
                                @case(18)
                                {{ trans('messages.pice_dorigine_oem_refabrique') }}
                                @break
                                @case(19)
                                {{ trans('messages.pice_dorigine_dpoque_reconditionne') }}
                                @break
                                @case(20)
                                {{ trans('messages.pice_de_substitution') }}
                                @break
                            @endswitch



                            @switch($product->condition)
                                @case(12)
                                <span class="badge badge-pill badge-success">{{ trans('messages.tat_neuf') }}</span>
                                @break
                                @case(13)
                                <span class="badge badge-pill badge-success">{{ trans('messages.tat_trs_bon') }}</span>
                                @break
                                @case(14)
                                <span class="badge badge-pill badge-warning">{{ trans('messages.tat_bon') }}</span>
                                @break
                                @case(15)
                                <span class="badge badge-pill badge-warning">{{ trans('messages.tat_satisfaisant') }}</span>
                                @break
                                @case(16)
                                <span class="badge badge-pill badge-danger">{{ trans('messages.tat_rnover') }}</span>
                                @break
                            @endswitch
                        </p>

                        <div class="img-wrapper mb-3">

                            @if ($product->img&&count($product->img) == 1)
                                <div class="h-100 w-100">
                                    <a href="{{$product->img[0]}}" data-lightbox="product-lightbox">
                                        <img src="{{$product->img[0]}}">
                                    </a>
                                </div>
                            @else
                                <div class="swiper-container img-wrapper">
                                    <div class="swiper-wrapper">
                                        @if ($product->img)
                                            @for ($i = 0; $i < count($product->img); $i++)
                                                <div class="swiper-slide img-wrapper">
                                                    <div class="h-100 w-100">
                                                        <a href="{{$product->img[$i]}}" data-lightbox="product-lightbox">
                                                            <img src="{{$product->img[$i]}}">
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
                            @endif

                        </div>

                        <table class="table text-primary table-striped">
                            <tbody>

                            <tr>
                                <td class="border-0">
                                    <strong>{{ trans('messages.description') }} </strong>
                                    <div style="white-space:pre-line">
                                        {{ translateObject('description', $product) }}
                                    </div>
                                </td>
                            </tr>

                            @if(count($product->modele) > 0)
                                <tr>
                                    <td class="border-0">
                                        <strong>{{ trans('messages.vhicules_compatibles') }} </strong>
                                        <ul>
                                            @foreach ($product->modele as $modele)
                                                <li>{{$modele->brand->name}} {{$modele->name}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif

                            @if ($product->reference)
                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">{{ trans('messages.rfrence_du_produit') }}</strong>
                                        {{ $product->reference }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="border-0">
                                    <p class="mb-1"><strong>{{ trans('messages.a_propos_du_vendeur') }}</strong></p>

                                    @if ($product->merchant->user[0]->role!=='admin')
                                        {{ __('messages.seller') }} {{ $product->merchant->merchant_type }} -
                                        {{ $product->merchant->region ? $product->regionObj->name . ' - ' : '' }} {{ $product->merchant->country->name }}
                                        <img class="flag" src="/images/flags/{{strtolower($product->merchant->country->code)}}.svg">

                                    @else
                                        {{ trans('messages.vendu_directement_par_cpf') }}
                                    @endif
                                </td>
                            </tr>

                            @if ($product->weight)
                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">{{ trans('messages.poids_du_produit') }}</strong>
                                        {{ $product->weight }} Kg
                                    </td>
                                </tr>
                            @endif

                            @if ($product->width && $product->height && $product->depth)
                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">{{ trans('messages.dimensions_du_produit') }}</strong>
                                        {{ $product->width }}cm x {{ $product->height }}cm x {{ $product->depth }}cm
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td class="border-0">
                                    <strong class="d-inline-block mr-2">{{ __('messages.price') }}</strong>{{ __('messages.hors_frais_de_ports') }}
                                    {{ number_format($product->price, 2) }} {{ $product->currency }}
                                    {{ $product->negotiable ? '(Négociable via Obvy)' : '(Non négociable)' }}
                                </td>
                            </tr>

                            <tr>
                                <td class="border-0">
                                    <p class="mb-1">
                                        <strong>{{ trans('messages.expdition') }}</strong>
                                        @if ($product->averagePreparationTime)
                                            {{ __('messages.remis_sous_x_jours', ['average' => $product->averagePreparationTime]) }}
                                        @endif
                                    </p>
                                    @if ($product->delivery_option === 0)
                                        {{ trans('messages.envoi_postal') }}
                                    @endif
                                    @if ($product->delivery_option === 1)
                                        {{ trans('messages.remise_en_main_propre_et_envoi_postal') }}
                                    @endif
                                    @if ($product->delivery_option === 2)
                                        {{ trans('messages.remise_en_main_propre_uniquement') }}
                                    @endif
                                    <br>
                                    @if ($product->delivery_option !== 2)
                                        <i>{{ trans('messages.frais_de_port_en_sus_calculer_selon_le_mode_de_liv') }}</i>
                                    @endif
                                </td>
                            </tr>

                            </tbody>
                        </table>



                         <a href="{{route('checkout.get',['locale'=>app()->getLocale(),'id'=>$product->id])}}" class="btn btn-lg btn-primary border-0 py-3 w-100">Achete</a>
  
                    </section>

                @else

                    <section class="text-center pb-4">
                        <img class="w-50 py-4" src="/images/missing-product.svg"
                             alt="{{ trans('messages.malheureusement_ce_produit_nest_plus_disponible3') }}">
                        <h1 class="text-center text-primary py-2">{!! trans('messages.malheureusement_ce_produit_nest_plus_disponible4') !!}
                        </h1>
                        <a href="https://classic-parts-finder.com/" target="_blank"
                           aria-label="{{ trans('messages.lien_de_connexion_classic_parts_finder') }}"
                           class="btn btn-lg btn-secondary py-3 mt-3">{{ trans('messages.dcouvrir_dautres_produits') }}</a>
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
        let text = "{{env('APP_URL')}}/{{ app()->getLocale() }}/product/{{ $product->id ?? '' }}";
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
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
    })
</script>
</body>
</html>
