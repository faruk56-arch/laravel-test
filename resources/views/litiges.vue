<template>
    <section class="container pb-5 mb-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <ValidationObserver ref="form" v-slot="{ handleSubmit, failed }">
                    <form @submit.prevent="handleSubmit(send)" class="form-md" v-if="step !== 2">
                        <fieldset>
                            <keep-alive>
                                <div :class="step === 0 ? '' : 'd-none'">
                                    <p class="text-blue pb-4">Veuillez fournir le plus d'informations possible sur votre achat. Nous
                                        pourrons alors sélectionner les organismes de règlement des litiges qui conviennent le mieux pour
                                        traiter votre plainte.</p>

                                    <label for="day">Quand l’achat a-t-il été effectué ?
                                        <sup class="text-danger">*</sup></label>

                                    <div class="row">
                                        <keep-alive>
                                            <ValidationProvider name="jour" rules="required" v-slot="{ errors }" vid="day" class="col-md-4">
                                                <div class="form-group" :class="{'error': errors.length}">
                                                    <select class="form-control" id="day" v-model="form.day">
                                                        <option disabled :value="null">Jours</option>
                                                        <option v-for="day in days" :value="day">{{ day }}</option>
                                                    </select>
                                                    <span v-if="errors">{{ errors[0] }}</span>
                                                </div>
                                            </ValidationProvider>
                                        </keep-alive>
                                        <ValidationProvider name="mois" rules="required" v-slot="{ errors }" vid="month" class="col-md-4">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <select class="form-control" id="month" v-model="form.month">
                                                    <option disabled :value="null">Mois</option>
                                                    <option v-for="month in months" :value="month">{{ month }}</option>
                                                </select>
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                        <ValidationProvider name="année" rules="required" v-slot="{ errors }" vid="year" class="col-md-4">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <select class="form-control" id="years" v-model="form.year">
                                                    <option disabled :value="null">Année</option>
                                                    <option v-for="year in years" :value="year">{{ year }}</option>
                                                </select>
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <label for="price">Quel était le prix ? <sup class="text-danger">*</sup></label>

                                    <div class="row">
                                        <ValidationProvider name="prix" rules="required" v-slot="{ errors }" vid="price" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <input type="text" class="form-control" id="price" placeholder="Prix" v-model="form.price">
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                        <ValidationProvider name="devise" rules="required" v-slot="{ errors }" vid="currency" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <select class="form-control" id="currency" v-model="form.currency">
                                                    <option value="eur">EUR - Euro</option>
                                                </select>
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <ValidationProvider name="numéro de référence" v-slot="{ errors }" vid="order_number">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="ref">Quel est le numéro de référence de votre commande ?</label>
                                            <input type="text" class="form-control" id="ref"
                                                   placeholder="Numéro de référence de la commande (facultatif)" v-model="form.order_number">
                                            <span v-if="errors">{{ errors[0] }}</span>

                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="type de plainte" rules="required" v-slot="{ errors }" vid="complaint_type">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="plainte">Quel type de plainte introduisez-vous ? <sup
                                                class="text-danger">*</sup></label>
                                            <select class="form-control" id="plainte" v-model="form.complaint_type">
                                                <option disabled :value="null">Sélectionner un type de plainte</option>
                                                <option v-for="type in types" :value="type">{{ type }}</option>
                                            </select>
                                            <span v-if="errors">{{ errors[0] }}</span>

                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="détail de la plainte" rules="required" v-slot="{ errors }" vid="details">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="details">Veuillez expliquer la plainte en détail <sup
                                                class="text-danger">*</sup></label>
                                            <textarea rows="6" id="details" class="form-control" v-model="form.details"></textarea>
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="document joint" v-slot="{ errors }" vid="file">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="file">Souhaitez-vous joindre un document à votre plainte ? (facultatif)</label>
                                            <input type="file" @change="setFile" ref="file" class="form-control-file" id="file">
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="check1" v-model="form.already_contacted">
                                        <label class="form-check-label cursor-p" for="check1">Le professionnel a déjà été contacté à
                                            propos de la plainte.</label>

                                    </div>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="check2" v-model="form.already_obtained">
                                        <label class="form-check-label cursor-p" for="check2">J'ai déjà essayé d'obtenir un règlement
                                            extrajudiciaire ou d'assigné l'autre partie en justice concernant votre plainte.</label>

                                    </div>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="check3" v-model="form.want_organisation">
                                        <label class="form-check-label cursor-p" for="check3">Le professionnel souhaite recourir à un
                                            organisme de règlement des litiges spécifique.</label>
                                    </div>

                                    <div class="form-group mt-4">
                                        <small><i><sup class="text-danger">*</sup> : Champs obligatoires</i></small>
                                    </div>
                                </div>
                            </keep-alive>
                            <keep-alive>
                                <div :class="step === 1 ? '' : 'd-none'">
                                    <p class="text-blue pb-4">Veuillez indiquer vos coordonnées personnelles dans le formulaire. Il sera
                                        ainsi plus facile pour l’équipe de CPF de vous identifier et pour l’organisme de règlement des
                                        litiges de vous contacter (si vous et le professionnel convenez de recourir à cet organisme pour
                                        résoudre votre litige).</p>
                                    <div class="row">
                                        <ValidationProvider name="nom" rules="required" v-slot="{ errors }" vid="name" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <label for="name">Votre nom <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" id="name" autocomplete="family-name" v-model="form.lastname">
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>

                                        <ValidationProvider name="prénom" rules="required" v-slot="{ errors }" vid="firstname" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <label for="last-name">Votre prénom
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" id="last-name" v-model="form.firstname" autocomplete="given-name">
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <ValidationProvider name="E-mail" rules="required|email" v-slot="{ errors }" vid="email">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="email">Quelle est votre adresse email ?
                                                <sup class="text-danger">*</sup></label>
                                            <input type="email" class="form-control" id="email" v-model="form.email">
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="numéro de téléphone" v-slot="{ errors }" vid="phone">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="phone">Quel est votre numéro de téléphone ?</label>
                                            <input type="text" class="form-control" id="phone" v-model="form.phone">
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <ValidationProvider name="adresse" rules="required" v-slot="{ errors }" vid="address">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="road">Quelle est votre adresse ?
                                                <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control" id="road" placeholder="Rue" v-model="form.address">
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <div class="row">
                                        <ValidationProvider name="code postal" rules="required" v-slot="{ errors }" vid="zip" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <input type="text" class="form-control" id="zip" placeholder="Code postal" v-model="form.zip">
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                        <ValidationProvider name="ville" rules="required" v-slot="{ errors }" vid="city" class="col-md-6">
                                            <div class="form-group" :class="{'error': errors.length}">
                                                <input type="text" class="form-control" id="city" placeholder="Ville" v-model="form.city">
                                                <span v-if="errors">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <ValidationProvider name="pays" rules="required" v-slot="{ errors }" vid="country">
                                        <div class="form-group" :class="{'error': errors.length}">
                                            <label for="country">Pays <sup class="text-danger">*</sup></label>
                                            <select class="form-control" id="country" v-model="form.country">
                                                <option :value="null">Sélectionner un pays</option>
                                                <option v-for="country in countries" :disabled="country.disabled" :value="country.name">{{ country.name }}</option>
                                            </select>
                                            <span v-if="errors">{{ errors[0] }}</span>
                                        </div>
                                    </ValidationProvider>

                                    <div class="form-group mt-4">
                                        <small><i><sup class="text-danger">*</sup> : Champs obligatoires</i></small>
                                    </div>
                                </div>
                            </keep-alive>
                        </fieldset>
                        <div ref="recaptcha" class="g-recaptcha" :data-sitekey="sitekey"></div>
                        <div class="alert alert-danger" v-if="failed">Des champs sont incorrects, veuillez vérifier les deux étapes du formulaires. Vous pouvez revenir en arrière à l'aide du bouton "retour"</div>
                        <div class="d-flex justify-content-between align-items-center" v-if="step === 1">
                            <a class="text-secondary" @click="step-=1"><i class="fal fa-arrow-left"></i> retour</a>
                            <button type="submit" class="btn btn-md bg-blue float-right px-4 py-3">
                                Envoyer ma demande
                            </button>
                        </div>
                        <button class="btn btn-md bg-blue float-right px-4 py-3" @click="step+=1" v-else>
                            Passer à l’étape suivante
                        </button>
                    </form>
                    <template v-else>
                        <div class="text-center">
                            <i class="fal fa-check-circle fa-4x text-gold"></i>
                            <h3 class="text-blue px-4 py-4">Votre demande a bien été transmise, nous vous répondrons dans les meilleurs délais</h3>
                        </div>
                    </template>
                </ValidationObserver>
            </div>
        </div>
    </section>

