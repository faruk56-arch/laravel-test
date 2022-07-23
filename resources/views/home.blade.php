@include('header')

<section class="container mb-5 pb-5 working-section">
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-4 mb-4 mb-lg-0">
            <div class="shadow h-100 p-4 bg-white rounded d-flex flex-column justify-content-between">
                <div class="text-center">
                    <img src="/images/home/research-icon.svg" class="mb-4">
                    <h2 class="h5 text-center mb-4"><strong>{{ trans('messages.besoin_de_la_meilleure_pice_dtache') }}</strong></h2>
                    <p class="mb-4 text-left">{{ trans('messages.fatigu_de_vous_parpiller_sur_les_sites_de_petites') }}</p>
                </div>
                <a href="/{{ app()->getLocale() }}/search/model" class="btn btn-primary btn-lg d-block text-center py-3">{{ trans('messages.je_vous_confie_ma_recherche') }}</a>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 mb-4 mb-lg-0">
            <div class="shadow h-100 p-4 bg-white rounded d-flex flex-column justify-content-between">
                <div class="text-center">
                    <img src="/images/home/sell-icon.svg" class="mb-4">
                    <h2 class="h5 text-center mb-4"><strong>{{ trans('messages.vous_avez_des_pices_vendre') }}</strong></h2>
                    <p class="mb-4 text-left">{{ trans('messages.diffusez_vos_articles_rapidement_et_simplement_not') }}</p>
                </div>
                <a href="/{{ app()->getLocale() }}/je-vends-des-pieces" class="btn btn-primary btn-lg d-block text-center py-3">{{ trans('messages.je_vends_mes_pices') }}</a>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="shadow h-100 p-4 bg-white rounded d-flex flex-column justify-content-between">
                <div class="text-center">
                    <img src="/images/home/refresh-icon.svg" class="mb-4">
                    <h2 class="h5 text-center mb-4"><strong>{{ trans('messages.votre_pice_dtache_est_endommage') }}</strong></h2>
                    <p class="mb-4 text-left">{{ trans('messages.trouvez_les_meilleures_offres_de_rnovation_plutt_q') }}</p>
                </div>
                <a href="/{{ app()->getLocale() }}/renovation" class="btn btn-primary btn-lg d-block text-center py-3">{{ trans('messages.je_fais_rnover') }}</a>
            </div>
        </div>
    </div>
</section>

