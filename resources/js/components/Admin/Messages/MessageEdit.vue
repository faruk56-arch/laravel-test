<template>
    <div class="form-group form-sm">
        <label for="input">Contenu du message</label>
        <input id="input" type="text" class="d-block w-100" v-model="newMsg">
        <button class="btn bg-gold btn-sm mt-3" @click="submit">Valider</button>
    </div>
</template>

<script>
export default {
    name: "MessageEdit",
    props:['msg','msgId'],
    data(){
        return {
            newMsg:this.msg
        }
    },
    methods:{
        submit(){
            this.$emit('submitted',this.newMsg,this.msgId)
            this.$bvModal.hide('modal-edit'+this.msgId)
        }
    }
}
</script>

<style scoped>

</style>
