import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from '@/store/auth.ts'

import ExpertLayout from "@/layouts/ExpertLayout.vue";
import ExpertDashboard from "@/pages/expert/Dashboard.vue";
import ExpertLeads from "@/pages/expert/Leads.vue";
import ExpertListing from "@/pages/expert/MyListing.vue";
import ExpertReviews from "@/pages/expert/Reviews.vue";
import ExpertChatroom from "@/components/expert/cards/Chatroom.vue";

import AdminLayout from "@/layouts/AdminLayout.vue";
import AdminDashboard from "@/pages/admin/Dashboard.vue";
import AdminListings from "@/pages/admin/Listings.vue";
import AdminLeads from "@/pages/admin/Leads.vue";
import AdminQuoteSent from "@/pages/admin/QuoteSent.vue";
import AdminReferrals from "@/pages/admin/Referrals.vue";
import AdminReviews from "@/pages/admin/Reviews.vue";
import AdminTransactions from "@/pages/admin/Transactions.vue";
import ClientTransactions from "@/pages/client/Transactions.vue";
import ClientSettings from "@/pages/client/Settings.vue";
import ExpertSettings from "@/pages/expert/Settings.vue";

import ClientLayout from "@/layouts/ClientLayout.vue";
import ClientDashboard from "@/pages/client/Dashboard.vue";
import ClientRequest from "@/pages/client/MyRequests.vue";
import PackagedServices from "@/pages/client/PackagedServices.vue";
import ClientSignup from "@/pages/client/ClientSignup.vue";
import ClientFreeQuote from "@/pages/client/ClientFreeQuote.vue";
import PreferredExpert from "@/components/client/freeQuote/PreferredExpert.vue";
import StoreOrAgencyDetails from "@/components/client/freeQuote/StoreOrAgencyDetails.vue";
import ProjectBrief from "@/components/client/freeQuote/ProjectBrief.vue";
import AccountInfo from "@/components/client/freeQuote/AccountInfo.vue";

