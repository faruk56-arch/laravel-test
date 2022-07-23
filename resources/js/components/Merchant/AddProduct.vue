<template>
    <div class="catalog">

        <div class="bg-white shadow container-fluid py-lg-4 py-3 px-4 mb-4">

            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <a class="cursor-p pl-1 pr-3" @click="$router.go(-1)"><i class="fal fa-2x fa-arrow-left"></i></a>
                    <h1 v-if="!$route.params.id" class="h2 d-inline-block text-blue mb-0 pt-3">{{ $t('nouveau_produit') }}</h1>
                    <h1 v-else class="h2 d-inline-block text-blue mb-0 pt-lg-3 pt-2">{{ $t('edition_de_produit') }}</h1>
                </div>
                <top-nav></top-nav>
            </div>

        </div>

        <div class="container position-relative" v-if="!preview">
            <div class="" v-if="$route.params.id">
                <b-nav vertical class="card nav-edit-product py-2 mt-5">
                    <b-nav-item @click="functionforscroll('compatiblecar-wrapper')">
                        <i class="fal fa-cars fa-folder-open"></i>
                        <span class="d-inline-block pr-1">{{ $t('vehicles_compatible') }}</span>
                    </b-nav-item>
                    <b-nav-item @click="functionforscroll('category-wrapper')">
                        <i class="fal fa-tire"></i>
                        <span class="d-inline-block pr-1">{{ $t('type_de_produit_catgorie') }}</span>
                    </b-nav-item>
                    <b-nav-item @click="functionforscroll('information-wrapper')">
                        <i class="fal fa-info-circle"></i>
                        <span class="d-inline-block pr-1">{{ $t('informations_gnrales') }}</span>
                    </b-nav-item>
                    <b-nav-item @click="functionforscroll('price-wrapper')">
                        <i class="fal fa-usd-circle"></i>
                        <span class="d-inline-block pr-1">{{ $t('prix') }}</span>
                    </b-nav-item>
                </b-nav>
            </div>
            <h3 class="mb-3">
                <span v-if="product.step3.name === ''">{{ $t('nouveau_produit') }}</span>
                <span v-if="product.step3.name != ''">{{ product.step3.name }}</span>
            </h3>
            <div class="card p-4 mb-2 step1" id="compatiblecar-wrapper">
                <!-- addProduct -->
                <h4 v-if="!$route.params.id" v-b-toggle.compatiblecar class="mb-0">
                    <i class="fal fa-cars fa-folder-open"></i>
                    {{ $t('vehicles_compatible') }}
                    <i v-if="validateStep('step1')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <!-- editProduct -->
                <h4 v-else class="mb-0">
                    <i class="fal fa-cars fa-folder-open"></i>
                    {{ $t('vehicles_compatible') }}
                    <i v-if="validateStep('step1')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <b-collapse :visible="!$route.query.modeleId" id="compatiblecar" accordion="general-info"
                            :class="errors && $v.product.step1.compatibleCars.$invalid ? 'error' : ''">
                    <label class="mt-3">{{ $t('ce_produit_est_compatible_pour_les_modles') }}
                        <sup class="text-danger">*</sup></label>
                    <div class="row">
                        <autocomplete
                            id="researchModele"
                            name="researchModele"
                            :search="searchModele"
                            :get-result-value="getResultValueModele"
                            :debounce-time="1"
                            ref="autocompleteModele"
                            @submit="selectCar($event)"
                            :placeholder="$t('brand_modeles_etc')"
                            class="col"
                        >
                            <template #result="{ result, props }">
                                <li v-bind="props">
                                    <div class="media">
                                        <img v-if="result.img" class="align" :src="'/images/Cars/tn/'+result.img+'.png'"
                                             @error="result.img = null">
                                        <img v-else class="align" :src="'/images/Cars/tn/emptycar.png'">
                                        <span class="media-body">{{
                                                result.brand.name + ' - ' + result.name + ' (' + result.year_start + '-' + result.year_end + ')'
                                            }}</span>
                                        <div class="details"></div>
                                    </div>
                                </li>
                            </template>
                        </autocomplete>
                    </div>
                    <div class="row ml-n2 m-0" :key="keyModel">
                        <template v-for="(car,i) in product.step1.compatibleCars">
                            <div
                                class="card p-3 m-2 mt-3 text-center d-flex align-items-center flex-column justify-content-center cursor-p"
                                :class="{ 'card-outline': car.compatible, 'dots': !car.compatible }"
                                @click="check(i)">
                                <!--                                <span class="remove-file" v-on:click="removeFile( key )"><i class="fal fa-trash-alt bg-white rounded-circle p-2 text-danger"></i></span>-->
                                <!--                                <img v-if="car.img" class="align" :src="'/images/Cars/tn/'+car.img+'.png'" @error="car.img = null">-->
                                <!--                                <img v-else class="align" :src="'/images/Cars/tn/emptycar.png'" >-->
                                <img v-if="car.img" class="w-100 little-car px-1" :src="'/images/Cars/'+car.img+'.png'"
                                     @error="car.img = null">
                                <i v-else class="fad fa-3x mb-2 fa-steering-wheel"></i>
                                <div class="mt-2" v-if="car.brand"><b>{{
                                        car.brand.name + ' - ' + car.name + ' (' + car.year_start + '-' + car.year_end + ')'
                                    }}</b></div>
                                <i v-if="car.compatible" class="fas fa-check-circle badge-icon"></i>
                            </div>
                        </template>
                    </div>
                    <!-- <template v-if="product.step1.compatibleCars.length>0">
                        <small><em>Cliquez sur un véhicule pour le retirer de la liste</em></small><br></template> -->
                    <span v-if='!$route.params.id' :class="validateStep('step1') ?'':'disabled'"
                          class="btn bg-blue btn-sm mt-3" v-b-toggle.category>{{ $t('suivant') }}</span>
                </b-collapse>
            </div>
            <div class="card p-4 mb-2 step2" id="category-wrapper">
                <!-- addProduct -->
                <h4 v-if="!$route.params.id" v-b-toggle.category class="mb-0">
                    <i class="fal fa-tire"></i>
                    {{ $t('type_de_produit_catgorie') }}
                    <i v-if="validateStep('step2')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <!-- editProduct -->
                <h4 v-else class="mb-0">
                    <i class="fal fa-tire"></i>
                    {{ $t('type_de_produit_catgorie') }}
                    <i v-if="validateStep('step2')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <b-collapse :class="{'show d-block' : $route.params.id}" id="category" accordion="general-info">
                    <div class="row mt-3">
                        <div class="col" :class="errors && !$v.product.step2.onePartSet ? 'error' : ''">
                            <label class="mt-0">{{ $t('indiquez_le_nom_du_produit') }}
                                <sup class="text-danger">*</sup></label>
                            <autocomplete
                                id="researchPart"
                                ref="autocomplete"
                                name="researchPart"
                                :search="searchPart"
                                :get-result-value="getResultValuePart"
                                :debounce-time="1"
                                default-value=""
                                :disabled="product.step1.compatibleCars.length===0"
                                @submit="selectPart($event)"
                                :placeholder="$t('nom_du_produit')"
                                class="auto-cat-home mb-2" v-if="!product.step2.part&&!suggesting">
                                <template #result="{ result, props }">
                                    <li v-bind="props" :class="result.id === 0 ? 'border-top' : ''">
                                        <div class="media">
                                            <span class="media-body">
                                                <template v-if="result.id === 0">
                                                    <i>{{ result.translation }}</i>
                                                </template>
                                                <template v-else>{{ translation('name', result) }}</template>
                                            </span>
                                            <div class="details"></div>
                                        </div>
                                    </li>
                                </template>
                            </autocomplete>

                            <div class="input-fake input-fake-cat-auto" v-else-if="!suggesting">
                                {{ partName }}
                                <span class="cursor-p ml-2 text-danger" @click="deletePart"><i
                                    class="fas fa-times-circle"></i></span>
                            </div>

                            <a v-if="!suggesting&&!partName" class="basic text-blue cursor-p" @click="suggesting=true">{{ $t('vous_ne_trouvez_pas_votre_produit') }}</a>

                            <div v-else-if="!partName">
                                <a class="basic text-blue cursor-p" @click="suggesting=false;suggest=''"><i
                                    class="fal fa-long-arrow-left mr-2"></i>{{ $t('back') }}</a>
                                <label for="suggest"></label>
                                <input class="input-fake mt-2 input-fake-cat-auto" id="suggest"
                                       :placeholder="$t('nom_de_votre_produit')" type="text" v-model="suggest"/>
                                <p class="mb-0 mt-2 text-danger">
                                    <em><i class="fal fa-exclamation-triangle mx-1"></i>{{ $t('votre_produit_pourra_tre_diffus_aprs_la_validation') }}</em>
                                </p>
                            </div>

                        </div>
                    </div>
                    <span v-if='!$route.params.id' :class="validateStep('step2') ?'':'disabled'"
                          class="btn bg-blue btn-sm mt-3" v-b-toggle.information>{{ $t('suivant') }}</span>
                </b-collapse>
            </div>
            <div class="card p-4 mb-2 step3" id="information-wrapper">
                <!-- addProduct -->
                <h4 v-if='!$route.params.id' v-b-toggle.information class="mb-0">
                    <i class="fal fa-info-circle"></i>
                    {{ $t('informations_gnrales') }}
                    <i v-if="validateStep('step3')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <!-- editProduct -->
                <h4 v-else class="mb-0">
                    <i class="fal fa-info-circle"></i>
                    {{ $t('informations_gnrales') }}
                    <i v-if="validateStep('step3')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <b-collapse :visible="!!$route.query.modeleId" :class="{'show d-block' : $route.params.id}"
                            class="mt-3 form-sm" id="information"
                            accordion="general-info">
                    <b-form-group id="input-group-name"
                                  :class="errors && $v.product.step3.name.$invalid ? 'error' : ''">
                        <label for="input-name">
                            {{ $t('titre_nom_prcis_du_produit') }} <sup class="text-danger">*</sup>
                            <template v-if="$route.params.edit && product.step3.original_language">
                                 - Langue de l'annonce (admin uniquement)
                                <select v-model="product.step3.original_language">
                                    <option value="fr_FR">Français</option>
                                    <option value="de_DE">Allemand</option>
                                    <option value="en_EN">Anglais</option>
                                </select>
                            </template>
                        </label>
                        <b-form-input id="input-name" v-model="$v.product.step3.name.$model" required
                                      placeholder="..."></b-form-input>
                    </b-form-group>
                    <div class="row">
                        <b-form-group class="col-12 col-md" id="input-group-stock"
                                      :class="errors && $v.product.step3.stock.$invalid ? 'error' : ''">
                            <label for="input-stock">{{ $t('quantit_disponible') }} <sup class="text-danger">*</sup></label>
                            <b-form-input :state="validateState('step3', 'stock')" id="input-stock" min="1"
                                          v-model.number="$v.product.step3.stock.$model" type="number"></b-form-input>
                        </b-form-group>
                        <b-form-group class="col-12 col-md" id="input-group-condition"
                                      :class="errors && $v.product.step3.condition.$invalid ? 'error' : ''">
                            <label for="input-condition">{{ $t('etat_du_produit') }} <sup class="text-danger">*</sup></label>
                            <b-form-select id="input-condition"
                                           v-model="$v.product.step3.condition.$model">
                                <b-form-select-option :value="null">
                                    {{ $t('etat_du_produit') }}
                                </b-form-select-option>
                                <b-form-select-option v-for="state in partState" :value="state.value"
                                                      :key="state.value">{{ state.text }}
                                </b-form-select-option>
                            </b-form-select>

                            <span v-b-modal.guide-des-etats class="text-secondary d-block mt-1"><small><i class="fal fa-book"></i> <u>{{ $t('states_guide') }}</u></small></span>

                            <modal-state></modal-state>

                        </b-form-group>
                        <b-form-group class="col-12 col-md" id="input-group-type"
                                      :class="errors && $v.product.step3.type.$invalid ? 'error' : ''">
                            <label for="input-type">{{ $t('typologie_de_la_produit') }} <sup class="text-danger">*</sup></label>
                            <b-form-select id="input-type"
                                           v-model="$v.product.step3.type.$model">
                                <b-form-select-option :value="null">
                                    {{ $t('typologie_de_la_produit') }}
                                </b-form-select-option>
                                <b-form-select-option v-for="type in partType" :value="type.value" :key="type.value">
                                    {{ type.text }}
                                </b-form-select-option>
                            </b-form-select>

                            <span v-b-modal.guide-des-typologie class="text-secondary d-block mt-1"><small><i class="fal fa-book"></i> <u>{{ $t('part_type_guide') }}</u></small></span>

                            <modal-origin></modal-origin>

                        </b-form-group>
                        <b-form-group class="col-12 col-md" id="input-group-manufacturer" :label="$t('fabricant')"
                                      label-for="input-manufacturer">
                            <b-form-input id="input-manufacturer" v-model="$v.product.step3.manufacturer.$model"
                                          :placeholder="$t('fabricant')"></b-form-input>
                        </b-form-group>
                        <b-form-group class="col" id="input-group-reference" :label="$t('reference')"
                                      label-for="input-reference">
                            <b-form-input id="input-reference" v-model="$v.product.step3.reference.$model"
                                          :placeholder="$t('reference')"></b-form-input>
                        </b-form-group>
                    </div>
                    <div class="row">
                        <b-form-group class="col" id="input-group-intern">
                            <label for="input-intern">{{ $t('rfrence_interne_votre_stock_invisible_pour_lachete') }}</label>
                            <b-form-input id="input-intern" min="1" v-model="$v.product.step3.intern.$model"
                                          type="text"></b-form-input>
                        </b-form-group>
                    </div>
                    <b-form-group id="input-group-description"
                                  :class="errors && $v.product.step3.description.$invalid ? 'error' : ''">
                        <label for="input-description">{{ $t('merci_de_prciser_du_mieux_possible_les_spcificits') }}
                            <sup class="text-danger">*</sup></label>
                        <b-form-textarea id="input-description" class="pt-3" rows="5"
                                         v-model="$v.product.step3.description.$model" required
                                         :placeholder="$t('cette_pice_convient_aux_modles_dans_son_emballage')"></b-form-textarea>
                    </b-form-group>
                    <div class="row">
                        <div class="col"
                             :class="errors && (product.step3.images && product.step3.images.length === 0) ? 'error' : ''">
                            <label class="px-0 d-block">{{ $t('maximiser_vos_chances_de_vendre_en_proposant_plusi') }}
                                <sup class="text-danger">*</sup>
                                <small><em>(Maximum 10 images)</em></small></label>
                            <label for="images" class="drop-img py-4 text-blue text-center w-100"
                                   v-if="product.step3.images&&product.step3.images.length<10">
                                <p class="lead mb-0 font-weight-normal">{{ $t('dposez_les_photos_de_votre_produit_ici') }}</p>
                                <p class="mb-0">{{ $t('or_if_prefer') }}</p>
                                <button @click="$refs.files.click()" class="btn bg-grey" id="images">{{ $t('slectionnez_les_photos') }}
                                </button>
                            </label>
                            <input class="d-none" type="file" id="files" ref="files" multiple @change="handleFiles()"
                                   accept="image/*"/>

                            <draggable v-model="product.step3.images" tag="ul" class="list-unstyled drop-img-list" @end="keyPics+=1"
                                       v-if="product.step3.images&&product.step3.images.length != 0">
                                <li class="file my-2" v-for="(file, key) in product.step3.images">
                                    <div class="file-listing">
                                        <img :key="keyPics" :src="file.url" :style="{minWidth : ((file.orientation%180)!=0 ? '115px':'') ,maxWidth : ((file.orientation%180)!=0 ? '115px':''), width : ((file.orientation%180)!=0 ? '115px':'') , transform: 'rotate('+product.step3.images[key].orientation+'deg) '+((file.orientation%180)!=0 && file.dimensions.ratio > 1 ? 'translateX(-'+calcTranslate(key, 'little')+'px)':''), height:((file.orientation%180)!=0 ? calcHeight(key, 'little'):'')+'px!important'}">
                                    </div>

                                    <div class="edit-tools d-flex justify-content-center py-2 px-3 align-items-center">
                                        <span class="shadow cursor-p mx-1 rounded-circle bg-white" placement='bottom' v-on:click="removeFile(key)" v-b-tooltip.hover :title="$t('remove')"><i class="fal fa-trash-alt text-danger"></i></span>
                                        <span class="shadow rounded-circle cursor-p mx-1 bg-white" placement='bottom' @click="product.step3.images[key].orientation+=90;keyPics+=1" v-b-tooltip.hover :title="$t('rotation')"><i class="far fa-sync-alt"></i></span>
                                        <span class="shadow rounded-circle move cursor-p mx-1 bg-white" v-b-tooltip.hover :title="$t('dplacer')"><i class="fal fa-arrows"></i></span>
                                    </div>

                                </li>
                            </draggable>
                            <!-- <div v-for="(file, key) in product.step3.images">
                                <button @click="product.step3.images[key].orientation-=90;keyPics+=1">Rotation vers la
                                    gauche
                                </button>
                                <button @click="product.step3.images[key].orientation+=90;keyPics+=1">Rotation vers la
                                    droite
                                </button>
                            </div> -->

                        </div>
                    </div>
                    <span v-if='!$route.params.id' class="btn bg-blue btn-sm mt-1" v-b-toggle.price>{{ $t('suivant') }}</span>
                </b-collapse>
            </div>
            <div class="card p-4 mb-2 step4" id="price-wrapper">
                <!-- addProduct -->
                <h4 v-if='!$route.params.id' v-b-toggle.price class="mb-0">
                    <i class="fal fa-usd-circle"></i> {{ $t('prix_et_livraison') }}
                    <i v-if="validateStep('step4')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <!-- editProduct -->
                <h4 v-else class="mb-0">
                    <i class="fal fa-usd-circle"></i> {{ $t('prix_et_livraison') }}
                    <i v-if="validateStep('step4')" class="fa fa-check-circle float-right text-success"></i>
                    <i v-else class="fa fa-times-circle float-right text-danger"></i>
                </h4>
                <b-collapse :class="{'show d-block' : $route.params.id}" class="form-sm" id="price"
                            accordion="general-info">
                    <p class="mt-3"></p>
                    <div class="row">
                        <b-form-group class="col-md-6" id="input-group-regularProfit" :label="$t('pour_vous')"
                                      label-for="input-regularProfit"
                                      :class="errors && $v.product.step4.regularProfit.$invalid ? 'error' : ''">
                            <b-input-group :append="product.step4.currency">
                            <b-form-input id="input-regularProfit" step="0.01" min="0.84" max="183949.99" class="h4" aria-describedby="input-regularProfit-help"
                                          v-model="product.step4.regularProfit" type="number" required
                                          @change="calcProfitReturn" v-on:keyup="calcProfitReturn()"></b-form-input>
                            </b-input-group>
                            <b-form-text id="input-regularProfit-help" class="mt-2" v-if="product.step4.regularPrice > 1">
                                <i class="fa fa-info-circle"></i> {{ $t('la_commission_de') }} <strong>{{ product.step4.commission }} {{ product.step4.currency }}</strong> {{ $t('est_destine_couvrir_les_frais_de_recherche_et_de_t') }}
                                {{ $t('the_selling_price') }} <strong>{{ product.step4.regularPrice }} {{ product.step4.currency }}.</strong>
                            </b-form-text>
                        </b-form-group>


                        <div class="col-md-6 d-flex mt-2 mb-2">
                            <div class="form-group mb-0">
                                <label class="mb-0">{{ $t('le_prix_estil_ngociable') }}</label>
                                <div class="d-flex pl-3 mt-2">
                                    <span class="d-block pr-2 text-blue pt-2">{{ $t('non') }}</span>
                                    <b-form-checkbox switch size="lg" id="checkbox-negotiable"
                                                     v-model="$v.product.step4.negotiable.$model"
                                                     name="checkbox-negotiable">
                                    </b-form-checkbox>
                                    <span class="d-block text-blue pt-2">{{ $t('oui') }}</span>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <hr>
                        </div>
                        <div class="col-md-4 pr-0 form-group"
                             :class="errors && $v.product.step4.weight.$invalid ? 'error' : ''">
                            <label for="input-weight">{{ $t('poids_approximatif_du_produit') }}<sup class="text-danger">*</sup></label>
                            <b-input-group append="Kg">
                                <b-form-input :change="validateState('step4', 'weight')" type="number" id="input-weight" aria-describedby="input-weight-help"
                                      v-model="$v.product.step4.weight.$model" min="0.1" step="0.1"></b-form-input>
                            </b-input-group>
                            <b-form-text id="input-weight-help">
                                {{ $t('pensez_lemballage_arrondir_au_dessus') }}
                            </b-form-text>

                        </div>
                        <div class="col-md-4 pr-0 form-group"
                             :class="errors && $v.product.step4.averagePreparationTime.$invalid ? 'error' : ''">
                            <label for="input-averagePreparationTime">{{ $t('dlai_avant_remise_au_transporteur') }}</label>

                            <b-input-group append="jours">
                            <b-form-input :change="validateState('step4', 'averagePreparationTime')" type="number"
                                          id="input-averagePreparationTime"
                                          v-model="$v.product.step4.averagePreparationTime.$model" min="1"
                                          step="1"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="pr-0 col-md-4 form-group" :class="errors && $v.product.step4.delivery_option.$invalid ? 'error' : ''">
                            <label>{{ $t('mode_de_transport_du_produit') }}</label>
                                <b-form-select v-model="$v.product.step4.delivery_option.$model">
                                    <b-form-select-option disabled :value="null">{{ $t('veuillez_slectionner_une_option') }}</b-form-select-option>
                                    <b-form-select-option :value="0">{{ $t('envoi_postal') }}</b-form-select-option>
                                    <b-form-select-option :value="1">{{ $t('remise_en_main_propre_et_envoi_postal') }}</b-form-select-option>
                                    <b-form-select-option :value="2">{{ $t('remise_en_main_propre_uniquement') }}</b-form-select-option>
                                </b-form-select>
                        </div>
                        <div class="col-md-4 pr-0 form-group"
                             :class="errors && $v.product.step4.height.$invalid ? 'error' : ''">
                            <label for="dimension-height">{{ $t('hauteur') }}</label>
                            <b-input-group append="cm">
                                <b-form-input :change="validateState('step4', 'height')" type="number" id="input-weight"
                                          v-model="$v.product.step4.height.$model" min="1" step="1"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="col-md-4 pr-0 form-group"
                             :class="errors && $v.product.step4.width.$invalid ? 'error' : ''">
                            <label for="dimension-width">{{ $t('largeur') }}</label>
                            <b-input-group append="cm">
                                <b-form-input :change="validateState('step4', 'width')" type="number" id="input-width"
                                          v-model="$v.product.step4.width.$model" min="1" step="1"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="col-md-4 pr-0 form-group"
                             :class="errors && $v.product.step4.depth.$invalid ? 'error' : ''">
                            <label for="dimension-depth">{{ $t('longueur') }}</label>
                            <b-input-group append="cm">
                                <b-form-input :change="validateState('step4', 'depth')" type="number" id="input-depth"
                                          v-model="$v.product.step4.depth.$model" min="1" step="1"></b-form-input>
                            </b-input-group>
                        </div>
                        <div class="col-12">
                            <b-form-text id="sent-help">
                                {{ $t('les_frais_lis_une_livraison_sont_la_charge_de_lach') }}
                            </b-form-text>
                        </div>

                    </div>
                </b-collapse>
            </div>
            <div class="row pb-5 pt-2 pl-md-3">
                <div class="col-md-9 mb-2 mb-md-0">
                    <button class="btn btn-block btn-primary btn-lg"
                        :class="!isOneSelected||isSubmitting?'disabled':''" @click="send">{{ $t('aperu_et_diffusion') }}
                    </button>
                </div>
                <div class="col-md-3" v-if="!$route.params.edit">
                    <div class="btn btn-block bg-dark-grey btn-lg px-0"
                         :class="!isOneSelected||isSubmitting?'disabled':''" @click="saveProduct(false)"
                         :key="2+keySubmit">
                        <template v-if="isSubmitting">
                            <span class="loader"></span>
                        </template>
                        <template v-else>
                            <i class="fal fa-save"></i> {{ $t('brouillon') }}
                        </template>
                    </div>
                </div>
                <div class="col-md-3" v-else>
                    <div class="btn btn-block bg-dark-grey btn-lg px-0"
                         :class="!isOneSelected||isSubmitting?'disabled':''" @click="saveProductAdmin(false)"
                         :key="2+keySubmit">
                        <template v-if="isSubmitting">
                            <span class="loader"></span>
                        </template>
                        <template v-else>
                            <i class="fal fa-save"></i> {{ $t('brouillon') }}
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="container pt-3 pb-5">
            <div class="card mb-3 px-5 py-4 offset-md-2 col-md-8">

                <a @click="preview=false" class="cursor-p">
                    <i class="fal fa-long-arrow-left pr-1"></i>
                    {{ $t('back') }}
                </a>
                <div class="add-product-preview details-content-wrapper">

                    <p class="h3 mb-2 text-blue mt-2"><strong>{{ product.step3.name }}</strong></p>
                    <p>{{ getStatusText(product.step3.type) }}

                        <template>

                        </template>
                        <b-badge class="text-white badge-lg" pill :variant="getVariantBadgeColor(product.step3.condition)">{{ $t('state') }} {{ getStatusText(product.step3.condition).toLowerCase() }}</b-badge>

                    </p>

                    <div class="details-content">


                        <div class="img-wrapper mb-3">
                            <div v-if="product.step3.images.length == 1" @click="zoom(0)" :key="0" class="d-flex justify-content-center align-items-center h-100 w-100 overflow-hidden">
                                <img :src="product.step3.images[0].url"   :class="{'w-100':product.step3.images[0].dimensions.ratio<1&&!product.step3.images[0].dimensions.height>250,'h-100':(product.step3.images[0].dimensions.ratio>1||product.step3.images[0].dimensions.height>250)&&product.step3.images[0].orientation%180==0}" :style="{
                                maxWidth : ((product.step3.images[0].orientation%180)!=0 ? '250px':''),
                                width : ((product.step3.images[0].orientation%180)!=0&&product.step3.images[0].dimensions.ratio<1  ? '250px':'') ,
                                transform:'rotate('+product.step3.images[0].orientation+'deg) ',
                                 height:((product.step3.images[0].orientation%180)!=0 ? calcHeight(0, 'large'):'')+'px!important'}">
