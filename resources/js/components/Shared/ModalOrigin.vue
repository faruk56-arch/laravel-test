<template>
    <b-modal size="lg" id="guide-des-typologie" hide-footer centered content-class="rounded border-0 shadow-lg" :title="$t('part_type_guide')" body-class="p-4">
        <div>

        </div>
        <p>{{ $t('criteria_explanation') }}</p>
        <table class="table-striped">
            <tbody>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-2 text-blue"><strong>{{ $t('factory_oem') }}</strong></p>
                    <p class="mb-0">{{ $t('strict_origin_factory') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-2 text-blue"><strong>{{ $t('remade_oem') }}</strong></p>
                    <p class="mb-0">{{ $t('strict_origin_remade') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-2 text-blue"><strong>{{ $t('old_fixed') }}</strong></p>
                    <p class="mb-0">{{ $t('strict_origin_fixed') }}</p>
                </td>
            </tr>
            <tr>
                <td class="px-4 pb-4 pt-3">
                    <p class="mb-2 text-blue"><strong>{{ $t('remplacement_part') }}</strong></p>
                    <p class="mb-0">{{ $t('quality_replacement') }}</p>
                </td>
            </tr>
            </tbody>
        </table>
    </b-modal>

</template>

<script>
export default {
    name: "ModalOrigin"
}
</script>

<style scoped>

</style>
