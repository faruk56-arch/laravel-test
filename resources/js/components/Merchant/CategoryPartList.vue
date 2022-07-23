<template>
    <ul class="list-unstyled mt-3 mb-2 add-alert-parts-list">
        <li class="mb-1" v-for="(part,i) in orderedParts">
            <input type="checkbox" :id="'p-'+part.id" @change="$emit('part-checkbox', part)">
            <label :for="'p-'+part.id" class="pl-1 mb-0">{{ translation('name', part) }}</label>
        </li>
    </ul>
</template>

<script>
export default {
    name: "CategoryPartList",
    props: {
        parts: {
            type: Array
        }
    },
    computed: {
        orderedParts() {
            return this.parts.sort((a, b) => {
                return this.translation('name', a).localeCompare(this.translation('name', b))
            })
        }
    }
}
</script>

<style scoped>

</style>
