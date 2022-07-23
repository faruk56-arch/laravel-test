<footer class="bg-blue pt-5 mt-md-n1 zi-5 position-relative">
    <div class="container">
        <div class="row pb-5">
            <div class="col-md-4">
                <img src="/images/logo/logo_classic_parts_finder_white.svg" alt="Classic Parts Finder" class="logo mb-4">
                <p>{{ trans('messages.loutil_de_rfrence_pour_la_recherche_la_vente_et_la') }}</p>
            </div>
            <div class="col-md-2 mt-md-4">
                <strong class="h5">{{ trans('messages.a_propos') }}</strong>
                <ul class="list-unstyled mt-3">
                    <li class="pb-1">
                        <a href="/{{ app()->getLocale() }}/a-propos-de-nous" class="text-white" rel="nofollow">{{ trans('messages.qui_sommesnous') }}</a>
                    </li>
                    {{-- TODO : Faire la page --}}
                    {{-- <li class="pb-1">
                        <a href="#" class="text-white" rel="nofollow">Questions fréquentes</a>
                    </li> --}}
                    {{-- <li class="pb-1">
                        <a href="/litige" class="text-white">Déclarer un litige</a>
                    </li> --}}
                    <li class="pb-1">
                        <a href="/{{ app()->getLocale() }}/contact" class="text-white">{{ trans('messages.nous_contacter') }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-2 mt-md-4">
                <strong class="h5">{{ trans('messages.nos_services') }}</strong>
                <ul class="list-unstyled mt-3">
                    <li class="pb-1">
                        <a href="/{{ app()->getLocale() }}/search/model" class="text-white" rel="nofollow">{{ trans('messages.confier_une_recherche') }}</a>
                    </li>
                    <li class="pb-1">
                        <a href="/{{ app()->getLocale() }}/je-vends-des-pieces" class="text-white" rel="nofollow">{{ trans('messages.vendre_des_pices') }}</a>
                    </li>
                    <li class="pb-1">
                        <a href="/{{ app()->getLocale() }}/renovation" class="text-white" rel="nofollow">{{ trans('messages.rnover_une_pice') }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-2 mt-md-4">
                <strong class="h5">{{ trans('messages.mon_compte') }}</strong>
                <ul class="list-unstyled mt-3">
                    @if (!Auth::check())
                        <li class="pb-1">
                            <a href="/{{ app()->getLocale() }}/newAccount" class="text-white" rel="nofollow">{{ trans('messages.inscription') }}</a>
                        </li>
                        <li class="pb-1">
                            <a href="/{{ app()->getLocale() }}/login" class="text-white" rel="nofollow">{{ trans('messages.connexion') }}</a>
                        </li>
                    @else
                        <li class="pb-1">
                            <a href="/{{ app()->getLocale() }}/finder/dashboard" class="text-white" rel="nofollow">{{ trans('messages.tableau_de_bord') }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="col-md-2 mt-md-4">
                <ul class="list-unstyled d-flex py-2">
                    <li class="px-3 font-weight-normal">
                        <a href="https://www.facebook.com/Classic-Parts-Finder-103525547820241" target="_blank" rel="noopener" aria-label="{{ trans('messages.lien_vers_la_page_facebook_classic_parts_finder') }}">
                            <i class="fab fa-facebook-f text-white fa-2x"></i>
                        </a>
                    </li>
                    <li class="px-3 font-weight-normal">
                        <a href="https://www.instagram.com/classic_parts_finder/" target="_blank" rel="noopener" aria-label="{{ trans('messages.lien_vers_la_page_instagram_classic_parts_finder') }}">
                            <i class="fab fa-instagram text-white fa-2x"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-white footer-bottom border-top py-4 d-flex justify-content-between">
            <p class="mb-0">
                <small>
                    © <?php echo date('Y'); ?> {{ __('messages.all_right_reserved') }}
                </small>
            </p>
            <ul class="list-unstyled d-flex mb-0">
                <li class="px-2">
                    <a href="/{{ app()->getLocale() }}/conditions-generales-d-utilisation/#privacy-policy" class="text-white"><small>{{ trans('messages.politiques_de_confidentialit') }}</small></a>
                </li>
                <li class="px-2">
                    <a href="/{{ app()->getLocale() }}/conditions-generales-d-utilisation" class="text-white"><small>{{ trans('messages.conditions_gnrales_dutilisation') }}</small></a>
                </li>
                <li class="pl-2">
                    <a href="/{{ app()->getLocale() }}/mentions-legales" class="text-white"><small>{{ trans('messages.mentions_lgales') }}</small></a>
                </li>
            </ul>
        </div>

    </div>
</footer>

@stack('scripts')

{{--
<script>
    @if(App::environment('production'))
        window.axeptioSettings = {
        clientId: "6019577104ebba3ce2081931",
        cookiesVersion: "classic-parts-finder-base",
    };

    (function (d, s) {
        var t = d.getElementsByTagName(s)[0], e = d.createElement(s);
        e.async = true;
        e.src = "//static.axept.io/sdk.js";
        t.parentNode.insertBefore(e, t);
    })(document, "script");
    @endif
</script>
--}}
</body>
</html>