</template>

<script>
import {ValidationObserver, ValidationProvider, localize, extend} from "vee-validate";
import fr from 'vee-validate/dist/locale/fr.json';
import {email, required} from 'vee-validate/dist/rules'

localize('fr', fr)
extend('required', required)
extend('email', email)

export default {
    name: "litiges",
    components: {
        ValidationProvider,
        ValidationObserver
    },
    props: {
        sitekey: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            form: {
                day: null,
                month: null,
                year: null,
                price: null,
                currency: 'eur',
                order_number: null,
                complaint_type: null,
                details: null,
                file: null,
                already_contacted: false,
                already_obtained: false,
                want_organisation: false,
                firstname: null,
                lastname: null,
                email: null,
                phone: null,
                address: null,
                zip: null,
                city: null,
                country: null,

            },
            countries: [],
            step: 0,
            days: [],
            types: [
                'Bien ou service défectueux/dommage subi',
                'Bien ou service non conforme à la commande',
                'Livraison',
                'Facturation',
                'Garantie',
                'Géoblocage',
                'Autre',
            ],
            months: [
                'Janvier',
                'Février',
                'Mars',
                'Avril',
                'Mai',
                'Juin',
                'Juillet',
                'Août',
                'Septembre',
                'Octobre',
                'Novembre',
                'Décembre'
            ],
            years: []
        }
    },
    created() {
        axios.get('/api/pays').then((res) => {
            this.countries = res.data;
        });
        this.days = this.range(31, 1)
        this.years = this.range(new Date().getFullYear() - 2018, 2019)
    },
    methods: {
        setFile() {
            this.form.file = this.$refs.file.files[0]
        },
        range(i, j = 0) {
            return Array.from(new Array(i), (x, i) => i + j)
        },
        send() {
            let formData = new FormData();
            Object.keys(this.form).forEach(key => formData.append(key, this.form[key]));
            formData.append('g-recaptcha-response', grecaptcha.getResponse())
            axios.post('/api/litige', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                this.step += 1
            })
        }

    },
    computed: {
        captchaApiKey() {
            return process.env.MIX_CAPTCHA_API_KEY
        }
    }
}
</script>

<style scoped>

</style>
