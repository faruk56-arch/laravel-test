<template>
    <div class="research-proposal-details text-blue custom-scroll">

        <div class="close-details bg-white shadow p-3 cursor-p" @click="closeModal">
            <i class="far fa-chevron-right"></i>
        </div>

        <div class="bg-white p-4 shadow h-100 d-flex justify-content-between flex-column" v-if="product">

            <div class="details-content-wrapper" :class="{'little' : newMsg}">



                <div class="details-content pr-3">
                <p class="h3 mb-2"><strong>{{ product.original_name }}</strong></p>
                <span v-if="!product.deleted_at" class="badge cursor-p mb-2" @click="togglePublic" :class="product.public ? 'badge-success text-white' : 'badge-secondary' ">
                    <i v-if="product.public" class="far fa-fw fa-eye"></i>
                    <i v-else class="far fa-fw fa-eye-slash"></i>
                    {{ product.public ? 'Rendre invisible sur l\'accueil': 'Rendre visible sur l\'accueil' }}
                </span>
                <p>{{ getStatusText(product.type) }} <b-badge class="text-white badge-lg" pill :variant="getVariantBadgeColor(product.condition)">État {{ getStatusText(product.condition)?getStatusText(product.condition).toLowerCase():'' }}</b-badge></p>

                    <div class="img-wrapper mb-3" v-if="product.img != null">

                        <div v-if="product.img.length == 1" @click="zoom(0)" :key="0" class="h-100">
                            <zoom-on-hover class="h-100 w-100" :img-normal="product.img[0]" :scale="1.2"></zoom-on-hover>
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

                            <b-carousel-slide v-for="(i, imageIndex) in product.img" :key="imageIndex">
                                <template v-slot:img>
                                    <div class="h-100" @click="zoom(imageIndex)" :key="imageIndex">
                                        <zoom-on-hover class="h-100 w-100" :img-normal="i" :scale="1.2"></zoom-on-hover>
                                    </div>
                                </template>
                            </b-carousel-slide>

                        </b-carousel>

                    </div>

                    <div class="pb-5">
                        <table class="table table-striped text-blue py-3">
                            <tbody>

                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Langue d'origine</strong>
                                        {{ product.original_language?product.original_language:'inconnue' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Date de création du produit</strong>
                                        {{ product.created_at }}
                                    </td>
                                </tr>

                                <tr v-if="product.merchant">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Nom de la pièce</strong><br>
                                        {{ product.part ? translation('name', product.part) : product.suggestion + ' (suggéré)' }}
                                    </td>
                                </tr>

                                <tr v-if="product.manufacturer">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Fabricant</strong><br>
                                        {{ product.manufacturer }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="border-0" style="white-space: pre-line;">
                                        <strong>Description : </strong>
                                        {{ product.original_description }}
                                    </td>
                                </tr>

                                <tr v-if="product.reference">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Référence de la pièce</strong>
                                        {{ product.reference }}
                                    </td>
                                </tr>
                                <tr v-if="product.merchant&&(product.merchant.country||product.merchant.merchant_type=='Particulier')">
                                    <td class="border-0">
                                        <p class="mb-1"><strong>A propos du vendeur</strong></p>
                                        <template v-if="product.merchant.user[0]&&product.merchant.user[0].role!=='admin'&&product.merchant.merchant_type==='Pro'">
                                            Vendeur {{ product.merchant.merchant_type }} -
                                            {{ product.merchant.region ? product.merchant.region.name : '' }} - {{ product.merchant.country.name}}
                                            <span :class="'flag-icon flag-icon-'+product.merchant.country.code.toLowerCase()"></span>
                                        </template>
                                        <template v-else-if="product.merchant.user[0]&&product.merchant.user[0].role!=='admin'">
                                            Vendeur {{ product.merchant.merchant_type }} -
                                            {{ product.merchant.user[0].region ? product.merchant.user[0].region.name : '' }} - {{ product.merchant.user[0].country.name}}
                                            <span :class="'flag-icon flag-icon-'+product.merchant.user[0].country.code.toLowerCase()"></span>
                                        </template>

                                        <template v-else-if="product.merchant.user[0]&&product.merchant.user[0].role==='admin'">
                                            Vendu directement par CPF
                                        </template>
                                    </td>
                                </tr>
                                <tr v-if="product.merchant">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Nom du vendeur (invisible)</strong><br>
                                        {{ product.merchant.merchant_name }}
                                    </td>
                                </tr>
                                <tr v-if="product.merchant&&product.merchant.user[0]">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Email du vendeur (invisible)</strong><br>
                                        {{ product.merchant.user[0].email }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Poids de la pièce</strong>
                                        {{ product.weight }} Kg
                                    </td>
                                </tr>

                                <tr v-if="product.width && product.height && product.depth">
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Dimensions du produit</strong>
                                        {{ product.width }}cm x {{ product.height }}cm x {{ product.depth }}cm
                                    </td>
                                </tr>

                                <tr>
                                    <td class="border-0" v-if="product.price">
                                        <strong class="d-inline-block mr-2">Prix</strong>
                                        {{ product.price.toFixed(2) }} €
                                        {{ product.negotiable ? '(Négociable via Obvy)' : '(Non négociable)' }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="border-0">
                                        <p class="mb-1"><strong>Expédition</strong></p>
                                        {{ deliveryMethod }}<br>
                                        <template v-if="product.averagePreparationTime">Temps moyen de préparation : {{product.averagePreparationTime}} jour<template v-if="product.averagePreparationTime>1">s</template></template><br>
                                        <span v-if="product.delivery_option !== 2">
                                            <i>Frais de port en sus à calculer selon le mode de livraison que vous aurez choisi via Obvy.</i>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="border-0">
                                        <strong class="d-inline-block mr-2">Modèle(s) compatible(s)</strong><br>
                                        <ul class="pl-3 mt-1">
                                            <li v-for="model in product.modele">
                                                {{ model.brand.name }} {{ model.name }}
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
                <button v-if="$route.name == 'admin-product-all' && !product.deleted_at" type="button" class="btn px-1 py-3 btn-outline-secondary w-100"
                        @click="submit(false)">
                    <i class="fal fa-pencil pr-1"></i>
                    Éditer
                </button>

            <div v-if="$route.name !== 'admin-product-all' && !product.deleted_at">
                <ul class="list-unstyled d-flex justify-content-center pt-2 mb-0">
                    <template v-if="!product.suggestion">
                        <li class="pr-2 w-75">
                            <button type="button" class="btn bg-gold btn-md py-3" @click="submit(true)">
                                Valider l'annonce
                            </button>
                            <button v-b-modal.modal="'modal-remove-' + product.id" type="button" class="btn bg-danger btn-md py-3">
                                Rejeter
                            </button>
                        </li>
                        <li class="w-25">
                            <button type="button" class="btn px-1 py-3 btn-outline-secondary w-100"
                                    @click="submit(false)">
                                <i class="fal fa-pencil pr-1"></i>
                                Éditer
                            </button>
                        </li>
                    </template>
                    <li class="pr-2 w-100" v-else>
                        <button type="button" class="btn bg-gold btn-md py-3 w-100"  v-b-modal="'modal-center'+product.id">
                            Créer/assigner une pièce
                        </button>
                        <b-modal :id="'modal-center'+product.id" centered hide-footer hide-header v-if="product.part_id === null">
                            <div class="py-4 text-center">
                                <div class="position-relative text-blue">
                                    <i class="fal fa-car fa-5x pb-3"></i>
                                    <h2 class="text-center">La pièce suggérée est: {{ product.suggestion }}</h2>
                                </div>
                                <p class="h3">{{ product.modele[0].brand.name }} {{ product.modele[0].name }} - ({{ product.modele[0].year_start }}-{{ product.modele[0].year_end }})</p>
                                <p class="h4">Vous devez compléter le produit<br>pour qu'il puisse être diffusé</p>
                                <a class="btn bg-gold btn-lg mt-4" @click="selectCreate(product)">Créer la pièce</a>
                                <a class="btn bg-gold btn-lg mt-4" @click="selectAssign(product)">Assigner la pièce</a><br>
                                <form v-if="modalState===1">
                                    <div class="form-group">
                                        <label for="link">La pièce n'est disponible que pour ce véhicule</label>
                                        <input id="link" type="checkbox" @change="setCarId(product)">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Nom de la pièce</label>
                                        <input id="name" type="text" v-model="nameFR" placeholder="Nom">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">Catégorie de la pièce : </label>
                                        <select id="category" v-model="categorySelected">
                                            <option v-for="category in allCategories"
                                                    :value="category.id">{{ translation('name', category) }}
                                            </option>
                                        </select>
                                    </div>
                                    <a class="btn bg-gold mt-4" :class="nameFR!=''&&categorySelected!=''?'':'disabled'"
                                       @click="createAssign(product)">Créer et associer</a>
                                </form>
                                <span v-if="modalState===2">
                                                <autocomplete
                                                    id="researchPart"
                                                    ref="autocomplete1"
                                                    name="researchPart"
                                                    key="1"
                                                    :search="searchPart"
                                                    :get-result-value="getResultValuePart"
                                                    :debounce-time="1"
                                                    default-value=""
                                                    @submit="selectPart($event)"
                                                    placeholder="Pièce détachée"
                                                    class="w-100 auto-cat-home">
                                            </autocomplete>
                                                <a class="btn bg-gold mt-4" :class="partSelected!==0?'':'disabled'" @click="assign(product)">Assigner</a>
                                            </span>
                            </div>
                        </b-modal>
                    </li>
                </ul>
                <b-modal :id="'modal-remove-' + product.id" centered hide-footer hide-header content-class='border-0 rounded px-3'>

                    <div class="alert alert-info mb-0 d-flex">
                        <i class="far fa-info-circle pt-1 mr-2"></i>
                        Un email sera envoyé à la personne à l'origine de cette annonce avec la raison ci-dessous.
                    </div>
                    <div class="form form-xl pt-2 py-2">
                        <label for="message">Raison du rejet</label>
                        <textarea class="w-100 pt-3" rows="4" type="text" id="message" v-model="message"></textarea>
                    </div>

                    <button @click="reject" class="btn btn-danger btn-sm float-right">
                        Confirmer le rejet
                    </button>
                </b-modal>
            </div>

        </div>

    </div>

</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {status} from "../../../mixins/status";
import mxResearches from "../../../mixins/Researches";

export default {
    name: 'AdminCatalogDetails',
    mixins: [status, mxResearches],

    props: ['product', 'index'],
    data() {
        return {
            message: '',
            slide: 0,
            sliding: null,
            confirmation: false,
            indexLightBox: null,
            newMsg: false,
            modalState: 0,
            nameFR: '',
            nameDE: '',
            nameEN: '',
            carSelected: null,
            categorySelected: '',
            partSelected: 0,
        }
    },
    methods: {
        zoom(index) {
            this.showBox([this.product.img])
            this.setIndex(index)
        },
        setProductState(state) {
            this.$emit("productState", state);
        },
        async reject() {
            if (!this.$auth.user()) {
                return this.$router.push({name: 'login'})
            }
            await axios.post('admin/' + this.$auth.user().id + '/product/' + this.product.id + '/reject', {message: this.message})
            this.$store.commit('admin/REMOVE_PRODUCT', this.product)
            this.$bvModal.hide('modal-remove-' + this.product.id)
            this.closeModal()
        },
        submit(bool) {
            if (bool) {
                this.changeProductStatus([this.product.id]).then(res => {
                    this.closeModal();
                })
            } else {
                this.$router.push({name: 'addProduct', params: {id: this.product.id, edit: 1}});
            }
        },
        getVariantBadgeColor(etat) {
            if ( etat == 12 || etat == 13) { return 'success';}
            if ( etat == 14 || etat == 15) { return 'warning';}
            if ( etat == 16 ) { return 'danger';}
        },
        closeModal() {
            this.$emit("showDetails", [this.index]);
            this.newMsg = false
        },
        onSlideStart(slide) {
            this.sliding = true
        },
        onSlideEnd(slide) {
            this.sliding = false
        },
        resetForm() {
            this.modalState = 0;
            this.nameFR = '';
            this.nameEN = '';
            this.nameDE = '';
            this.carSelected = null;
            this.categorySelected = '';
            this.partSelected = 0;
        },
        selectCreate() {
            this.modalState = 1;
            this.partSelected = 0
        },
        createAssign(product) {
            let app = this;
            this.newPart({
                translation: {fr_FR: this.nameFR},
                modele_id: this.carSelected,
                category_id: this.categorySelected
            }).then(res => {
                app.assignPartProduct([product, res.id])
                app.closeModal()
                app.resetForm();
            })
        }, selectAssign(product) {
            this.modalState = 2;
            this.carSelected = product.modele[0].id
        },
        assign(product) {
            this.assignPartProduct([product, this.partSelected])
            this.closeModal()
            this.resetForm();
        },
        setCarId(product) {
            if (this.carSelected == null) this.carSelected = product.modele[0].id;
            else this.carSelected = null;
        },
        async togglePublic() {
            const publicValue = await axios.get('/toggle-public-status/product/' + this.product.id).then(res => res.data)
            this.product.public = publicValue
        },
        ...mapActions('coolLightBox', ['showBox', 'setIndex']),
        ...mapActions('admin', ['assignPartProduct', 'changeProductStatus']),
        ...mapActions('parts', ['newPart', 'assignPart']),
    },
    computed: {
        the_shipping_cost() {
            if (this.$auth.user().country.code !== this.product.merchant.code) return this.product.shipping_cost_abroad;
            else return this.product.shipping_cost;
        },
        deliveryMethod() {
            const delivery = {
                0: 'Envoi postal',
                1: 'Remise en main propre et envoi postal',
                2: 'Remise en main propre uniquement'
            }
            const deliveryOption = this.product.delivery_option
            return delivery[deliveryOption]
        },
        negociableCategory() {
            const delivery = {
                0: 'remote_only',
                1: 'hand_remote',
                2: 'hand_only'
            }
            const deliveryOption = this.product.delivery_option
            const negotiable = this.product.negotiable
            if (delivery[deliveryOption] === 'remote_only') {
                return negotiable ? 'part_negotiable' : 'part'
            }
            if (delivery[deliveryOption] === 'hand_remote') {
                return negotiable ? 'part_negotiable_hand' : 'part_hand'
            }
            if (delivery[deliveryOption] === 'hand_only') {
                return negotiable ? 'part_negotiable_hand_only' : 'part_hand_only'
            }
        },
        is_not_admin() {
            return this.product.merchant && this.product.merchant.user[0] && this.product.merchant.user[0].role !== 'admin'
        },
        ...mapGetters('categories', ['allCategories'])
    }
}
</script>
