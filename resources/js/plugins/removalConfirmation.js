import Vue from 'vue'

Vue.mixin({
    methods: {
        $removalConfirmation($message = this.$t('confirm_removal') ,title=this.$t('confirm_required'), size = 'sm',) {
            return this.$bvModal
                .msgBoxConfirm($message, {
                    title:title,
                    buttonSize: "sm",
                    okVariant: " bg-grey",
                    okTitle: this.$t('remove'),
                    cancelTitle: this.$t('do_not_remove'),
                    cancelVariant: " bg-gold",
                    size : "md",
                    bodyClass: "text-center",
                    titleClass: "",
                    contentClass: "rounded border-0 shadow-lg",
                    headerClass: "justify-content-center py-3",
                    footerClass: "justify-content-center border-0 pt-0 pb-4",
                    hideHeaderClose: true,
                    centered: true
                })
        }
    }
})
