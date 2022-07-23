<template>
    <div>
        <div class="col-12" >
            <b-nav class="nav-tabs">
                <b-nav-item v-if="allMessagesAdmin.filter(r=>r.template_id==2||r.template_id==3).length != 0"
                            :to="{name: 'MessagesAdmin'}" exact exact-active-class="active">Messages pour l'admin
                    <span class="ml-1 badge bg-gold text-white font-weight-normal"
                          v-if="allMessagesAdmin.filter(r=>r.template_id==2||r.template_id==3).length != 0">{{ allMessagesAdmin.filter(r => r.template_id == 2 || r.template_id == 3).length }}</span>
                </b-nav-item>
                <b-nav-item :to="{name: 'MessagesUser'}" exact exact-active-class="active">Messages à valider
                    <span class="ml-1 badge bg-gold text-white font-weight-normal"
                          v-if="allMessagesAdmin.filter(r=>!(r.template_id==2||r.template_id==3)).length != 0">{{ allMessagesAdmin.filter(r => !(r.template_id == 2 || r.template_id == 3)).length }}</span>
                </b-nav-item>
            </b-nav>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    name: 'message-list',
    data(){
        return{
        }
    },
    mounted() {
    },
    computed: {
        ...mapGetters('messages', ['allMessagesAdmin']),

    },
}
</script>
