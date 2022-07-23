@include('header', ['mainTitle' => "Déclarer un litige"])
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div id="litiges">
    <litiges sitekey="{{ config('app.MIX_CAPTCHA_API_KEY') }}"></litiges>
</div>

<script src="{{mix('js/litiges.js')}}"></script>
@include('footer')

