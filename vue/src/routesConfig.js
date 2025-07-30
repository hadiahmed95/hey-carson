
export const routesConfig = [
    {
        path: "/",
        redirect: { name: "client-login" }
    },

    {
        path: "/reset-password",
        name: "reset-password",
        component: () => import('./views/auth/ResetPasswordPage.vue'),
        meta: {
            title: 'shopexperts - Reset Password',
            metaTags: [
                {
                    name: 'description',
                    content: 'Reset your shopexperts password. Enter your email address and we’ll send you a link to reset your password.'
                },
                {
                    property: 'og:description',
                    content: 'Reset your shopexperts password. Enter your email address and we’ll send you a link to reset your password.'
                }
            ]
        }
    },

    {
      path: '/sso-login',
      name: 'SSOLogin',
      component: () => import('./views/auth/SSOLogin.vue'),
    },

    /**
     * ADMIN DASHBOARD
     */

    {
        path: "/admin",
        name: "admin",
        component: () => import('./views/admin/DashboardPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Admin Dashboard',
        }
    },
    {
        path: "/admin/login",
        name: "admin-login",
        component: () => import('./views/auth/admin/LoginPage.vue'),
        meta: {
            title: 'shopexperts - Admin Login',
            metaTags: [
                {
                    name: 'description',
                    content: 'Secure login to admin dashboord.'
                },
                {
                    property: 'og:description',
                    content: 'Secure login to admin dashboord.'
                }
            ]
        }
    },
    {
        path: "/admin/projects",
        name: "admin-projects",
        component: () => import('./views/admin/ProjectsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Projects',
        }
    },
    {
        path: "/admin/projects/:id",
        name: "admin-project",
        component: () => import('./views/admin/ProjectPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Project',
        }
    },
    {
        path: "/admin/clients",
        name: "admin-clients",
        component: () => import('./views/admin/ClientsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Clients',
        }
    },
    {
        path: "/admin/client/:id",
        name: "admin-client",
        component: () => import('./views/admin/ClientProfilePage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Client',
        }
    },
    {
        path: "/admin/experts",
        name: "admin-experts",
        component: () => import('./views/admin/ExpertsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Experts',
        }
    },
    {
        path: "/admin/expert/:id",
        name: "admin-expert",
        component: () => import('./views/admin/ExpertProfilePage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Expert',
        }
    },
    {
        path: "/admin/payouts",
        name: "admin-payouts",
        component: () => import('./views/admin/PayoutsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Payouts',
        }
    },
    {
        path: "/admin/questions",
        name: "admin-questions",
        component: () => import('./views/admin/QuestionsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Questions',
        }
    },
    {
        path: "/admin/questions/:id",
        name: "admin-question",
        component: () => import('./views/admin/QuestionPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Question',
        }
    },
    {
        path: "/admin/transactions",
        name: "admin-transactions",
        component: () => import('./views/admin/TransactionsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Transactions',
        }
    },
    {
        path: "/admin/settings",
        name: "admin-settings",
        component: () => import('./views/admin/SettingsPage.vue'),
        meta: {
            authRequired: true,
            isAdmin: true,
            title: 'shopexperts - Settings',
        }

    },

    /**
     * ADMIN AUTH
     */

    {
        path: "/admin/login",
        name: "admin-login",
        component: () => import('./views/auth/admin/LoginPage.vue'),
        meta: {
            title: 'shopexperts - Admin Login',
            metaTags: [
                {
                    name: 'description',
                    content: 'Secure login to admin dashboord.'
                },
                {
                    property: 'og:description',
                    content: 'Secure login to admin dashboord.'
                }
            ]
        }
    },

    /*
    {
        path: "/admin/payouts",
        name: "admin-payouts",
        component: () => import('./views/admin/PayoutsPage.vue')
    },
    {
        path: "/admin/project-ideas",
        name: "admin-project-ideas",
        component: () => import('./views/admin/ProjectIdeasPage.vue')
    },
    {
        path: "/admin/questions",
        name: "admin-questions",
        component: () => import('./views/admin/QuestionsPage.vue')
    },*/

    /**
     * CLIENT DASHBOARD
     */

    {
        path: "/client",
        name: "client-dashboard",
        component: () => import('./views/client/DashboardPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Dashboard',
        }
    },
    {
        path: "/client/project/:id",
        name: "client-project",
        component: () => import('./views/client/ProjectPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Project',
        }
    },
    {
        path: "/client/pricing",
        name: "client-pricing",
        component: () => import('./views/client/PricingPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Pricing',
        }
    },
    {
        path: "/client/settings",
        name: "client-settings",
        component: () => import('./views/client/SettingsPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Settings',
        }
    },
    {
        path: "/client/team",
        name: "client-team",
        component: () => import('./views/client/TeamPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Team',
        }
    },
    {
        path: "/client/project-ideas",
        name: "client-project-ideas",
        component: () => import('./views/client/ProjectIdeas.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Project Ideas',
        }
    },
    {
        path: "/client/questions",
        name: "client-questions",
        component: () => import('./views/client/QuestionsPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Questions',
        }
    },
    {
        path: "/client/transactions",
        name: "old-client-transactions",
        component: () => import('./views/client/TransactionsPage.vue'),
        meta: {
            authRequired: true,
            isClient: true,
            title: 'shopexperts - Transactions',
        }
    },


    {
        path: "/client/login",
        name: "client-login",
        component: () => import('./views/auth/client/LoginPage.vue'),
        meta: {
            title: 'shopexperts - Customer Login',
            metaTags: [
                {
                    name: 'description',
                    content: 'Securely log in to your shopexperts account to manage your projects, track progress, and access exclusive client resources.'
                },
                {
                    property: 'og:description',
                    content: 'Securely log in to your shopexperts account to manage your projects, track progress, and access exclusive client resources.'
                }
            ]
        }
    },
    {
        path: "/client/register",
        name: "client-register",
        component: () => import('./views/auth/client/RegisterPage.vue'),
        meta: {
            title: 'shopexperts - Create a Free Account',
            metaTags: [
                {
                    name: 'description',
                    content: 'Register for a free shopexperts account to start managing your projects with ease and connect with leading Shopify developers & designers.'
                },
                {
                    property: 'og:description',
                    content: 'Register for a free shopexperts account to start managing your projects with ease and connect with leading Shopify developers & designers.'
                }
            ]
        }
    },
    {
        path: "/client/new-project",
        name: "client-new-project",
        component: () => import('./views/auth/client/NewProjectPage.vue'),
        meta: {
            title: 'shopexperts  - Submit a Project',
            metaTags: [
                {
                    name: 'description',
                    content: 'Kickstart your next project with shopexperts. Connect with the world’s best Shopify developers & designers in minutes. Rates from $80/hour.'
                },
                {
                    property: 'og:description',
                    content: 'Kickstart your next project with shopexperts. Connect with the world’s best Shopify developers & designers in minutes. Rates from $80/hour.'
                }
            ]
        }

    },
    {
        path: "/client/forgot-password",
        name: "client-forgot-password",
        component: () => import('./views/auth/client/ForgotPasswordPage.vue'),
        meta: {
            title: 'shopexperts - Password Reset',
            metaTags: [
                {
                    name: 'description',
                    content: 'Securely reset your shopexperts account password, to access our portal.'
                },
                {
                    property: 'og:description',
                    content: 'Securely reset your shopexperts account password, to access our portal.'
                }
            ]
        }
    },

    /*
    {
        path: "/client/team",
        name: "old-client-team",
        component: () => import('./views/client/old/TeamPage.vue')
    },
    {
        path: "/client/team/full",
        name: "old-client-team-full",
        component: () => import('./views/client/old/TeamPage2.vue')
    },
    {
        path: "/client/referrals",
        name: "old-client-referrals",
        component: () => import('./views/client/old/ReferPage.vue')
    },
    {
        path: "/client/transactions",
        name: "old-client-transactions",
        component: () => import('./views/client/old/TransactionsPage.vue')
    },
    {
        path: "/client/project-ideas-a",
        name: "old-client-project-ideas-a",
        component: () => import('./views/client/old/ProjectIdeasA.vue')
    },
    {
        path: "/client/project-ideas-b",
        name: "old-client-project-ideas-b",
        component: () => import('./views/client/old/ProjectIdeasB.vue')
    },
    */

    /**
     * EXPERT DASHBOARD
     */

    {
        path: "/expert",
        name: "expert-dashboard",
        component: () => import('./views/expert/DashboardPage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Dashboard',
        }
    },
    {
        path: "/expert/new-dashboard",
        name: "new-expert-dashboard",
        component: () => import('./views/expert/new-dashboard/DashboardPage.vue'),
        meta: {
            authRequired: false,
            isExpert: false,
            title: 'shopexperts - New Dashboard',
        }
    },
    {
        path: "/expert/leads",
        name: "expert-leads",
        component: () => import('./views/expert/new-dashboard/Leads.vue'),
        meta: {
            authRequired: false,
            isExpert: false,
            title: 'shopexperts - My Leads',
        }
    },
    {
        path: "/expert/available",
        name: "expert-available",
        component: () => import('./views/expert/AvailablePage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Available Projects',
        }
    },
    {
        path: "/expert/project/:id",
        name: "expert-project",
        component: () => import('./views/expert/ProjectPage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Project',
        }
    },
    {
      path: "/expert/questions",
      name: "expert-questions",
      component: () => import('./views/expert/QuestionsPage.vue'),
      meta: {
        authRequired: true,
        isExpert: true,
        title: 'shopexperts - Questions',
      }
    },
    {
        path: "/expert/payouts",
        name: "expert-payouts",
        component: () => import('./views/expert/PayoutsPage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Payouts',
        }
    },
    {
        path: "/expert/settings",
        name: "expert-settings",
        component: () => import('./views/expert/SettingsPage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Settings',
        }
    },{
        path: "/expert/profile",
        name: "expert-profile",
        component: () => import('./views/expert/ProfilePage.vue'),
        meta: {
            authRequired: true,
            isExpert: true,
            title: 'shopexperts - Profile',
        }
    },

    /**
     * EXPERT AUTH
     */

    {
        path: "/expert/login",
        name: "expert-login",
        component: () => import('./views/auth/expert/LoginPage.vue'),
        meta: {
            title: 'shopexperts - Expert Login',
            metaTags: [
                {
                    name: 'description',
                    content: 'Securely log in to your shopexperts Expert account to manage your projects.'
                },
                {
                    property: 'og:description',
                    content: 'Securely log in to your shopexperts Expert account to manage your projects.'
                }
            ]
        }
    },
    {
        path: "/expert/register",
        name: "expert-register",
        component: () => import('./views/auth/expert/RegisterPage.vue'),
        meta: {
            title: 'shopexperts - Become a shopexpert',
            metaTags: [
                {
                    name: 'description',
                    content: 'Join the shopexperts talent network to work with top-tier Shopify businesses.'
                },
                {
                    property: 'og:description',
                    content: 'Join the shopexperts talent network to work with top-tier Shopify businesses.'
                }
            ]
        }
    },
    {
        path: "/expert/forgot-password",
        name: "expert-forgot-password",
        component: () => import('./views/auth/expert/ForgotPasswordPage.vue'),
        meta: {
            title: 'shopexperts - Password Reset',
            metaTags: [
                {
                    name: 'description',
                    content: 'Securely reset your shopexperts account password, to access our portal.'
                },
                {
                    property: 'og:description',
                    content: 'Securely reset your shopexperts account password, to access our portal.'
                }
            ]
        }
    },

    /*
    {
        path: "/expert/team",
        name: "expert-team",
        component: () => import('./views/expert/old/TeamPage.vue')
    },
    {
        path: "/expert/team/full",
        name: "expert-team-full",
        component: () => import('./views/expert/old/TeamPage2.vue')
    },
    {
        path: "/expert/available/projects",
        name: "expert-available-projects",
        component: () => import('./views/expert/old/AvailableProjectsPage.vue')
    },
    {
        path: "/expert/referrals",
        name: "expert-referrals",
        component: () => import('./views/expert/old/ReferPage.vue')
    },
    */
]
