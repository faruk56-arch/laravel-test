<template>
    <div class="form-sm">
        <form @submit.prevent="submit" v-if="!submitted">
            <label for="not-found">{{ label }}</label>
            <textarea v-model="notFound" id="not-found" class="w-100" type="text" :placeholder="placeholder"/>
            <button type="submit" :class="{'disabled': notFound === '', 'btn btn-sm bg-blue mt-3': true}">{{ $t('send') }}</button>
        </form>
        <div class="alert alert-success" role="alert" v-else>
          {{ $t('thanks_for_comment') }}
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

/**
 * @emits submitted the message was entered by the user, contains message as payload
 */
export default {
    name: "NotFoundForm",
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
    data() {
        return {
            notFound: '',
            submitted: false
        }
    },
    methods: {
        ...mapActions('messages', ['newMessage']),
        submit() {
            if (this.notFound === '') {
                return
            }
            this.submitted = true;
            this.$emit('submitted', this.notFound);
        }
    },
}
</script>

<style scoped>

</style>
