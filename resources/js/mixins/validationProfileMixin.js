import {ValidationProvider, ValidationObserver, localize, extend} from 'vee-validate';
import fr from 'vee-validate/dist/locale/fr.json';
import {alpha, required, min, max, numeric, confirmed, email} from 'vee-validate/dist/rules';


const validationProfileMixin = {
    created() {
        localize('fr', fr)
        extend('required', required);
        extend('email', email);
        extend('numeric', numeric);
        extend('checkName', this.validName);
        extend('alpha', alpha);
        extend('confirmed', confirmed);
        extend('min', min);
        extend('max', max);
        axios.get('/pays').then((res) => {
            this.countries = res.data;
            this.country = this.countries.find(el => el.id === this.payload.country_id)
        });

    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
    computed: {
        hasMerchant() {
            return this.$auth.user().merchant
        },

        regions() {
            return this.country && this.country.regions && this.country.regions
        },
    },
    methods:{
        validName(value){
            let re = new RegExp("^[A-Za-zÀ-ÖØ-öø-ÿ]'?[- A-Za-zÀ-ÖØ-öø-ÿ]+$")
            return re.test(value);
        }
    },
    data() {
        return {
            success: false,
            countries: null,
            country: null
        }
    },
    watch: {
        payload: {
            handler() {
                this.success = false
            },
            deep: true,
        },
        "payload.country_id": {
            handler() {
                this.country = this.countries.find(el => el.id === this.payload.country_id)
            }
        }
    }
}
export default validationProfileMixin
