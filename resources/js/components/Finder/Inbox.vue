con.mod<template>
    <div class="inbox">


                <span class="d-block d-md-none">
                    <top-nav></top-nav>
                </span>


        <div class="bg-white shadow container-fluid py-4 px-4 zindex-top">

            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 text-blue mb-0 pt-3">{{ $t('inbox') }}</h1>
                <span class="d-none">
                    <top-nav></top-nav>
                </span>

            </div>

        </div>

        <div class="inbox-wrapper border-right bg-white pt-4">
            <h2 class="h3 h3-serif text-blue px-4 pb-3">{{ $t('conversations') }}</h2>
            <div v-if="loaded&&(productMessages.length>0 || researchMessages.length > 0)">

                <p class="px-4 mb-0 bg-dark-grey py-2 text-blue" v-if="productMessages.length != 0 && researchMessages.length != 0">{{ $t('buying_product') }}</p>

                <ul class="list-unstyled">
                    <li v-for="(conv, index) in productMessages"
                        class="conv-details border-bottom px-4 text-blue py-3 cursor-p d-flex"
                        :class="{'active' : active.products[index] === true}" @click="changeActive('products', index)">
                        <div class="bg-gold rounded-circle background-center p-4 d-inline-block preview-img" :class="{'research' : conv.img[0] != ''}"
                             :style="'background-image: url('+conv.img[0]+')'"></div>
                        <div class="pl-3 w-100">
                            <span class="badge float-right badge-danger new-message-badge font-weight-normal rounded-circle p-1"
                                  v-if="conv.messages.find(m=>m.read===0&&m.sender.id!==$auth.user().id)">
                                {{conv.messages.filter(m=>m.read===0&&m.sender.id!==$auth.user().id).length}}</span>
                            <p class="mb-0"><strong>{{ conv.name }}</strong></p>
                            <p class="mb-0" v-if="conv.modele[0]"><small>{{ conv.modele[0].brand.name }} {{ conv.modele[0].name }}</small></p>
                        </div>
                        <InboxDiscussion @newMessage="newMessage('products')" :conv="conv" :index="index"></InboxDiscussion>

                    </li>
                </ul>
<!--                TODO : séparer convenablement -->
                <p class="px-4 mb-0 bg-dark-grey py-2 text-blue " v-if="productMessages.length != 0 && researchMessages.length != 0">{{ $t('selling_product') }}</p>

                <ul class="list-unstyled">
                    <li v-for="(conv, index) in researchMessages"
                        class="conv-details border-bottom px-4 text-blue py-3 cursor-p d-flex"
                        :class="{'active' : active.researches[index] === true}" @click="changeActive('researches', index)">
                        <div class="bg-gold rounded-circle background-center p-4 d-inline-block preview-img" :class="{'alert' : conv.img[0] != ''}" :style="'background-image: url('+ conv.img && conv.img[0]+')'"></div>

                        <div class="pl-3 w-100">
                            <span class="badge float-right badge-danger new-message-badge font-weight-normal rounded-circle p-1"
                                  v-if="conv.messages.find(m=>m.read===0&&m.sender.id!==$auth.user().id)">
                                {{conv.messages.filter(m=>m.read===0&&m.sender.id!==$auth.user().id).length}}</span>
                            <p class="mb-0"><strong>{{ conv.part && translation('name', conv.part) }}</strong></p>
                            <p class="mb-0"><small>{{ conv.modele && conv.modele.modele && conv.modele.modele.brand.name }} {{ conv.modele && conv.modele.modele.name }}</small></p>
                        </div>
                        <InboxDiscussion @newMessage="newMessage('researches')" :conv="conv" :index="index"></InboxDiscussion>

                    </li>
                </ul>
            </div>
            <div v-else-if="loaded">
                <span class="pl-4">{{ $t('no_new_message') }}</span>
            </div>
            <span v-else class="loader"></span>
        </div>
    </div>

</template>

<script>
import {mapGetters} from "vuex";
import {mapActions} from "vuex";

const InboxDiscussion = () => import(/* webpackChunkName: "group-finder" */'./InboxDiscussion');
const TopNav = () => import(/* webpackChunkName: "group-dashboard" */"../Shared/TopNav");

export default {
    name: "Inbox",
    components: {InboxDiscussion, TopNav},
    data() {
        return {
            loaded: false,
            active: {
                products: [],
                researches: []
            },
        }
    },
    methods: {
        changeActive(key, index) {
            this.active = {
                products: [],
                researches: []
            };
            Object.keys(this.allMessages).forEach((el) => {
                this.allMessages[el].forEach(() => {
                    this.active[el].push(false);
                })
            })
            this.active[key][index] = true;
            for (let i = this.allMessages[key][index].messages.length-1; i >-1; i--) {
                let message = this.allMessages[key][index].messages[i];
                if(message.sender.id!==this.$auth.user().id&&message.read===0)this.markRead(key,index,i)
            }

            setTimeout(() => {
                let element = document.getElementById("lastLi" + index);
                element.scrollIntoView(false);
            }, 10)
        },
        markRead(key,index,i){
            this.markAsRead(this.allMessages[key][index].messages[i]);
        },
        newMessage(key) {
            this.active = {
                products: [],
                researches: []
            };
            Object.keys(this.allMessages).forEach((el) => {
                this.allMessages[el].forEach(() => {
                    this.active[el].push(false);
                })
            })
            this.active[key][0] = true;
            setTimeout(() => {
                let element = document.getElementById("lastLi" + 0);
                element.scrollIntoView(false);
            }, 10)
        },
        ...mapActions('messages',['markAsRead'])
    },
    mounted() {
        this.$store.dispatch('messages/fetchAllMessages').then(() => {
            this.loaded = true
            Object.keys(this.allMessages).forEach((el) => {
                this.allMessages[el].forEach(() => {
                    this.active[el].push(false);
                })
            })
            if (this.researchMessages.length>0){
                this.active.researches[0] = true;
                for (let i = this.researchMessages[0].messages.length-1; i >-1; i--) {
                    let message = this.researchMessages[0].messages[i];
                    if(message.sender.id!==this.$auth.user().id&&message.read===0)this.markRead('researches', 0,i)
                }
            } else if (this.productMessages.length > 0) {
                this.active.products[0] = true;
                for (let i = this.productMessages[0].messages.length-1; i >-1; i--) {
                    let message = this.productMessages[0].messages[i];
                    if(message.sender.id!==this.$auth.user().id&&message.read===0)this.markRead('products', 0,i)
                }
            }
        })

    },
    computed: {
        ...mapGetters('messages', ['allMessages']),
        productMessages() {
            return this.allMessages.products
        },
        researchMessages() {
            return this.allMessages.researches
        },

    }

}
</script>

<style scoped>

</style>