<!--                                <zoom-on-hover class="h-100 w-100" :img-normal="product.step3.images[0].url"-->
<!--                                               :scale="1.2"></zoom-on-hover>-->
                            </div>

                            <b-carousel
                                v-model="slide"
                                :interval="0"
                                controls
                                indicators
                                background="#ababab"
                                class="h-100"
                                img-width="1024"
                                img-height="100%"
                                @sliding-start="onSlideStart"
                                @sliding-end="onSlideEnd"
                                v-else
                            >

                                <b-carousel-slide v-for="(i, imageIndex) in product.step3.images" :key="imageIndex">
                                    <template v-slot:img>
                                        <div class="d-flex justify-content-center align-items-center h-100 w-100 overflow-hidden" @click="zoom(imageIndex)" :key="imageIndex">
                                            <img :class="{'w-100':i.dimensions.ratio<1&&!i.dimensions.height>250,'h-100':(i.dimensions.ratio>1||i.dimensions.height>250)&&i.orientation%180==0}" :src="product.step3.images[imageIndex].url"
                                                 :style="{
                                                 maxWidth : ((i.orientation%180)!=0 ? '250px':''),
                                                 width : ((i.orientation%180)!=0&&i.dimensions.ratio<1 ? '250px':'') ,
                                                 transform:'rotate('+i.orientation+'deg)' ,
                                                 height:((i.orientation%180)!=0 ? calcHeight(imageIndex, 'large'):'')+'px!important'}">
