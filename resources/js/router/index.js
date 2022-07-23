import Vue from "vue";
import translations from "../translations";
import LangRouter from "vue-lang-router";


Vue.use(LangRouter, {
    defaultLanguage: navigator.language,
    translations,
});

const router = new LangRouter(
    {
        mode: 'history',
        routes: [
            {
                path: '/about',
                name: 'About',
                component: () => import('../components/About'),
            }, {
                path: '/cgu',
                name: 'CGU',
                component: () => import('../components/CGU'),
            }, {
                path: '/legal',
                name: 'LegalNotices',
                component: () => import('../components/LegalNotices'),
            }, {
                path: '/privacy',
                name: 'PrivacyPolicies',
                component: () => import('../components/PrivacyPolicies'),
            }, {
                path: '/press',
                name: 'Press',
                component: () => import('../components/LegalNotices'),
            }, {
                path: '/faq',
                name: 'FAQ',
                component: () => import('../components/FAQ'),
            }, {
                path: '/contact',
                name: 'Contact',
                component: () => import('../components/Contact'),
            }, {
                path: '/obvy',
                name: 'Obvy',
                component: () => import('../components/Obvy'),
            },
            {
                path: '/email-validation',
                name: 'email-validation',
                component: () => import('../components/emailValidation'),
                props: route => ({url: route.query.q})
            },
            {
                path: '/logout',
                name: 'logout',
                beforeEnter: (to, from, next) => {
                    localStorage.clear()
                    window.location.href = '/'
                    next()
                }
            },
            {
                path: '/login',
                name: 'login',
                component: () => import('../components/Auth/Login'),
                // meta: {
                //     auth: false,
                // },
            }, {
                path: '/password-reset',
                name: 'password-reset',
                component: () => import('../components/Auth/ResetPassword'),
                // meta: {
                //     auth: false,
                // },
            },
            {
                path: '/password-change/:token',
                name: 'password-change',
                component: () => import('../components/Auth/ChangePassword'),
                // meta: {
                //     auth: false,
                // },
                props: true
            },
            {
                path: '/activation/:token',
                name: 'first-time',
                component: () => import('../components/Auth/FirstTimePassword.vue'),
                props: route => ({token: route.params.token, merchant: route.query.merchant}),
            },
            {
                path: '/research/still/:id',
                name: 'researchStill',
                props: true,
                component: () => import('../components/Finder/Research/ResearchStill'),
            },
            {
                path: '/finder',
                name: 'finder',
                component: () => import(/* webpackChunkName: "group-finder" */'../components/Finder/Finder'),
                meta: {
                    auth: true,
                },
                children: [
                    {
                        path: '',
                        redirect: {name: 'dashboard'}
                    },
                    {
                        path: 'inbox',
                        name: 'Inbox',
                        props: true,
                        component: () => import('../components/Finder/Inbox'),
                        meta: {
                            auth: true
                        }
                    },
                    {
                        path: 'research/:id',
                        name: 'researchProposalList',
                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Finder/Research/ResearchProposalList'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'all-orders',
                        name: 'allOrders',
                        props: true,
                        component: () => import('../components/Admin/Orders/Index'),
                        meta: {
                            roles: 'admin',
                            forbiddenRedirect: {name: 'dashboard'}
                        }
                    },
                    {
                        path: 'boxtal-dashboard',
                        name: 'BoxtalDashboard',
                        beforeEnter() {
                            location.href = 'https://www.boxtal.com/fr/fr/app/utilisateur/connexion'
                        }
                    },
                    {
                        path: 'admin',
                        name: 'adminDashboard',
                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/AdminDashboard'),
                        meta: {
                            auth: {
                                roles: 'admin',
                                forbiddenRedirect: {name: 'dashboard'}
                            },
                        },

                        children: [
                            {
                                path: 'research',
                                name: 'admin-research',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Researches/ResearchList'),
                                children: [
                                    {
                                        path: 'researchesWaiting',
                                        name: 'admin-research-waiting',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Researches/ResearchesWaiting'),
                                    },
                                    {
                                        path: 'allResearches',
                                        name: 'admin-research-all',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Researches/AllResearches'),
                                    },
                                    {
                                        path: 'adminResearchDetails/:id',
                                        name: 'adminResearchDetails',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Researches/AdminResearchDetails'),
                                    },
                                    {
                                        path: 'adminEditResearch/:id',
                                        name: 'adminEditResearch',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Researches/AdminEditResearch'),
                                    },
                                ]
                            },
                            {
                                path: 'allAlerts',
                                name: 'AdminAllAlerts',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Alerts/AdminAllAlerts'),
                            },

                            {
                                path: 'users',
                                name: 'AdminUsersList',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Users/AdminUsersList'),
                            }, {
                                path: 'catalog/:alert?',
                                name: 'admin-catalog',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Products/ProductsList'),
                                children: [
                                    {
                                        path: 'allProducts',
                                        name: 'admin-product-all',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Products/AllProducts'),
                                    },
                                    {
                                        path: 'productWaiting',
                                        name: 'admin-product-waiting',
                                        component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Products/AdminCatalog'),
                                    }
                                ]
                            },
                            {
                                path: 'part',
                                name: 'admin-part',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Parts/PartList'),
                                children: [
                                    {
                                        path: 'addPart/:modele?/:category?',
                                        name: 'addPart',
                                        component: () => import('../components/Admin/Parts/AddPart'),

                                    },
                                    {
                                        path: 'editPart/:id',
                                        name: 'editPart',
                                        props: true,
                                        component: () => import('../components/Admin/Parts/AddPart'),
                                    },
                                ]
                            },
                            {
                                path: 'modele',
                                name: 'admin-modele',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Models/ModelList'),
                                children: [
                                    {
                                        path: 'addModel',
                                        name: 'addModel',
                                        component: () => import('../components/Admin/Models/AddModel'),
                                    },
                                    {
                                        path: 'editModel/:id',
                                        name: 'editModel',
                                        props: true,
                                        component: () => import('../components/Admin/Models/AddModel'),
                                    },
                                ],
                            },
                            {
                                path: 'brand',
                                name: 'admin-brand',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Brands/BrandList'),
                                children: [
                                    {
                                        path: 'addBrand',
                                        name: 'addBrand',
                                        component: () => import('../components/Admin/Brands/AddBrand'),
                                    },
                                    {
                                        path: 'editBrand/:id',
                                        name: 'editBrand',
                                        props: true,
                                        component: () => import('../components/Admin/Brands/AddBrand'),
                                    },
                                ]
                            },

                            {
                                path: 'platform-fee',
                                name: 'platformFee',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/platformFee/FeesList'),
                                meta: {
                                    auth: {
                                        roles: 'admin',
                                        forbiddenRedirect: {name: 'dashboard'}
                                    },
                                },
                            },
                            {
                                path: 'platform-fee/addFee',
                                name: 'addFee',
                                component: () => import('../components/Admin/platformFee/AddFee'),
                                meta: {
                                    auth: {
                                        roles: 'admin',
                                        forbiddenRedirect: {name: 'dashboard'}
                                    },
                                },
                            },
                            {
                                path: 'platform-fee/edit-fee/:id',
                                name: 'EditFee',
                                component: () => import('../components/Admin/platformFee/EditFee'),
                                meta: {
                                    auth: {
                                        roles: 'admin',
                                        forbiddenRedirect: {name: 'dashboard'}
                                    },
                                },
                            },
                            {
                                path: 'adminMessages',
                                name: 'MessagesAdmin',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Messages/MessagesAdmin'),
                            },
                            {
                                path: 'userMessages',
                                name: 'MessagesUser',
                                component: () => import(/* webpackChunkName: "group-finder" */'../components/Admin/Messages/MessagesUser'),
                            },
                            {
                                path: 'addCategory',
                                name: 'addCategory',
                                component: () => import('../components/Admin/Categories/AddCategory'),
                            },
                        ]
                    },

                    {
                        path: 'car/:id',
                        name: 'editUserCar',
                        component: () => import('../components/Finder/UserCar/UserCarEdit'),
                        meta: {
                            auth: true,
                        }
                    },
                    {
                        path: 'researches',
                        name: 'researchList',
                        component: () => import('../components/Finder/Research/ResearchList'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'carList',
                        name: 'carList',
                        component: () => import('../components/Finder/UserCar/UserCarsList'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'carList/:id',
                        name: 'carDetail',
                        component: () => import('../components/Finder/UserCar/UserCarDetail'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'profile',
                        name: 'userProfile',
                        component: () => import('../components/UserProfile/UserProfile'),
                        meta: {
                            auth: true,
                        },
                        redirect: {name: 'profilePersonalEdit'},
                        children: [
                            {
                                path: 'personalEdit',
                                name: 'profilePersonalEdit',
                                component: () => import('../components/UserProfile/ProfilePersonalEdit'),
                                meta: {
                                    auth: true,
                                },
                            }, {
                                path: 'merchantEdit',
                                name: 'profileMerchantEdit',
                                component: () => import('../components/UserProfile/ProfileMerchantEdit'),
                                meta: {
                                    auth: true,
                                },
                            }, {
                                path: 'passwordEdit',
                                name: 'profilePasswordEdit',
                                component: () => import('../components/UserProfile/ProfilePasswordEdit'),
                                meta: {
                                    auth: true,
                                },
                            },
                        ],
                    },
                    {
                        path: 'dashboard',
                        name: 'dashboard',
                        component: () => import('../components/Finder/Dashboard'),
                        meta: {
                            auth: true,
                        },
                    }, {
                        path: 'researchAlert',
                        name: 'researchAlert',
                        component: () => import('../components/Merchant/ResearchAlert'),
                        meta: {
                            auth: true,
                        },
                    }, {
                        path: 'researchAlert/list/:id',
                        name: 'ResearchAlertProposalList',
                        component: () => import('../components/Merchant/ResearchAlertProposalList'),
                        meta: {
                            auth: true,
                        },
                    }, {
                        path: 'catalog',
                        name: 'catalog',
                        component: () => import('../components/Merchant/Catalog'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'catalog/addProduct/:id?/:edit?',
                        name: 'addProduct',
                        component: () => import('../components/Merchant/AddProduct'),
                        meta: {
                            auth: true,
                        },
                    },

                    {
                        path: 'orders',
                        name: 'orders',
                        component: () => import('../components/Orders/Index'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'order/:id',
                        name: 'showProduct',
                        component: () => import('../components/Orders/ShowProduct'),
                        meta: {
                            auth: true,
                        },
                    },

                    {
                        path: 'alert/addAlert',
                        name: 'addAlert',
                        component: () => import('../components/Merchant/AddAlert'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'sales',
                        name: 'sales',
                        component: () => import('../components/Merchant/Sales'),
                        meta: {
                            auth: true,
                        },
                    },
                    {
                        path: 'notifications',
                        name: 'notifications',
                        component: () => import('../components/Finder/AllNotifications'),
                        meta: {
                            auth: true,
                        },
                    },
                ],
            },
            {
                path: '/confirmation',
                name: 'CreateAccountConfirmation',
                component: () => import('../components/SearchEngine/CreateAccountConfirmation'),
            }, {
                path: '/search',
                name: 'searchEngine',
                component: () => import('../components/SearchEngine/SearchEngine'),
                children: [
                    {
                        path: 'model',
                        name: 'searchCarModel',
                        component: () => import('../components/SearchEngine/SearchCarModel'),
                    },
                    {
                        path: 'category/:idCarPerso?',
                        name: 'searchCarPartsCategories',
                        component: () => import('../components/SearchEngine/SearchCarPartsCategories'),
                    },
                    {
                        // path: 'searchCarParts/:idCar',
                        path: 'parts',
                        name: 'searchCarParts',
                        component: () => import('../components/SearchEngine/SearchCarParts'),
                    },
                    {
                        path: 'createAccount',
                        name: 'createAccount',
                        component: () => import('../components/SearchEngine/CreateAccount'),
                    }, {
                        path: 'details/:idCar?/:custom?/:idCategory?/:idPart?',
                        name: 'SearchDetails',
                        component: () => import('../components/SearchEngine/SearchDetails'),
                    },

                ],
            },
            {
                path: '/merchantPage',
                name: 'MerchantPage',
                component: () => import('../components/MerchantSetup/MerchantPage'),
            },
            {
                path: '/newAccount',
                name: 'newAccount',
                component: () => import('../components/Auth/CreateAccount'),
                // meta: {
                //     auth: false,
                // },
            },
            {
                path: '/model',
                redirect: {name: 'searchCarModel '}
            },
            {
                path: '/merchant',
                name: 'MerchantSetup',
                component: () => import('../components/MerchantSetup/MerchantSetup'),
                children: [
                    {
                        path: 'settings/:type',
                        name: 'MerchantSettings',
                        component: () => import('../components/MerchantSetup/MerchantSettings'),
                    }, {
                        path: 'obvy',
                        name: 'ObvyConnection',
                        component: () => import('../components/MerchantSetup/ObvyConnection'),
                    }, {
                        path: 'confirmation',
                        name: 'MerchantConfirmation',
                        component: () => import('../components/MerchantSetup/MerchantConfirmation'),
                        meta: {
                            auth: true,
                        },
                    }, {
                        path: 'register/:idMerchant?/:firstname?/:lastname?',
                        name: 'register',
                        component: () => import('../components/Auth/Register'),
                        // meta: {
                        //     auth: false,
                        // },
                    },
                ],

            },
            {path: '/fr', redirect: '/'},
            {
                path: '*', beforeEnter: (to, from, next) => {
                    // reload same url before routing to home
                    // var url = window.location.href;
                    // if (url.indexOf('?') > -1){
                    //     window.location.href = '/'
                    // } else {
                    //     window.location.href = url+'?reload=1';
                    // }
                }
            },
        ],
    }
);

export default router;
