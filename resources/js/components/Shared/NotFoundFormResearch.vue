<template>
    <div class="not-found-form-research form-group form-sm">

        <input v-model="notFound" class="py-2 form-control w-100" type="text" :placeholder="placeholder" @input="edit">

    </div>
</template>

<script>
    import {mapActions} from "vuex";

    /**
     * @emits submitted the message was entered by the user, contains message as payload
     */
    export default {
        name: "NotFoundFormResearch",
        data() {
            return {
                notFound: '',
                submitted: false
            }
        },
        methods: {
            ...mapActions('messages', ['newMessage']),
            edit(){
                this.$emit('edit',this.notFound)
            }
        },
        props: {
            label: {
                default: '',
                type: String
            },
            placeholder: {
                default: '',
                type: String
            },
        },
    }
</script>

<style scoped>

</style>