import ExpertLogin from "@/components/expert/ExpertLogin.vue"
import ClientLogin from "@/components/client/ClientLogin.vue"
import AdminLogin from "@/components/admin/AdminLogin.vue"
import Reviews from "@/pages/client/Reviews.vue"
import ForgotPassword from "@/pages/ForgotPassword.vue";
import ResetPassword from "@/pages/ResetPassword.vue";
import ExpertSignup from "@/pages/expert/ExpertSignup.vue";
import ExpertClaimProfile from "@/pages/expert/ExpertClaimProfile.vue";
import GetMatched from "@/pages/client/GetMatched.vue";
import GetMatchedDetails from "@/components/client/getMatched/GetMatchedDetails.vue";
import GetMatchedBrief from "@/components/client/getMatched/GetMatchedBrief.vue";
import GetMatchedAccount from "@/components/client/getMatched/GetMatchedAccount.vue";
import CollectionChanges from "@/pages/expert/lead/CollectionChanges.vue";
import SignupStart from "@/components/expert/SignUp/SignupStart.vue";
import ContactInfo from "@/components/expert/SignUp/ContactInfo.vue";
import ProfessionalDetails from "@/components/expert/SignUp/ProfessionalDetails.vue";
import Experience from "@/components/expert/SignUp/Experience.vue";
import ClaimStart from "@/components/expert/claimProfile/ClaimStart.vue";
import ClaimVerification from "@/components/expert/claimProfile/ClaimVerification.vue";
import OnBoardingIntro from "@/pages/expert/onboarding/OnBoardingIntro.vue";
import OnboardingLayout from "@/pages/expert/onboarding/Layout.vue";
import SSOLogin from "@/pages/client/SSOLogin.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: ClientLogin,
    },

    {
        path: '/expert/login',
        name: 'expert-login',
        component: ExpertLogin,
    },
    {
        path: '/client/login',
        name: 'client-login',
        component: ClientLogin,
    },
    {
        path: '/admin/login',
        name: 'admin-login',
        component: AdminLogin,
    },
    {
        path: '/sso-login',
        name: 'SSOLogin',
        component: SSOLogin,
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: ResetPassword,
    },
    {
        path: "/client/signup",
        name: "client-signup",
        component: ClientSignup,
    },
    {
        path: "/expert/signup",
        component: ExpertSignup,
        children: [
            {
                path: "",
                name: "expert-signup-start",
                component: SignupStart
            },
            {
                path: "contact-info",
                name: "expert-signup-contact",
                component: ContactInfo
            },
            {
                path: "professional-details",
                name: "expert-signup-professional",
                component: ProfessionalDetails
            },
            {
                path: "experience",
                name: "expert-signup-experience",
                component: Experience
            },
        ],
    },
    {
        path: "/expert/claim-profile",
        component: ExpertClaimProfile,
        children: [
            {
                path: "",
                name: "expert-claim-start",
                component: ClaimStart
            },
            {
                path: "verify",
                name: "expert-claim-verify",
                component: ClaimVerification
            }
        ]
    },
    {
        path: "/client/free-quote",
        component: ClientFreeQuote,
        children: [
            {
                path: "",
                name: "client-claim-expert",
                component: PreferredExpert
            },
            {
                path: "agency-details",
                name: "store-details",
                component: StoreOrAgencyDetails
            },
            {
                path: "project-brief",
                name: "project-brief",
                component: ProjectBrief
            },
            {
                path: "account-info",
                name: "account-info",
                component: AccountInfo
            }
        ]
    },
    {
        path: "/client/get-matched",
        component: GetMatched,
        children: [
            {
                path: "",
                name: "get-matched-details",
                component: GetMatchedDetails
            },
            {
                path: "project-brief",
                name: "get-matched-brief",
                component: GetMatchedBrief
            },
            {
                path: "account-info",
                name: "get-matched-account",
                component: GetMatchedAccount
            }
        ]
    },
    {
        path: "/expert",
        component: ExpertLayout,
        children: [
            {
                path: 'onboarding',
                name: 'expert-onboarding-intro',
                component: OnBoardingIntro,
            },

            {
                path: 'onboarding-steps',
                component: OnboardingLayout,
                children: [
                    {
                        path: '',
                        redirect: '/expert/onboarding-steps/personalDetails',
                    },
                    {
                        path: 'personalDetails',
                        name: 'expert-onboarding-personalDetails',
                        alias: '/expert/onboarding/personalDetails',
                        component: () => import('@/pages/expert/onboarding/PersonalDetails.vue'),
                    },
                    {
                        path: 'serviceCategories',
                        name: 'expert-onboarding-serviceCategories',
                        alias: '/expert/onboarding/serviceCategories',
                        component: () => import('@/pages/expert/onboarding/ServiceCategories.vue'),
                    },
                    {
                        path: 'packagedServices',
                        name: 'expert-onboarding-packagedServices',
                        alias: '/expert/onboarding/packagedServices',
                        component: () => import('@/pages/expert/onboarding/PackagedServices.vue'),
                    },
                    {
                        path: 'customerStories',
                        name: 'expert-onboarding-customerStories',
                        alias: '/expert/onboarding/customerStories',
                        component: () => import('@/pages/expert/onboarding/CustomerStories.vue'),
                    },
                    {
                        path: 'faq',
                        name: 'expert-onboarding-faq',
                        alias: '/expert/onboarding/faq',
                        component: () => import('@/pages/expert/onboarding/Faq.vue'),
                    }
                ]
            },
            {
                path: "dashboard",
                name: "expert-dashboard",
                component: ExpertDashboard,
            },
            {
                path: "leads",
                name: "expert-leads",
                component: ExpertLeads,
            },
            {
                path: "lead/:leadId",
                name: "expert-lead-collection-changes",
                component: CollectionChanges,
            },
            {
                path: "lead/:leadId/chatroom",
                name: "expert-lead-chatroom",
                component: ExpertChatroom,
            },
            {
                path: "my-listings",
                name: "expert-my-listings",
                component: ExpertListing,
            },
            {
                path: "reviews",
                name: "expert-reviews",
                component: ExpertReviews,
            },
            {
                path: "latest-leads",
                name: "expert-latest-leads",
                component: ExpertReviews,
            },
            {
                path: "latest-reviews",
                name: "expert-latest-reviews",
                component: ExpertReviews,
            },
            {
                path: "settings",
                name: "expert-settings",
                component: ExpertSettings,
            },
        ]
    },
    {
        path: "/admin",
        component: AdminLayout,
        meta: { requiresAuth: true, requiredRole: 'admin' }, // ONLY admin routes protected
        children: [
            {
                path: "dashboard",
                name: "admin-dashboard",
                component: AdminDashboard,
            },
            {
                path: "listings",
                name: "admin-listings",
                component: AdminListings,
            },
            {
                path: "leads",
                name: "admin-leads",
                component: AdminLeads,
            },
            {
                path: "quotes-sent",
                name: "admin-quotes-sent",
                component: AdminQuoteSent,
            },
            {
                path: "transactions",
                name: "admin-transactions",
                component: AdminTransactions,
            },
            {
                path: "reviews",
                name: "admin-reviews",
                component: AdminReviews,
            },
            {
                path: "referrals",
                name: "admin-referrals",
                component: AdminReferrals,
            },
        ]
    },
    {
        path: "/client",
        component: ClientLayout,
        children: [
            {
                path: "dashboard",
                name: "client-dashboard",
                component: ClientDashboard,
            },
            {
                path: "my-requests",
                name: "my-requests",
                component: ClientRequest,
            },
            {
                path: '/client/request/:requestId',
                name: 'RequestDetails',
                component: () => import('@/pages/client/RequestDetail.vue'),
                props: true
            },
            {
                path: "packaged-services",
                name: "packaged-services",
                component: PackagedServices,
            },
            {
                path: "reviews",
                name: "reviews",
                component: Reviews,
            },
            {
                path: "transactions",
                name: "client-transactions",
                component: ClientTransactions,
            },
            {
                path: "settings",
                name: "client-settings",
                component: ClientSettings,
            },
        ]
    }
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation Guards - ONLY for admin routes
router.beforeEach((to, _from, next) => {
    if (to.path.startsWith('/admin') && to.meta.requiresAuth) {
        const authStore = useAuthStore()
        
        if (!authStore.token || !authStore.user) {
            next('/admin/login')
            return
        }
        
        if (to.meta.requiredRole) {
            const userRole = authStore.user.role?.name?.toLowerCase()
            const requiredRole = to.meta.requiredRole as string
            
            if (userRole !== requiredRole) {
                next('/admin/login')
                return
            }
        }
    }
    
    next()
})

export default router
