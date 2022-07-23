<template>
    <div>
        <div>
            <h3 class="text-blue mb-4">Liste des utilisateurs</h3>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="allUsersList"
            styleClass="vgt-table striped condensed"
            :pagination-options="{
                    enabled: true,
                    mode:'pages',
                    perPage:10,
                    perPageDropdownEnabled: true,
                    rowsPerPageLabel: 'Utilisateurs par page',
                    ofLabel:'sur',
                    allLabel:'Tous',
                    nextLabel:'Suivant',
                    prevLabel:'Précédent',
                }">
            <template slot="table-row" slot-scope="props">
                <template v-if="props.column.field == 'action' && props.row.deleted_at === null">
                    <button @click="remove(props.row.id)" class="btn btn-danger">Bloquer</button>
                </template> <template v-else-if="props.column.field == 'email'">
                   {{props.row.email}}  <i v-if="!props.row.account_activated" class="fa fa-hourglass-half" aria-hidden="true"  v-b-tooltip.hover title="En attente de validation"></i>
                </template>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "AdminUsersList",
    data() {
        return {
            columns: [
                {
                    label: 'ID',
                    field: 'id',
                    type: 'number',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par ID',
                    },
                    thClass: 'text-center',
                    tdClass: 'text-center id'
                },
                {
                    label: 'Nom',
                    field: 'lastname',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par nom',
                    },
                },
                {
                    label: 'Prénom',
                    field: 'firstname',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par prénom',
                    },
                },
                {
                    label: 'E-mail',
                    field: 'email',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par email',
                    },
                },
                {
                    label: 'Numéro de téléphone',
                    field: 'phone',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par numéro de téléphone',
                    },
                },
                {
                    label: 'Adresse',
                    field: 'address',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par addresse',
                    },
                },
                {
                    label: 'Ville',
                    field: 'city',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par ville',
                    },
                },
                {
                    label: 'CP',
                    field: 'zip',
                    type: 'number',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par CP',
                    },
                    thClass: 'text-left',
                    tdClass: 'text-left',
                },
                {
                    label: 'Région',
                    field: 'region.name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par région',
                    },
                },
                {
                    label: 'Pays',
                    field: 'country.name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Filtrer par pays',
                    },
                },
                {
                    label: 'Marchand',
                    field: 'merchant.merchant_type',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Tous les types',
                        filterDropdownItems: [
                            {value: 'Pro', text: 'Pro'},
                            {value: 'Particulier', text: 'Particulier'},
                        ],
                    },
                },
                {
                    label: 'Action',
                    field: 'action',
                    sortable: false,
                },
            ]
        }
    },
    created() {
        this.$store.dispatch('admin/fetchAllUsers')
    },
    computed: {
        ...mapGetters('admin', ['allUsersList', 'isUsersLoaded']),
    },
    methods: {
        async remove(id) {
            const value = await this.$bvModal.msgBoxConfirm("Bloquer cet utilisateur aura pour conséquence de mettre en brouillon ses produits, archiver ses recherches ainsi que désactiver ses annonces simplifiées.", {
                title: 'Confirmation requise',
                size: 'lg',
                okVariant: 'danger',
                okTitle: 'Bloquer cet utilisateur',
                cancelTitle: 'Annuler',
                footerClass: 'p-2',
                contentClass: 'border-0 rounded',
                hideHeaderClose: false,
                centered: true
            })
            if (value) {
                try {
                    if (!this.$auth.user()) {
                        return this.$router.push({name: 'login'})
                    }
                    await axios.delete('/admin/' + this.$auth.user().id + '/users/' + id)
                    this.$store.commit('admin/REMOVE_USER', id)
                } catch (error) {
                    console.log(error)
                }
            }

            // .then(res => {
            // })
        }
    }
}
</script>

<style scoped>

</style>
