export const status = {
    data() {
        return {
            partState: [
                {value: '12', text: this.$t('brand_new')},
                {value: '13', text: this.$t('very_good')},
                {value: '14', text: this.$t('good')},
                {value: '15', text: this.$t('satisfying')},
                {value: '16', text: this.$t('renovate')},
            ],
            partType: [
                {
                    value: '17', text: this.$t('factory_oem')
                },
                {
                    value: '18', text: this.$t('remade_oem')
                },
                {
                    value: '19', text: this.$t('old_fixed')
                },
                {
                    value: '20', text: this.$t('remplacement_part')
                }
            ]
        }
    },
    methods: {
        getStatusText(value) {
            const state = this.partState.find(el => +el.value === +value)
            const type = this.partType.find(el => +el.value === +value)
            return state !== undefined ? state.text : type !== undefined ? type.text : null
        }
    }
}
