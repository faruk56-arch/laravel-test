<template>
    <b-modal size="lg" id="guide-des-etats" hide-footer centered content-class="rounded border-0 shadow-lg" :title="$t('states_guide')" body-class="p-4">
        <div>

        </div>
        <p>{{ $t('criteria_responsibility') }}</p>
        <span class="alert alert-info d-block d-flex">
            <i class="far fa-thumbs-up mr-2 pt-1"></i>
            <span>
                <strong>{{ $t('our_advice') }}</strong> : {{ $t('avoid_issue_defaults') }}
            </span>
        </span>
        <table class="table-striped">
            <tbody>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-0 text-blue"><strong>{{ $t('brand_new') }}</strong></p>
                    <p class="mb-0">{{ $t('never_used') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-0 text-blue"><strong>{{ $t('very_good') }}</strong></p>
                    <p class="mb-0">{{ $t('almost_never_used') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-0 text-blue"><strong>{{ $t('good') }}</strong></p>
                    <p class="mb-0">{{ $t('few_imperfections') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-0 text-blue"><strong>{{ $t('satisfying') }}</strong></p>
                    <p class="mb-0">{{ $t('needs_cleaning') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-0 text-blue"><strong>{{ $t('renovate') }}</strong></p>
                    <p class="mb-0">{{ $t('to_renovate_fix') }}</p>
                </td>
            </tr>
            </tbody>
        </table>
    </b-modal>
</template>

<script>
export default {
    name: "ModalState"
}
</script>

<style scoped>

</style>