<div id="app">
    <section class="bg-waves avantages mt-0 pt-0 mt-md-5 pt-md-5" id="avantages">
        <div class="container avantages-wrapper">
            <h2 class="text-center"><strong>{{ trans('messages.les_produits_phares_du_moment') }}</strong></h2>
            <p class="lead text-center mt-3 text-blue">{{ trans('messages.dcouvrez_quelques_exemples_de_pices_proposes_sur_n') }} </p>

            <div class="row px-3 px-md-0 py-2 py-md-5 justify-content-between">

                <div class="product-item bg-white rounded shadow p-2 py-md-4 px-md-3 mb-4 d-flex flex-column justify-content-between" v-for="(product, index) in products" v-if="(index + 1) / productsPage <= 10">

                    <a :id="'product'+index" :href="'/{{ app()->getLocale() }}/product/' + product.id" target="_blank">
                        <object type="image/png" class="mb-3" :data="product.img[0]">
                            <img class="mb-3" src="/images/home/head-home-illustration.png" alt="">
                        </object>
                        <p class="mb-0 text-blue"><strong>@{{ translation('name', product.part) }}</strong></p>
                        <p class="mb-2 text-blue">{{ __('messages.for') }} @{{ product.modele[0].brand.name + ' ' + product.modele[0].name }}</p>

                            <span v-if="product.condition == 12" class="badge badge-pill rounded-pill badge-success px-3 py-1 mb-3">{{ trans('messages.tat_neuf') }}</span>

                            <span v-if="product.condition == 13" class="badge badge-pill rounded-pill badge-success px-3 py-1 mb-3">{{ trans('messages.tat_trs_bon') }}</span>

                            <span v-if="product.condition == 14" class="badge badge-pill rounded-pill badge-warning px-3 py-1 mb-3">{{ trans('messages.tat_bon') }}</span>

                            <span v-if="product.condition == 15" class="badge badge-pill rounded-pill badge-warning px-3 py-1 mb-3">{{ trans('messages.tat_satisfaisant') }}</span>

                            <span v-if="product.condition == 16" class="badge badge-pill rounded-pill badge-danger px-3 py-1 mb-3">{{ trans('messages.tat_rnover') }}</span>

                    </a>
                    <a :href="'/{{ app()->getLocale() }}/product/' + product.id" target="_blank" class="d-none btn btn-outline-secondary btn-sm d-md-block">{{ trans('messages.consulter') }}</a>
                </div>

                <div class="col-12 text-center mt-4" v-if="productsPage < 3 && products.length > 10">
                    <p class="lead text-blue cursor-p" @click="loadMore('product')"><u><span>{{ trans('messages.voir_plus_darticles') }}</span> <i class="far fa-chevron-down"></i></u></p>
                </div>
                <div v-else class="col-12 text-center py-4">
                    <h3 class="text-blue mb-4 h3"><strong>{!! trans('messages.nhsitez_plus_lancezvous') !!}</strong></h3>
                    <a href="/{{ app()->getLocale() }}/search/model" class="btn btn-primary btn-lg text-center mx-2 px-4 py-3">{{ trans('messages.je_fais_ma_propre_recherche') }}</a>
                    <a href="/{{ app()->getLocale() }}/je-vends-des-pieces" class="btn btn-secondary mx-2 btn-lg text-center px-4 py-3">{{ trans('messages.je_vends_mes_pices') }}</a>
                </div>
            </div>

        </div>
    </section>


    <section class="py-5 mb-5 recently-found">

    <div class="container">
        <div class="row">
            <div class="col-12 mb-0 mb-md-5">
                <h2 class="text-center text-gold"><strong>{{ trans('messages.nos_utilisateurs_recherchent') }}</strong></h2>
                <p class="lead text-center mt-3 text-blue">{{ trans('messages.dcouvrez_quelques_exemples_de_pices_recherches_sur') }}</p>
            </div>

            <div class="col-5 d-none d-lg-block">
                <div class="illustration-found">
                    <img src="/images/home/chasseur.png" class="mr-n4">
                </div>
            </div>
            <div class="col-12 col-lg-7 px-lg-0">
                <div class="row">
                    <div class="col-6 col-sm-6 col-lg-4 mb-4" v-for="(research, index) in researches" v-if="(index + 1) / researchPage <= 6">
                        <div class="research-item bg-white h-100 rounded shadow p-2 py-md-4 px-md-3 d-flex flex-column justify-content-between">
                            <a :href="'/{{ app()->getLocale() }}/research/' + research.id" target="_blank" class="text-center" :id="'research'+index">
                                <object class="w-75 mb-3" type="image/png" :data="'/images/Cars/tn/' + research.modele.modele.img + '.png'">
                                    <img src="/images/Cars/tn/emptycar.png" class="w-75 mb-3">
                                </object>
                                <p class="mb-0 text-blue text-left"><strong>@{{ translation('name', research.part) }} {{ __('messages.for') }} @{{ research.modele.modele.brand.name + ' ' + research.modele.modele.name  }} {{ __('messages.from') }} @{{ research.modele.year  }}</strong></p>
                                <p class="{{-- mb-2 --}} mb-0 text-blue text-left" v-if="research.user && research.user.country">
                                    <img :src="'/images/flags/'+research.user.country.code+'.svg'" class="research-item-flag mr-1">
                                    @{{ research.user.country.name }}
                                </p>
                            </a>
                            <a :href="'/{{ app()->getLocale() }}/research/' + research.id" target="_blank" class="btn btn-outline-secondary btn-sm d-none d-md-block mt-md-2">{{ trans('messages.consulter') }}</a>

                            <div class="dropdown">
                                <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="cursor-p d-inline-block p-2">
                                    <i class="far lead fa-share-alt"></i>
                                </span>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a id="copy" @click="copy(research.id)" class="dropdown-item cursor-p"><i
                                            class="fas fa-fw fa-share-alt fa-fw"></i> <span>{{ trans('messages.copier_le_lien') }}</span></a>
                                    <a id="is_copy" @click="copy(research.id)" style="display: none;" class="text-success dropdown-item cursor-p"><i
                                            class="fas fa-fw fa-check fa-fw"></i> <span>{{ trans('messages.lien_copi') }}</span></a>
                                    <a class="dropdown-item" :href="'https://www.facebook.com/sharer.php?u={{env('APP_URL')}}/{{ app()->getLocale() }}/research/' + research.id"><i class="fab fa-fw fa-facebook"></i> Facebook</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-4" v-if="researchPage < 3 && researches.length > 6">
                    <p class="lead text-blue cursor-p" @click="loadMore('research')"><u><span>{{ trans('messages.voir_plus_de_recherches') }}</span> <i class="far fa-chevron-down"></i></u></p>
                </div>
                <div v-else class="text-center py-4">
                    <h3 class="text-blue mb-4 h3"><strong>{!! trans('messages.nhsitez_plus_lancezvous') !!}</strong></h3>
                    <a href="/{{ app()->getLocale() }}/search/model" class="btn btn-primary btn-lg text-center mx-2 px-4 py-3">{{ trans('messages.je_fais_ma_propre_recherche') }}</a>
                    <a href="/{{ app()->getLocale() }}/je-vends-des-pieces" class="btn btn-secondary mx-2 btn-lg text-center px-4 py-3">{{ trans('messages.je_vends_mes_pices') }}</a>
                </div>
            </div>
        </div>
    </div>

</section>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
    let app = new Vue({
        name: 'root',
        el: '#app',
        data() {
            return {
                researchPage: 1,
                researches: {!! json_encode($researches)  !!},
                productsPage: 1,
                products: {!! json_encode($products)  !!}
            }
        },
        methods: {
            translation(field, model, translationField = 'translation') {
                const langs = [
                    'fr_FR',
                    'en_EN',
                    'de_DE'
                ]
                const lang = "{{ \App\Actions\Translations\CreateTranslation::language() }}"
                const otherLangs = langs.filter(el => el !== lang)
                if (!model[translationField] && !model[translationField][field]) {
                    return model[field] ? model[field] : ''
                }
                return model[translationField][field][lang] || model[translationField][field][otherLangs[0]] || model[translationField][field][otherLangs[1]]
            },
            copy(id) {
                let text = "{{config('app.url')}}/{{ app()->getLocale() }}/research/" + id;
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
            },
            loadMore(content) {
                var el
                if(content == "product"){
                    let index = this.productsPage * 10
                    this.productsPage++;
                    el = "product"+index
                }
                if(content == "research"){
                    let index = this.researchPage * 6
                    this.researchPage++;
                    el = "research"+index
                }
                window.setTimeout(function(){
                    document.getElementById(el).scrollIntoView();
                }, 200);

            }
        }
    })
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>


@include('footer')

