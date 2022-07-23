const langs = [
    'fr_FR',
    'en_EN',
    'de_DE'
]

export default {
    methods: {
        translation(field, model, translationField = 'translation') {
            const lang = this.$i18n.locale + '_' + this.$i18n.locale.toUpperCase()
            const otherLangs = langs.filter(el => el !== lang)
            if (!model[translationField][field]) {
                return model[field] ? model[field] : ''
            }
            return model[translationField][field][lang] || model[translationField][field][otherLangs[0]] || model[translationField][field][otherLangs[1]]
        }
    }
}