<!--                                            <zoom-on-hover class="h-100 w-100" :img-normal="i.url" :scale="1.2"-->
<!--                                            :style="{minWidth : ((i.orientation%180)!=0 ? '250px':'') ,maxWidth : ((i.orientation%180)!=0 ? '250px':''), width : ((i.orientation%180)!=0 ? '250px':'') , transform:'rotate('+i.orientation+'deg) '+((i.orientation%180)!=0 && i.dimensions.ratio > 1 ? 'translateX(-'+calcTranslate(imageIndex, 'large')+'px)':''), height:((i.orientation%180)!=0 ? calcHeight(imageIndex, 'large'):'')+'px!important'}"></zoom-on-hover>-->
<!--                                       -->
                                        </div>
                                    </template>
                                </b-carousel-slide>

                            </b-carousel>

                        </div>


                        <div class="pb-4">
                            <table class="table text-blue table-striped py-3">
                                <tbody>

                                    <tr>
                                        <td class="border-0" style="white-space: pre-line;">
                                            <strong>{{ $t('description') }} </strong>
                                            {{ product.step3.description }}
                                        </td>
                                    </tr>

                                    <tr v-if="product.step3.reference">
                                        <td class="border-0">
                                            <strong class="d-inline-block mr-2">{{ $t('rfrence_du_produit') }}</strong>
                                            {{ product.step3.reference }}
                                        </td>
                                    </tr>
                                    <tr v-if="product.step3.manufacturer">
                                        <td class="border-0">
                                            <strong class="d-inline-block mr-2">{{ $t('fabricant') }}</strong><br>
                                            {{ product.step3.manufacturer }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border-0">
                                            <p class="mb-1"><strong>{{ $t('a_propos_du_vendeur') }}</strong></p>

                                            <template v-if="$auth.user().role!=='admin'">
                                                {{ $t('seller') }} {{ $auth.user().merchant.merchant_type }} -
                                                {{ $auth.user().region ? $auth.user().region.name : '' }} - {{ $auth.user().country.name }}
                                                <span :class="'flag-icon flag-icon-'+$auth.user().country.code.toLowerCase()"></span>
                                            </template>

                                            <template v-else-if="!$route.params.edit">
                                                {{ $t('vendu_directement_par_cpf') }}
                                            </template>
                                            <template v-else>
                                                {{ $t('seller') }} {{ loadedMerchant.merchant_type }} -
                                                {{ loadedMerchant.user[0].region ? loadedMerchant.user[0].region.name : '' }} - {{ loadedMerchant.user[0].country.name }}
                                                <span :class="'flag-icon flag-icon-'+loadedMerchant.user[0].country.code.toLowerCase()"></span>
                                            </template>

                                        </td>
                                    </tr>
<!--                                    <tr v-if="product.step4.averagePreparationTime">-->
<!--                                        <td class="border-0">-->
<!--                                            <strong class="d-inline-block mr-2">Temps moyen de préparation</strong>-->
<!--                                            {{ product.step4.averagePreparationTime }} jours-->
<!--                                        </td>-->
<!--                                    </tr>-->
                                    <tr>
                                        <td class="border-0">
                                            <strong class="d-inline-block mr-2">{{ $t('poids_du_produit') }}</strong>
                                            {{ product.step4.weight }} Kg
                                        </td>
                                    </tr>

                                    <tr v-if="product.step4.width && product.step4.height && product.step4.depth">
                                        <td class="border-0">
                                            <strong class="d-inline-block mr-2">{{ $t('dimensions_du_produit') }}</strong>
                                            {{ product.step4.width }}cm x {{ product.step4.height }}cm x {{ product.step4.depth }}cm
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border-0">
                                            <strong class="d-inline-block mr-2">{{ $t('prix') }}</strong>{{ $t('hors_frais_de_ports') }}
                                            {{ product.step4.regularPrice }} {{product.step4.currency }}
                                            {{ product.step4.negotiable ? '(Négociable via Obvy)' : '(Non négociable)' }}
                                            <br>
                                            <small>
                                            {{ $t('la_commission_de') }} <strong>{{ product.step4.commission }} {{ product.step4.currency }}</strong> {{ $t('est_destine_couvrir_les_frais_de_recherche_et_de_t') }}
                                            {{ $t('the_selling_price') }} <strong>{{ product.step4.regularPrice }} {{ product.step4.currency }}.</strong>
                                            </small>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="border-0">
                                            <p class="mb-1"><strong>{{ $t('expdition') }}</strong></p>
                                            {{ deliveryMethod }}<br>
                                            <template v-if="product.step4.averagePreparationTime">{{ $tc('temps_moyen_de_prparation_jours', product.step4.averagePreparationTime, {'0': product.step4.averagePreparationTime}) }}</template><br>
                                            <span v-if="product.step4.delivery_option !== 2">
                                            <i>{{ $t('frais_de_port_en_sus_calculer_selon_le_mode_de_liv') }}</i>
                                        </span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="row mb-4">
                    <div class="col">
                        <template v-if="!$route.params.edit">
                            <div class="row" v-if="!$route.params.id">

                                <div class="col mb-3">
                                    <div class="btn btn-block bg-gold btn-lg"
                                         :class="$v.product.$anyError||isSubmitting?'disabled':''"
                                         @click="saveProduct(true)" :key="1+keySubmit">
                                        <template v-if="isSubmitting">
                                            {{ $t('en_cours_denvoi') }}
                                        </template>
                                        <template v-else>
                                            {{ $t('diffuser_le_produit') }}
                                        </template>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="btn btn-block bg-dark-grey btn-lg px-0"
                                         :class="!isOneSelected||isSubmitting?'disabled':''" @click="saveProduct(false)"
                                         :key="2+keySubmit">
                                        <template v-if="isSubmitting">
                                            <span class="loader"></span>
                                        </template>
                                        <template v-else>
                                            <i class="fal fa-save"></i> {{ $t('brouillon') }}
                                        </template>
                                    </div>
                                </div>

                            </div>

                            <!--                    <div v-if="$v.product.$anyError" class="btn btn-block btn-disabled" @click="saveProduct()">Formulaire incomplet ou contenant des erreurs</div>-->
                            <div class="row" v-else>
                                <div class="col mb-3 mb-md-0">
                                    <div class="btn btn-block bg-gold btn-lg"
                                         :class="$v.product.$anyError||isSubmitting?'disabled':''"
                                         @click="saveProduct(true)" :key="1+keySubmit">
                                        <template v-if="isSubmitting">
                                            {{ $t('en_cours_denvoi') }}
                                        </template>
                                        <template v-else>
                                            {{ $t('diffuser_le_produit') }}
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="btn btn-block bg-dark-grey btn-lg"
                                         :class="!isOneSelected||isSubmitting?'disabled':''" @click="saveProduct(false)"
                                         :key="2+keySubmit">
                                        <template v-if="isSubmitting">
                                            <span class="loader"></span>
                                        </template>
                                        <template v-else>
                                            <i class="fal fa-save"></i> {{ $t('brouillon') }}
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="row">
                                <div class="col mb-3">
                                    <div class="btn btn-block bg-gold btn-lg"
                                         :class="$v.product.$anyError||!product.step2.part||isSubmitting?'disabled':''"
                                         @click="saveProductAdmin(true)">
                                        <template v-if="isSubmitting">
                                            {{ $t('en_cours_denvoi') }}
                                        </template>
                                        <template v-else>
                                            {{ $t('diffuser_le_produit') }}
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

import {mapActions, mapGetters} from "vuex";
import {validationMixin} from "vuelidate";
import {status} from "../../mixins/status.js"
import {required} from "vuelidate/lib/validators";
import integer from "vuelidate/lib/validators/integer";
import minValue from "vuelidate/lib/validators/minValue";
import minLength from "vuelidate/lib/validators/minLength";
import draggable from 'vuedraggable';
import ModalOrigin from "../Shared/ModalOrigin";
import ModalState from "../Shared/ModalState";
import profitCalculation from "../../mixins/profitCalculation";

const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    name: 'addProduct',
    mixins: [validationMixin, status, profitCalculation],
    validations: {
        product: {

            step1: {
                compatibleCars: {
                    required,
                    oneSet() {
                        return this.isOneSelected;
                    },
                },
            },
            step2: {
                onePartSet() {
                    return this.product.step2.part || this.suggest !== '';
                }
            },
            step3: {
                stock: {
                    integer,
                    minValue: minValue(1),
                    minLength: minLength(1)
                },
                name: {
                    required
                },
                description: {
                    required
                },
                condition: {
                    required
                },
                type: {
                    required
                },
                intern: {},
                reference: {},
                images: {
                    oneSet() {
                        return this.product.step3.images != null && this.product.step3.images.length > 0;
                    },
                },
                manufacturer: {}
            },
            step4: {
                currency: {},
                regularPrice: {
                    required,
                    minValue: minValue(2)
                },
                weight: {
                    required,
                    minValue: minValue(0.1)
                },
                regularProfit: {
                    required,
                    minValue: minValue(0.84)
                },
                commission: {},
                negotiable: {
                    required
                },
                delivery_option: {
                    required
                },
                minPrice: {},
                minProfit: {},
                minCommission: {},
                shippingFees: {},
                shippingFeesAbroadZone: {},
                averagePreparationTime: {},
                height: {},
                width: {},
                depth: {}
            }
        }
    },
    components: {
        draggable, TopNav, ModalOrigin, ModalState
    },
    data() {
        return {
            errors: false,
            imagePreview: [],
            modelSelected: null,
            isOneSelected: false,
            partName: '',
            sliding: false,
            slide: 0,
            preview: false,
            keySubmit: 0,
            loadedMerchant:null,
            keyPics: 0,
            suggest: '',
            custom: !!this.$route.query.custom,
            suggesting: false,
            product: {
                step1: {
                    compatibleCars: [],
                },
                step2: {
                    part: null,
                },
                step3: {
                    name: null,
                    description: null,
                    reference: null,
                    condition: null,
                    type: null,
                    stock: 1,
                    manufacturer: null,
                    original_language:null,
                    images: [],
                    intern: '',
                },
                step4: {
                    currency: '€',
                    regularPrice: '',
                    priceForMerchant: '',
                    regularProfit: '',
                    delivery_option: null,
                    weight: null,
                    commission: '',
                    commissionMerchant: '',
                    negotiable: false,
                    minPrice: '',
                    minProfit: '',
                    minCommission: '',
                    shippingFees: '',
                    shippingFeesAbroadZone: '',
                    averagePreparationTime: null,
                    height: null,
                    width: null,
                    depth: null
                }
            },
            parts: [],
            models: [],
            categories: [],
            keyModel: 0,
            isSubmitting: false,
            searchPartOutput: [],
        }
    },
    created() {
        if (!this.isProductLoaded && this.$route.params.id) {
            this.fetchProduct(this.$route.params.id).then(res => {
                let product = res.data;
                if (product.modele) {
                    this.product.step1.compatibleCars = [...product.modele];
                }
                this.product.step1.compatibleCars.forEach(c => {
                    c.compatible = true;
                })
                if (product.part) {
                    this.product.step2.part = product.part.id;
                    this.partName = this.translation('name', product.part.category) + ' - ' + this.translation('name', product.part);
                } else if (product.suggestion) {
                    this.product.step2.part = null;
                    this.suggest = product.suggestion;
                    this.suggesting = true;
                }
                this.product.step3.name = product.original_name;
                this.product.step3.original_language = product.original_language;
                this.product.step3.description = product.original_description
                this.loadedMerchant = product.merchant;
                this.product.step3.reference = product.reference;
                this.product.step3.intern = product.intern;
                this.product.step3.condition = +product.condition;
                this.product.step3.type = +product.type;
                this.product.step3.stock = product.stock;
                this.product.step3.manufacturer = product.manufacturer;
                if (product.img) {
                    this.product.step3.images = product.img_with_index;
                    this.product.step3.images.forEach(img => {
                        img.orientation = 0;
                    })
                }
                this.product.step4.regularPrice = product.price;
                this.product.step4.minPrice = product.minPrice;
                this.product.step4.delivery_option = product.delivery_option;
                this.product.step4.weight = product.weight;
                this.product.step4.negotiable = product.negotiable === 1;
                this.product.step4.shippingFees = product.shipping_cost;
                this.product.step4.shippingFeesAbroadZone = product.shipping_cost_abroad;
                this.product.step4.averagePreparationTime = product.average_preparation_time;
                this.product.step4.height = product.height;
                this.product.step4.width = product.width;
                this.product.step4.depth = product.depth;
                this.calcProfit();
                this.calcImgSizes();
            }).then((res) => {
                this.$nextTick(() => {
                    if (this.$route.params.id) {
                        this.verifyErrors();
                    }
                })
            });
            this.isOneSelected = true;
        }
        if (this.$route.query.modeleId) {
            var app = this;
            this.fetchCar(this.$route.query.modeleId).then(res => {
                app.product.step1.compatibleCars.push(res);
                app.product.step1.compatibleCars.forEach(c => {
                    c.compatible = true;
                })
                this.isOneSelected = true;
            });
            this.fetchPart(this.$route.query.partId).then(res => {
                app.partName = this.translation('name', res.category) + ' - ' + this.translation('name', res);
            });
            this.product.step2.part = this.$route.query.partId;
            this.product.step3.reference = this.$route.query.reference;
            this.product.step3.intern = this.$route.query.intern;
        }
    },
    mounted: function () {
        if (this.$route.params.id && this.isProductLoaded && !this.$route.params.edit) {

            //let product = this.getProduct(this.$route.params.id) /* TODO : Vérifier ceci */
            this.fetchProduct(this.$route.params.id).then(res => {
                let product = res.data;
                if (product.modele) {
                    this.product.step1.compatibleCars = [...product.modele];
                }
                this.product.step1.compatibleCars.forEach(c => {
                    c.compatible = true;
                })
                if (product.part) {
                    this.product.step2.part = product.part.id;
                    this.partName = this.translation('name', product.part.category) + ' - ' + this.translation('name', product.part);
                } else if (product.suggestion) {
                    this.product.step2.part = null;
                    this.suggest = product.suggestion;
                    this.suggesting = true;
                }
                this.loadedMerchant = product.merchant;
                this.product.step3.name = product.original_name;
                this.product.step3.original_language = product.original_language;
                this.product.step3.description = product.original_description;
                this.product.step3.reference = product.reference;
                this.product.step3.intern = product.intern;
                this.product.step3.condition = product.condition;
                this.product.step3.type = product.type;
                this.product.step3.stock = product.stock;
                this.product.step3.manufacturer = product.manufacturer;
                if (product.img) {
                    this.product.step3.images = product.img_with_index;
                    this.product.step3.images.forEach(img => {
                        img.orientation = 0;
                    })
                }
                this.product.step4.regularPrice = product.price;
                this.product.step4.minPrice = product.minPrice;
                this.product.step4.negotiable = product.negotiable === 1;
                this.product.step4.delivery_option = product.delivery_option;
                this.product.step4.weight = product.weight;
                this.product.step4.shippingFees = product.shipping_cost;
                this.product.step4.shippingFeesAbroadZone = product.shipping_cost_abroad;
                this.product.step4.averagePreparationTime = product.average_preparation_time;
                this.product.step4.height = product.height;
                this.product.step4.width = product.width;
                this.product.step4.depth = product.depth;
                this.calcProfit();
                this.calcImgSizes();
                this.isOneSelected = true;
                this.$nextTick(() => {
                    if (this.$route.params.id) {
                        this.verifyErrors();
                    }
                })
            })
        }
        else if (this.isProductLoaded && this.$route.params.id && this.$route.params.edit) {
            this.fetchProduct(this.$route.params.id).then(res => {
                let product = res.data;
                if (product.modele) {
                    this.product.step1.compatibleCars = [...product.modele];
                }
                this.product.step1.compatibleCars.forEach(c => {
                    c.compatible = true;
                })
                if (product.part) {
                    this.product.step2.part = product.part.id;
                    this.partName = this.translation('name', product.part.category) + ' - ' + this.translation('name', product.part);
                } else if (product.suggestion) {
                    this.product.step2.part = null;
                    this.suggest = product.suggestion;
                    this.suggesting = true;
                }
                this.product.step3.name = product.original_name;
                this.product.step3.original_language = product.original_language;
                this.product.step3.description = product.original_description;
                this.loadedMerchant = product.merchant;
                this.product.step3.reference = product.reference;
                this.product.step3.intern = product.intern;
                this.product.step3.condition = +product.condition;
                this.product.step3.type = +product.type;
                this.product.step3.stock = product.stock;
                this.product.step3.manufacturer = product.manufacturer;
                if (product.img) {
                    this.product.step3.images = product.img_with_index;
                    this.product.step3.images.forEach(img => {
                        img.orientation = 0;
                    })
                    this.imagePreview = Object.values(product.img);
                }
                this.product.step4.regularPrice = product.price;
                this.product.step4.minPrice = product.minPrice;
                this.product.step4.delivery_option = product.delivery_option;
                this.product.step4.weight = product.weight;
                this.product.step4.negotiable = product.negotiable === 1;
                this.product.step4.shippingFees = product.shipping_cost;
                this.product.step4.shippingFeesAbroadZone = product.shipping_cost_abroad;
                this.product.step4.averagePreparationTime = product.average_preparation_time;
                this.product.step4.height = product.height;
                this.product.step4.width = product.width;
                this.product.step4.depth = product.depth;
                this.calcProfit();
                this.calcImgSizes();
            })
            this.isOneSelected = true;
        }
        this.calcImgSizes();
        let app = this;
        var droparea;
        droparea = document.querySelector(".drop-img");
        if (droparea != null) {
            AddEventListeners();
        }

        function AddEventListeners() {
            droparea.addEventListener("dragenter", DragEnter, false);
            droparea.addEventListener("dragleave", DragLeave, false);
            droparea.addEventListener("dragover", DragOver, false);
            droparea.addEventListener("drop", Drop, false);
        }

        function DragEnter(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.add("dragging-over");
        }

        function DragOver(e) {
            e.stopPropagation();
            e.preventDefault();
        }

        function DragLeave(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.remove("dragging-over");
        }

        function Drop(e) {
            e.stopPropagation();
            e.preventDefault();
            droparea.classList.remove("dragging-over");
            var dt = e.dataTransfer;
            var files = dt.files;
            if (files.length + app.product.step3.images.length > 10) {
                alert(this.$t('max_photos'))
            } else {
                for (var i = 0; i < files.length; i++) {
                    let reader = new FileReader();
                    let index = app.product.step3.images.length;
                    reader.addEventListener("load", function () {
                        app.product.step3.images[index].url = reader.result
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(files[i].name)) {
                        let newImg = {
                            index: app.product.step3.images.length,
                            url: files[i],
                            orientation: 0,
                        }
                        app.product.step3.images.push(newImg);
                        reader.readAsDataURL(files[i]);
                        app.calcImgSizes();
                    }
                }
            }
        }

    },

    methods: {
        ...mapActions('products', ['fetchAllProducts']),
        ...mapActions('coolLightBox', ['showBox', 'setIndex']),


        zoom(index) {
            this.showBox([this.product.step3.images])
            this.setIndex(index)
        },
        getVariantBadgeColor(etat) {
            if ( etat == 12 || etat == 13) { return 'success';}
            if ( etat == 14 || etat == 15) { return 'warning';}
            if ( etat == 16 ) { return 'danger';}
        },
        calcHeight(index, size) {
            let paramsImg = this.product.step3.images[index].dimensions
            if (size == 'large') {
                if (paramsImg.ratio > 1 ) {
                    let newHeight = (270 * paramsImg.height / 1726) + 250;
                    // let newHeight=0;
                    // if (paramsImg.width<250)newHeight = 434.7;
                    // else {
                    //     newHeight = 530;
                    // }
                    return newHeight;
                }else{ return ''; }
            }else if(size == 'little'){
                if (paramsImg.ratio > 1 ) {
                    let newHeight = (83 * paramsImg.height / 1726) + 115;
                    return newHeight;
                }else{ return ''; }
            }
        },
        calcTranslate(index, size) {
            let paramsImg = this.product.step3.images[index].dimensions
            if (size == 'large') {
                if (paramsImg.ratio > 1) {
                    let newTranslate = (135 * paramsImg.height / 1726);
                    return newTranslate;
                }else{ return ''; }
            }else if(size == 'little'){
                return 0;
            }
        },
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        },
        validateState(step, name) {
            const {$dirty, $error} = this.$v.product[step][name];
            return $dirty ? !$error : null;
        },
        validateStep(step) {
            this.$v.product[step].$touch();
            return !this.$v.product[step].$anyError;

        },
        calcProfitReturn: function () {
            const obj = this.buyerPrice(this.product.step4.regularProfit)
            this.product.step4.regularPrice = obj.price
            this.product.step4.commission = obj.commission
        },
        calcProfit: function () {
            const obj = this.sellerPrice(this.product.step4.regularPrice)
            this.product.step4.regularProfit = obj.profit
            this.product.step4.commission = obj.commission
        },
        addModel: function () {
            if (this.product.step1.compatibleCars.find(el => el.id === this.modelSelected.id))
                return
            this.product.step1.compatibleCars.push(this.modelSelected);
            this.product.step1.compatibleCars[this.product.step1.compatibleCars.length - 1].compatible = true;
            this.getCompatible();
            this.brandSelected = '0';
            this.modelSelected = '0';
            this.$refs.autocompleteModele.value = '';
            this.checkSelected();
        },
        getCompatible: function () {
            var app = this;
            axios.get('modele/' + this.modelSelected.id + '/compatible').then(function (res) {
                res.data.data.forEach(function (car) {
                        car.compatible = false;
                        app.product.step1.compatibleCars.push(car);
                    }
                )
            })
        },
        selectCar(event) {
            this.brandSelected = event.brand.id
            this.modelSelected = event;
            this.addModel();
        },
        searchModele(input) {
            return axios.get('/modele?name=' + input).then(res => {
                return res.data
            })
        },
        getResultValueModele(result) {
            return result.brand.name + ' - ' + result.name + '(' + result.year_start + '-' + result.year_end + ')'
        },
        saveProduct(send) {
            this.$v.product.$touch();
            if (this.$v.product.$anyError && send) {
                return;
            }
            this.isSubmitting = true;
            let copyProduct = Object.create(this.product);
            var app = this;
            copyProduct.step1.compatibleCars = app.product.step1.compatibleCars.filter(car => car.compatible)
            copyProduct.step1.compatibleCars = app.product.step1.compatibleCars.map(car => car.id)

            copyProduct.step1.compatibleCars = JSON.stringify(copyProduct.step1.compatibleCars);
            let orientation = copyProduct.step3.images.map(im => im.orientation);
            copyProduct.step3.orientation = JSON.stringify(orientation);
            let formData = new FormData()
            let img = Array();

            copyProduct.step3.images.forEach((image, i) => {
                if (image.file) {
                    formData.append('file[' + i + ']', image.file, i);
                    img.push(null);
                } else img.push(image.url);
            })
            if (copyProduct.step3.images.length === 0) copyProduct.step3.images = null;

            let data = {
                ...copyProduct.step1,
                ...copyProduct.step2,
                ...copyProduct.step3,
                ...copyProduct.step4,
            }

            Object.keys(data).forEach(key => formData.append(key, data[key]));

            if (img.length > 0) formData.append('img', JSON.stringify(img));

            else formData.append('img', null);
            if (!send) {
                formData.append('send', null);
            } else {
                formData.append('send', true);
            }
            if (this.suggest != '') formData.append('suggestion', this.suggest);
            else formData.append('suggestion', null);
            if (this.$route.params.id) {
                formData.append('merchant_id', this.$auth.user().merchant_id)
                formData.append('product_id', this.$route.params.id)
                this.editProduct([formData]).then((res) => app.$router.push({name: 'catalog'}).catch(() => {}))
            } else this.newProduct([formData])
                .then((res) => app.$router.push({name: 'catalog',params:{alert:this.$route.query.alert}}).catch(() => {}))

        },
        async saveProductAdmin(send) {
            this.$v.product.$touch();
            if (this.$v.product.$anyError && send) {
                return;
            }
            this.isSubmitting = true;
            let copyProduct = Object.create(this.product);
            var app = this;
            copyProduct.step1.compatibleCars = app.product.step1.compatibleCars.filter(car => car.compatible)
            copyProduct.step1.compatibleCars = app.product.step1.compatibleCars.map(car => car.id)

            copyProduct.step1.compatibleCars = JSON.stringify(copyProduct.step1.compatibleCars);
            let orientation = copyProduct.step3.images.map(im => im.orientation);
            copyProduct.step3.orientation = JSON.stringify(orientation);

            let formData = new FormData()
            let img = Array();

            copyProduct.step3.images.forEach((image, i) => {
                if (image.file) {
                    formData.append('file[' + i + ']', image.file, i);
                    img.push(null);
                } else img.push(image.url);
            })
            if (copyProduct.step3.images.length === 0) copyProduct.step3.images = null;

            let data = {
                ...copyProduct.step1,
                ...copyProduct.step2,
                ...copyProduct.step3,
                ...copyProduct.step4,
            }

            Object.keys(data).forEach(key => formData.append(key, data[key]));

            if (img.length > 0) formData.append('img', JSON.stringify(img));

            else formData.append('img', null);
            if (!send) {
                formData.append('send', null);
            } else {
                formData.append('send', true);
            }
            if (this.suggest != '') formData.append('suggestion', this.suggest);
            else formData.append('suggestion', null);
            if (this.$route.params.id) {
                // formData.append('merchant_id', this.$auth.user().merchant_id)
                formData.append('product_id', this.$route.params.id)
                await this.editProduct([formData, true])
                this.changeProductStatus([this.$route.params.id]).then(res => {
                    app.$router.push({name: 'admin-product-waiting'}).catch(() => {})
                })
            } else this.newProduct([formData])
                .then((res) => app.$router.push({name: 'catalog'}).catch(() => {}))

        },
        check(index) {
            this.product.step1.compatibleCars[index].compatible = !this.product.step1.compatibleCars[index].compatible;
            this.checkSelected();
            this.keyModel++;
        },
        async handleFiles() {
            let app = this;
            let uploadedFiles = this.$refs.files.files;
            if (uploadedFiles.length + this.product.step3.images.length > 10) {
                alert(this.$t('max_photos'))
            } else {
                for (var i = 0; i < uploadedFiles.length; i++) {
                    let newImg = {
                        index: app.product.step3.images.length,
                        file: uploadedFiles[i],
                        orientation: 0,
                    }
                    this.product.step3.images.push(newImg);
                    let reader = new FileReader();
                    let index = app.product.step3.images.length - 1;
                    reader.addEventListener("load", function () {
                        app.product.step3.images[index].url = reader.result
                        app.calcImgSizes();
                        app.keyPics += 1;
                    }.bind(app), false);
                    if (/\.(jpe?g|png|gif)$/i.test(uploadedFiles[i].name)) {
                        reader.readAsDataURL(uploadedFiles[i]);
                    }
                }
            }
            this.$refs.files.value = ""
        },
        checkSelected() {
            let bool = false;
            this.product.step1.compatibleCars.forEach(car => {
                if (car.compatible === true) bool = true;
            })
            this.isOneSelected = bool;
        },
        selectPart(event) {
            const part = event || this.searchPartOutput[0]
            if (!part) {
                return
            }
            if (part.id === 0) {
                return this.suggesting = true;
            }
            this.product.step2.part = part.id
            this.partName = this.translation('name', part.category) + ' - ' + this.translation('name', part)
        },
        deletePart() {
            this.partName = '';
            this.product.step2.part = null;
        },
        searchPart(input) {
            return axios.get(
                '/modele/' + this.product.step1.compatibleCars[0].id +
                '/part?name=' + input
            ).then(res => {
                let output = res.data
                if (input !== '') {
                    output[Object.keys(output).length + 2] = {id: 0, translation: this.$t('je_nai_pas_trouv_ma_pice')}
                }
                this.searchPartOutput = Object.values(output)
                return this.searchPartOutput
            });
        },
        removeFile(key) {
            this.product.step3.images.splice(key, 1);
        },
        getResultValuePart(result) {
            if (result.id === 0) {
                return this.translation('name', result)
            }
            return this.translation('name', result) + ' (' + this.translation('name', result.category) + ')'
        },
        functionforscroll(id) {
            var reqId = "#" + id;
            document.querySelector(reqId).scrollIntoView();
        },
        ...mapActions('products', ['newProduct', 'editProduct', 'fetchProduct']),
        ...mapActions('cars', ['fetchCar']),
        ...mapActions('admin', ['changeProductStatus']),
        ...mapActions('parts', ['fetchPart',]),
        verifyErrors() {
            const steps = {
                step1: "compatiblecar",
                step2: "category",
                step3: "information",
                step4: "price",
            }
            let first = null
            this.$v.product.$touch();
            Object.entries(this.product).forEach(([key, value]) => {
                if (this.$v.product[key].$anyError) {
                    if (!first)
                        first = key
                    this.errors = true
                    const collapse = document.getElementById(steps[key]);
                    collapse.classList.add('d-block')
                }
            })
            if (first) {
                const el = this.$el.querySelector("." + first);
                el.scrollIntoView({behavior: 'smooth'})
            }
        },
        async calcImgSizes() {
            for (const img of this.product.step3.images) {
                   let dim = await this.getImageDimensions(img)
                img.dimensions={
                        height: dim.h,
                        width: dim.w,
                        ratio: dim.h / dim.w
                    }
            }
        },
        async getImageDimensions(file) {
            return new Promise(function (resolved, rejected) {
                var i = new Image()
                i.onload = function () {
                    resolved({w: i.width, h: i.height})
                };
                i.src = file.url
            })
        },
        async send() {
            this.verifyErrors()
            if (this.$v.product.$anyError) {
                return;
            }
            await this.calcImgSizes();
            this.preview = true;
        },

    },
    computed: {
        deliveryMethod() {
            const delivery = {
                0: this.$t('envoi_postal'),
                1: this.$t('remise_en_main_propre_et_envoi_postal'),
                2: this.$t('remise_en_main_propre_uniquement')
            }
            const deliveryOption = this.product.step4.delivery_option
            return delivery[deliveryOption]
        },
        ...mapGetters('products', ['getProduct', 'isProductLoaded']),
        ...mapGetters('brands', ['allBrands']),
        ...mapGetters('merchant', ['merchant']),


    },


}
</script>
