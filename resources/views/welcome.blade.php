<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE') }}">
@include('globalScript')
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans('messages.title') }}</title>
    <meta name="description" content="Service de recherche de pièces détachées entre particuliers et professionnels. Pièces d’origine ou rééditions.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Classic Parts Finder | Vente et achat de pièces détachées pour véhicules anciens">
    <meta property="og:description" content="Service de recherche de pièces détachées entre particuliers et professionnels. Pièces d’origine ou rééditions.">
    <meta property="og:url" content="{{ config('app.url') }}/">
    <meta property="og:site_name" content="Classic Parts Finder">
    <meta property="og:image" content="{{ config('app.url') }}/images/home/og-img.jpg">
    <meta property="og:image:width" content="1920">
    <meta property="og:image:height" content="1081">
    <link rel="icon" type="image/png" href="images/logo/icon.png"/>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width">
</head>
<body>

<div id="app">
    <app></app>
</div>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{ mix('/js/modernizr-custom.js') }}"></script>
{{--<script src="//code.tidio.co/qkjohnbtaqvhv9b1q6s9ksadurigf7cv.js"></script>--}}
<!-- Hotjar Tracking Code for https://classic-parts-finder.com/ -->
@if (App::environment('production'))
    <script>
        (function (h, o, t, j, a, r) {
            h.hj = h.hj || function () {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {hjid: 2124920, hjsv: 6};
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
@endif
<script type="application/ld+json">
	{
	  "@context": "https://schema.org",
	  "@graph": [
	    {
	      "@type": "Organization",
	      "@id": "{{ config('app.url') }}/",
	      "name": "Classic Parts Finder",
	      "url": "{{ config('app.url') }}/",
	      "sameAs": [],
	      "logo": {
	        "@type": "ImageObject",
	        "inLanguage": "fr-FR",
	        "url": "{{ config('app.url') }}/images/logo/logo_classic_parts_finder_blue.svg",
	        "width": 68,
	        "height": 28,
	        "caption": "Logo Classic Parts Finder"
	      }
	    },
	    {
	      "@type": "WebSite",
	      "@id": "{{ config('app.url') }}/",
	      "url": "{{ config('app.url') }}/",
	      "name": "Classic Parts Finder",
	      "description": "Service de recherche de pièce détachée entre particuliers et professionnels. Pièces d’origine ou rééditions.",
	      "publisher": {
	        "@id": "{{ config('app.url') }}/"
	      },
	      "inLanguage": "fr-FR"
	    },
	    {
	      "@type": "ImageObject",
	      "@id": "{{ config('app.url') }}/",
	      "inLanguage": "fr-FR",
	      "url": "{{ config('app.url') }}/images/home/og-img.jpg",
	      "width": 1920,
	      "height": 1081
	    },
	    {
	      "@type": "WebPage",
	      "@id": "{{ config('app.url') }}/",
	      "url": "{{ config('app.url') }}/",
	      "name": "Classic Parts Finder | Vente et achat de pièces détachées pour véhicules anciens",
	      "description": "Service de recherche de pièce détachée entre particuliers et professionnels. Pièces d’origine ou rééditions.",
	      "inLanguage": "fr-FR",
	      "potentialAction": [
	        {
	          "@type": "ReadAction",
	          "target": [
	            "{{ config('app.url') }}/"
	          ]
	        }
	      ]
	    }
	  ]
	}

</script>
</body>
</html>
