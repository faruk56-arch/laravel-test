<template>
    <div class="message-list">

        <div>
            <h3 class="text-blue mb-4">Messages pour l'administrateur</h3>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allMessagesAdmin.filter(message=>message.template_id!==4)"
            ref="my-table"
            styleClass="vgt-table striped condensed"
            :row-style-class="isView"
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
                <template v-if="props.column.field == 'template_id'">
                    <router-link v-if="props.row.template_id!==5&&props.row.template_id!==6" tag="a" class="btn btn-xs px-4 bg-blue" :to="{name:'researchProposalList',params:{id:props.row.research_id}}">Voir la recherche</router-link>
                    <span v-else-if="props.row.template_id==6">Proposition pièce</span>
                    <span v-else>Proposition véhicule</span>
                </template>
                <template v-else-if="props.column.field == 'action'">
                    <td>
                        <button class="btn btn-danger" @click="removal(props.row)"><i class="fal fa-trash"></i></button>
                    </td>
                </template>
                <template v-else-if="props.column.field === 'read'">
                    <template v-if="props.row.read === 0">
                        <i class="far fa-envelope fa-lg cursor-p" @click="maskAsRead(props.row)" v-b-tooltip.hover title="Marquer comme lu"></i>
                       <!--  <button class="btn btn-info" >Marquer comme lu</button> -->
                    </template>
                    <template v-else>
                        <i class="fal fa-envelope-open fa-lg text-success" v-b-tooltip.hover title="Message lu"></i>
                    </template>
                </template>
            </template>
            <div slot="emptystate">
                <div class="bg-white text-blue px-4 py-4 mt-3">
                    Aucun message ne correspond aux filtres
                </div>
            </div>
        </vue-good-table>
        <div class="bg-white text-blue px-4 py-4 mt-3" v-if="allMessagesAdmin.length == 0">
            Aucun message pour l'instant !
        </div>

    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "MessagesAdmin",
    data() {
        return {
            ids: [],
            columns: [
                {
                    label: 'Lu',
                    field: 'read',
                    type: 'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterDropdownItems: [
                            {value: 0, text: 'Non lu'},
                            {value: 1, text: 'Lu'},
                        ],
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                },
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par ID', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                },
                {
                    label: 'Expéditeur',
                    field: this.senderFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par expéditeur', // placeholder for filter input
                        filterFn: this.filterString
                    },
                },
                {
                    label: 'Raison du message',
                    field: this.reasonFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par raison', // placeholder for filter input
                        filterFn: this.filterString
                    },
                }, {
                    label: 'Email de l\'expediteur',
                    field: this.emailFn,
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par email', // placeholder for filter input
                        filterFn: this.filterInt
                    },
                }, {
                    label: 'Date d\'envoi',
                    field: 'created_at',
                    type: 'date',
                    dateInputFormat: "d MMM yyyy 'à' H'h'm",
                    dateOutputFormat: "d MMM yyyy 'à' H'h'm",
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Filtrer par date', // placeholder for filter input
                    },
                    tdClass: "text-left",
                    thClass: "text-left",
                },
                {
                    label: 'Association',
                    field: 'template_id',
                    type: 'number',
                    filterOptions: {
                        enabled: true, // enable filter for this column
                        placeholder: 'Tous', // placeholder for filter input
                        filterDropdownItems: [
                            {value: 1, text: 'Proposition véhicule'},
                            {value: 2, text: 'Messages de recherche'},
                        ],
                        filterFn: this.filterTemplate
                    },
                },
                {
                    label: 'Action',
                    field: 'action',
                    sortable: false
                },
            ]
        }
    },
    mounted() {
    },
    methods: {
        ...mapActions('messages', ['removeAdminMessage', 'markAsReadAdmin']),
        removal(message) {
            const h = this.$createElement;
            const body = [h('div', {class: ['position-relative', 'text-blue']}, [
                h('h3', {class: 'text-center'}, [
                    'Êtes-vous sur de vouloir supprimer ce message ?'
                ])
            ]), h('p', {class: 'h3'}, [])];
            this.$removalConfirmation(body, null).then(removal => removal ? this.removeAdminMessage(message) : null)
        },
        maskAsRead(message) {
            this.markAsReadAdmin(message)
        },
        isView(row){
           return row.read === 0 ? 'no-view' : 'is-view';
        },
        filterString(nom, filterString) {
            return nom.toLowerCase().includes(filterString.toLowerCase());
        }, filterInt(nom, filterString) {
            return nom.toString().toLowerCase().includes(filterString.toString().toLowerCase());
        }, filterTemplate(data, filterString) {
            if (filterString == 2) return data !== 5
            else return data == 5
        },
        senderFn(message) {
            if (message.sender) return message.sender.firstname + ' ' + message.sender.lastname
            else return 'Pas de compte'
        }, reasonFn(message) {
            return message.template.translation[''].fr_FR + message.params[0]
            //return message.translation != " NO_TRANSLATION " ? message.translation : message.params[0] // message.translation est vide !
        }, emailFn(message) {
            return message.sender ? message.sender.email : message.email
        }
    },
    computed: {
        ...mapGetters('messages', ['allMessagesAdmin']),

    },
}
</script>

<style scoped>

</style>
