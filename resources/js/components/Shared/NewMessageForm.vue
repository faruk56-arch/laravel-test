<template>
    <div class="new-message-form">
        <form @submit.prevent="submit" v-if="!submitted">
            <div v-for="(question, key, index) in questions">
                <input type="radio" v-model="msg" :id="'not-found-'+(index+1)+id" :class="msg===question?'active':''"
                       :name="'not-found-'+(index+1)+id" :value="question" class="d-none">
                <label class="border d-block rounded px-3 py-2 cursor-p" :for="'not-found-'+(index+1)+id" :class="msg===question?'active':''" >{{ question }}</label>
            </div>

            <div>
                <input autocomplete="off" type="text" v-model="msgOther" name="not-found" :placeholder="type === 'research_product' ? $t('other_question') : $t('enter_your_question')" class="rounded px-3 py-2 border w-100 text-blue other" @click="resetMsg">
            </div>
            <!-- <input v-model="msg" id="not-found" class="w-100" type="text"> -->

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" :class="[{'disabled': emptyMessage},'btn btn-md bg-blue px-5 py-2']">{{ $t('send') }}</button>
                <span class="btn btn-md btn-outline-danger d-flex align-items-center" @click="$emit('close')">{{ $t('cancel') }}</span>
            </div>

        </form>
        <div class="alert alert-success" role="alert" v-else>
            {{ $t('message_waiting_validation') }}
        </div>
    </div>
</template>

<script>
/**
 * @emits submitted the message was entered by the user, contains message as payload
 */
export default {
    name: "NewMessageForm",
    props: {
        id: {
            type: Number
        },
        type: {
            default: 'research_product'
        }
    },
    data() {
        return {
            msg: '',
            submitted: false,
            msgOther: '',
            predefinedProduct: {
                1: this.$t('more_picture_product'),
                2: this.$t('where_product_from'),
            },
            predefinedResearch: {
            }
        }
    },
    methods: {
        submit() {
            if (this.emptyMessage) {
                return
            }
            this.submitted = true;
            if (this.msgOther!=='')this.$emit('submitted', this.msgOther);
            else this.$emit('submitted', this.msg);
        },
        resetMsg(){
            this.msg='';
            document.querySelector('.other').classList.add("active");
        },
    },
    computed: {
        emptyMessage() {
            return this.msg === '' && this.msgOther === ''
        },
        questions() {
            return this.type === 'research_product' ? this.predefinedProduct : this.predefinedResearch
        }
    },
    watch:{
        msg(){
            document.querySelector('.other').classList.remove("active");
            this.msgOther='';
        }
    }
}
</script>

<style scoped>

</style>
