<template>
    <div class="message-list">

        <div>
            <h3 class="text-blue mb-4">Modération</h3>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allMessagesAdmin.filter(message=>message.template_id===4)"
            ref="my-table"
            styleClass="vgt-table striped condensed"
            :pagination-options="{
                    enabled: true,
                    mode:'pages',
                    perPage:10,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: 'Messages par page',
                    ofLabel:'sur',
                    allLabel:'Tous',
                    nextLabel:'Suivant',
                    prevLabel:'Précédent',
                }">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.field == 'action'">
                    <button class="btn bg-gold btn-sm" @click="markMessage(props.row.id,1)">Valider</button>
                    <b-dropdown id="dropdown-1" right text="Dropdown Button" class="rounded-circle text-blue bg-blue">
                        <template #button-content>
                            <i class="fal fa-cog"></i>
                        </template>
                        <b-dropdown-item @click="markMessage(props.row.id,2)"><span class="text-danger"><i class="fal fa-trash-alt pr-1"></i>Refuser</span></b-dropdown-item>
                        <b-dropdown-item v-b-modal="'modal-edit'+props.row.id"><i class="fal fa-pencil pr-1"></i>Modifier</b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item v-b-modal="'modal-details'+props.row.id"><i class="fal fa-eye pr-1"></i>Détails</b-dropdown-item>
                    </b-dropdown>
                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucun message ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <template v-for="message in allMessagesAdmin.filter(message=>message.template_id===4)">
            <b-modal :id="'modal-edit'+message.id" hide-footer centered title="Edition du message" content-class="rounded border-0 shadow-lg">
                <MessageEdit :msgId="message.id" :msg="message.params[0]" class="my-2" placeholder="Contenu du message" @submitted="submitted"/>
            </b-modal>
            <b-modal :id="'modal-details'+message.id" hide-footer centered title="Historique des messages" content-class="rounded border-0 shadow-lg">
                <InboxDiscussion :conv="null" :idPivot="message.type_id" :idMessage="message.id"/>
            </b-modal>
        </template>
        <div class="bg-white text-blue px-4 py-4 mt-3" v-if="allMessagesAdmin.filter(message=>message.template_id===4).length === 0">
            Aucun message pour l'instant !
        </div>

    </div>
</template>

<script>
import {mapGetters} from "vuex";
const InboxDiscussion = () => import(/* webpackChunkName: "group-finder" */'../../Finder/InboxDiscussion');
const MessageEdit = () => import(/* webpackChunkName: "group-finder" */'./MessageEdit');

export default {
    name: "MessagesUser",
    components: {InboxDiscussion,MessageEdit},
    data(){
        return {
            ids:[],
            columns: [
                {
                    label:'ID',
                    field: 'id',
                    type:'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par ID', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                },
                {
                    label:'Expéditeur',
                    field: this.senderFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par expéditeur', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label:'Destinataire',
                    field: this.recipientFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par destinataire', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label:'Contenu du message',
                    field: 'params.0',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par contenu', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },{
                    label:'Email de l\'expediteur',
                    field: 'sender.email',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par email', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                },{
                    label:'Email du destinataire',
                    field:'recipient.email',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par email', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                },{
                    label:'Date d\'envoi',
                    field: 'created_at',
                    type:'date',
                    dateInputFormat: "d MMM yyyy 'à' H'h'm",
                    dateOutputFormat: "d MMM yyyy 'à' H'h'm",
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par date', // placeholder for filter input
                    },
                },
                {
                    label:'Action',
                    field: 'action',
                    sortable:false
                },
            ]

        }
    },
    created() {
            this.allMessagesAdmin.forEach(msg=>{
                if (msg.type==='research'){
                    this.ids.push(msg.type_id)
                }else{
                    axios.get('/messageId/'+msg.type_id).then(res=>{
                        this.ids.push(res.data)
                    })
                }
            })
    },

    methods:{
        markMessage(id,checked){
            if (!this.$auth.user()) {
                return this.$router.push({name: 'login'})
            }

            axios.put('admin/'+this.$auth.user().id+'/message/'+id,{
                checked
            }).then(res=>{
                this.$store.commit('messages/REMOVE_MESSAGE_ADMIN',id);
            })
        },
        submitted(msg,id){
            if (!this.$auth.user()) {
                return this.$router.push({name: 'login'})
            }
            let params = [msg];
            axios.put('admin/'+this.$auth.user().id+'/message/'+id,{
                params
            }).then(res=>{
                this.$store.commit('messages/EDIT_MESSAGE_ADMIN',res.data);
            });
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        }, filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        }, filterTemplate(data, filterString) {
            if (filterString ==2)return data!==5
            else return data==5
        },
        senderFn(message){
            if(message.sender)return message.sender.firstname +' '+message.sender.lastname
            else return 'Pas de compte'
        },
        recipientFn(message){
            if(message.recipient)return message.recipient.firstname +' '+message.recipient.lastname
            else return 'Pas de compte'
        }, reasonFn(message){
            return message.translation != " NO_TRANSLATION " ? message.translation : message.params[0]
        },emailFn(message){
            return message.sender?message.sender.email: message.email
        }
    },
    computed: {
        ...mapGetters('messages', ['allMessagesAdmin']),

    },
}
</script>

<style scoped>

</style>
