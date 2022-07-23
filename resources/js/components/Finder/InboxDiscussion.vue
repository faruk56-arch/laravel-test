<template>
<div>
    <div class="inbox-discussion cursor-initial" v-if="conv&&loaded">
        <div class="inbox-discussion-wrapper p-4">
            <div class="text-center mb-4">
                <div class="bg-gold rounded-circle p-4 d-inline-block background-center preview-img" :class="conv.currency?'research': 'alert'" :style="'background-image: url('+conv.img[0]+')'" ></div>
                <router-link v-if="isSeller" :to="{name:'addProduct',params:{id:conv.id}}" class="cursor-p">
                    <p class="mb-0"><strong>{{ conv.name || translation('name', conv.part) }}</strong></p>
                    <p class="mb-0"><small>{{ (conv.modele[0] && conv.modele[0].brand.name) || conv.modele.modele.brand.name }} {{ (conv.modele[0] && conv.modele[0].name) || conv.modele.modele.name }}</small></p>
                </router-link>
                <template v-else>
                    <p class="mb-0"><strong>{{ conv.name || translation('name', conv.part) }}</strong></p>
                    <p class="mb-0"><small>{{ (conv.modele[0] && conv.modele[0].brand.name) || conv.modele.modele.brand.name }} {{ (conv.modele[0] && conv.modele[0].name) || conv.modele.modele.name }}</small></p>
                </template>
            </div>
            <ul class="list-unstyled">
                <li v-for="msg in conv.messages" class="bg-white shadow p-3 position-relative">
                    <strong>{{the_sender(msg.sender.id)}} :
                    <p class="mb-1"><small><strong>{{ $t('sent_at', {'date': msg.created_at}) }}</strong></small></p></strong>
                    <hr class="mt-1 mb-0">
                    <p class="mb-0 py-3">{{msg.params[0]}}</p>
                    <p class="state mb-0 px-3" v-if="the_sender(msg.sender.id)===$t('vous')">
                        <small class="badge badge-warning text-white font-weight-normal mt-1" v-if="msg.checked===0" v-b-tooltip.hover :title="$t('message_sent_for_validation')">
                            <i><i class="fal fa-info-circle pr-1"></i> {{ $t('moderation_in_progress') }}</i>
                        </small>
                        <small class="badge font-weight-normal mt-1" v-else-if="msg.read===0">
                            <i> <i class="fal fa-check pr-1"></i> {{ $t('sent') }}</i>
                        </small>
                        <small class="badge font-weight-normal mt-1" v-else>
                            <i> <i class="fal fa-check-double pr-1"></i> {{ $t('read') }}</i>
                        </small>
                    </p>
                </li>
                <li :id="'lastLi'+index"></li>
            </ul>
        </div>
        <div class="form-group form-sm d-flex p-4 shadow bg-white answer mb-0 w-100">
             <input class="form-control" type="text" :placeholder="$t('new_message')" v-model="msg">
             <button class="ml-3 btn btn-sm bg-blue" @click="newMsg">{{ $t('send') }}</button>
        </div>
    </div>
    <div v-else-if="convLoaded&&loaded">
        <ul class="list-unstyled inbox-discussion-admin">
            <li class="bg-white shadow p-3 position-relative mb-3 text-blue" v-for="msg in convLoaded" :class="msg.id==idMessage?'text-success border border-success':''">
                <strong>{{ $t('from') }} {{msg.sender.firstname}} {{msg.sender.lastname}}</strong>
                <span class="float-right">
                    <small class="badge badge-warning text-white font-weight-normal" v-if="msg.checked===0 && msg.id!=idMessage" v-b-tooltip.hover :title="$t('message_sent_for_validation')">
                        <i><i class="fal fa-info-circle pr-1"></i> {{ $t('moderation_in_progress') }}</i>
                    </small>
                    <small class="badge font-weight-normal mt-1" v-else-if="msg.checked==2">
                            <i> <i class="fal fa-times pr-1"></i> {{ $t('refused') }}</i>
                        </small>
                    <small class="badge font-weight-normal" v-else-if="msg.id!=idMessage">
                        <i> <i class="fal fa-check pr-1"></i> {{ $t('sent') }}</i>
                    </small>
                    <small v-if="msg.id==idMessage" class="badge text-white badge-success font-weight-normal" v-else>
                        <i> <i class="fal fa-clock pr-1"></i>{{ $t('actual_message_to_moderate') }}</i>
                    </small>
                </span>
                <small class="mb-1 d-block"><strong>{{ $t('sent_at', {'date': msg.created_at}) }}</strong></small>
                <hr class="mt-1">
                {{msg.params[0]}}
            </li>
        </ul>
    </div>
    <div v-else class="loader"></div>
</div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "InboxDiscussion",
    props:['conv','idMessage','idPivot','index'],
    data() {
        return {
            msg:'',
            convLoaded:null,
            loaded:true,
        }
    },
    created() {
        if(this.idMessage){
            this.loaded = false;
            axios.get('conv/'+this.idPivot).then(res=>{
                this.convLoaded = res.data;
                this.loaded=true;
            })
        }
    },
    methods:{
        newMsg(){
            let recipient;
            if (this.isSeller){
                recipient = (this.conv.researchUser && this.conv.researchUser.id) || this.conv.user_id;
            }else{
                recipient=(this.conv.merchant && this.conv.merchant.user[0].id) || this.conv.merchant_id
            }
            this.newMessage({
                template_id: 4,
                params: [this.msg],
                recipient:recipient,
                sender:this.$auth.user().id,
                type: this.conv.messages[0].type,
                type_id: this.conv.messages[0].type_id,
                checked:0,
            }).then(()=>{
                this.$emit('newMessage')
                this.msg='';
            })
            setTimeout(() => {
                let element = document.getElementById("lastLi" + 0);
                element.scrollIntoView(false);
            })

        },
        the_sender(id){
            if(id===this.$auth.user().id){
                return this.$t('vous');
            }
               if (this.isSeller){
                   return this.$t('the_buyer')
               }
               return this.$t('the_seller')
        },
        ...mapActions('messages', ['newMessage']),
    },
    computed:{
        isSeller(){
            return this.conv && ((this.conv.merchant && this.conv.merchant.user[0].id===this.$auth.user().id))
        }
    }
}
</script>

<style scoped>

</style>
