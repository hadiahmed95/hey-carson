import { createRouter, createWebHistory } from "vue-router";

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
import Reviews from "@/pages/client/Reviews.vue"
import ForgotPassword from "@/pages/ForgotPassword.vue";
import ResetPassword from "@/pages/ResetPassword.vue";
import ExpertSignup from "@/pages/expert/ExpertSignup.vue";
import ExpertClaimProfile from "@/pages/expert/ExpertClaimProfile.vue";
import GetMatched from "@/pages/client/GetMatched.vue";
import GetMatchedDetails from "@/components/client/getMatched/GetMatchedDetails.vue";
import GetMatchedBrief from "@/components/client/getMatched/GetMatchedBrief.vue";
import GetMatchedAccount from "@/components/client/getMatched/GetMatchedAccount.vue";
import LeadDetailsPage from "@/pages/expert/lead/LeadDetailsPage.vue";
import SignupStart from "@/components/expert/SignUp/SignupStart.vue";
import ContactInfo from "@/components/expert/SignUp/ContactInfo.vue";
import ProfessionalDetails from "@/components/expert/SignUp/ProfessionalDetails.vue";
import Experience from "@/components/expert/SignUp/Experience.vue";
import ClaimStart from "@/components/expert/claimProfile/ClaimStart.vue";
import ClaimVerification from "@/components/expert/claimProfile/ClaimVerification.vue";
import OnBoardingIntro from "@/pages/expert/onboarding/OnBoardingIntro.vue";
import ListingLayout from "@/layouts/ListingLayout.vue";
import SSOLogin from "@/pages/client/SSOLogin.vue";

// Centralized redirection function based on user role
export const redirectToDashboard = (user: any, router: any) => {
    if (user.role_id === 1) {
        router.push('/admin/dashboard')
    } else if (user.role_id === 2) {
        router.push('/client/dashboard')
    } else if (user.role_id === 3) {
        router.push('/expert/dashboard')
    }
}

const routes = [
    {
        path: '/',
        name: 'home',
        component: ClientLogin,
        meta: {
            title: 'shopexperts',
        }
    },

    {
        path: '/expert/login',
        name: 'expert-login',
        component: ExpertLogin,
        meta: {
            title: 'shopexperts - Expert Login',
        }
    },
    {
        path: '/client/login',
        name: 'client-login',
        component: ClientLogin,
        meta: {
            title: 'shopexperts - Client Login',
        }
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
        meta: {
            title: 'shopexperts - Forgot Password',
        }
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: ResetPassword,
        meta: {
            title: 'shopexperts - Reset Password',
        }
    },
    {
        path: "/client/signup",
        name: "client-signup",
        component: ClientSignup,
        meta: {
            title: 'shopexperts - Client Signup',
        }
    },
    {
        path: "/expert/signup",
        component: ExpertSignup,
        meta: {
            title: 'shopexperts - Expert Signup',
        },
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
        meta: {
            title: 'shopexperts - Expert Claim Profile',
        },
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
        meta: {
            title: 'shopexperts - Client Quote Request',
        },
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
        meta: {
            title: 'shopexperts - Client Get Matched',
        },
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
        meta: {
            title: 'shopexperts - Expert Dashboard',
        },
        children: [
            {
                path: 'onboarding',
                name: 'expert-onboarding-intro',
                component: OnBoardingIntro,
            },
            {
                path: 'listing-settings',
                component: ListingLayout,
                children: [
                    {
                        path: '',
                        redirect: '/expert/listing-settings/personal-details',
                    },
                    {
                        path: 'personal-details',
                        name: 'listing-settings-personal-details',
                        alias: '/expert/listing-settings/personal-details',
                        component: () => import('@/pages/expert/listing-settings/PersonalDetails.vue'),
                    },
                    {
                        path: 'service-categories',
                        name: 'listing-settings-service-categories',
                        alias: '/expert/listing-settings/service-categories',
                        component: () => import('@/pages/expert/listing-settings/OfferedServices.vue'),
                    },
                    {
                        path: 'packaged-services',
                        name: 'listing-settings-packaged-services',
                        alias: '/expert/listing-settings/packaged-services',
                        component: () => import('@/pages/expert/listing-settings/PackagedServices.vue'),
                    },
                    {
                        path: 'customer-stories',
                        name: 'listing-settings-customer-stories',
                        alias: '/expert/listing-settings/customer-stories',
                        component: () => import('@/pages/expert/listing-settings/CustomerStories.vue'),
                    },
                    {
                        path: 'faq',
                        name: 'listing-settings-faq',
                        alias: '/expert/listing-settings/faq',
                        component: () => import('@/pages/expert/listing-settings/Faq.vue'),
                    }
                ]
            },
            {
                path: "dashboard",
                name: "expert-dashboard",
                component: ExpertDashboard,
                meta: {
                    title: 'shopexperts - Expert Dashboard',
                },
            },
            {
                path: "leads",
                name: "expert-leads",
                component: ExpertLeads,
                meta: {
                    title: 'shopexperts - Expert Leads',
                },
            },
            {
                path: "lead/:leadId",
                name: "expert-lead-collection-changes",
                component: LeadDetailsPage,
                meta: {
                    title: 'shopexperts - Lead Details',
                },
            },
            {
                path: "lead/:leadId/chatroom",
                name: "expert-lead-chatroom",
                component: ExpertChatroom,
                meta: {
                    title: 'shopexperts - Lead Details',
                },
            },

            {
                path: "my-listings",
                name: "expert-my-listings",
                component: ExpertListing,
                meta: {
                    title: 'shopexperts - Expert Listings',
                },
            },
            {
                path: "reviews",
                name: "expert-reviews",
                component: ExpertReviews,
                meta: {
                    title: 'shopexperts - Expert Reviews',
                },
            },
            {
                path: "latest-reviews",
                name: "expert-latest-reviews",
                component: ExpertReviews,
                meta: {
                    title: 'shopexperts - Expert Reviews',
                },
            },
            {
                path: "settings",
                name: "expert-settings",
                component: ExpertSettings,
                meta: {
                    title: 'shopexperts - Expert Settings',
                },
            },
        ]
    },
    {
        path: "/admin",
        component: AdminLayout,
        meta: {
            title: 'shopexperts - Admin',
        },
        children: [
            {
                path: "dashboard",
                name: "admin-dashboard",
                component: AdminDashboard,
                meta: {
                    title: 'shopexperts - Admin Dashboard',
                },
            },
            {
                path: "listings",
                name: "admin-listings",
                component: AdminListings,
                meta: {
                    title: 'shopexperts - Admin Listings',
                },
            },
            {
                path: "leads",
                name: "admin-leads",
                component: AdminLeads,
                meta: {
                    title: 'shopexperts - Admin Leads',
                },
            },
            {
                path: "quotes-sent",
                name: "admin-quotes-sent",
                component: AdminQuoteSent,
                meta: {
                    title: 'shopexperts - Admin Quotes',
                },
            },
            {
                path: "transactions",
                name: "admin-transactions",
                component: AdminTransactions,
                meta: {
                    title: 'shopexperts - Admin Transactions',
                },
            },
            {
                path: "reviews",
                name: "admin-reviews",
                component: AdminReviews,
                meta: {
                    title: 'shopexperts - Admin Reviews',
                },
            },
            {
                path: "referrals",
                name: "admin-referrals",
                component: AdminReferrals,
                meta: {
                    title: 'shopexperts - Admin Referrals',
                },
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
                meta: {
                    title: 'shopexperts - Client Dashboard',
                },
            },
            {
                path: "my-requests",
                name: "my-requests",
                component: ClientRequest,
                meta: {
                    title: 'shopexperts - Client Requests',
                },
            },
            {
                path: '/client/request/:requestId',
                name: 'RequestDetails',
                meta: {
                    title: 'shopexperts - Request Details',
                },
                component: () => import('@/pages/client/RequestDetail.vue'),
                props: true
            },
            {
                path: "packaged-services",
                name: "packaged-services",
                component: PackagedServices,
                meta: {
                    title: 'shopexperts - Client Packaged Services',
                },
            },
            {
                path: "reviews",
                name: "reviews",
                component: Reviews,
                meta: {
                    title: 'shopexperts - Client Reviews',
                },
            },
            {
                path: "transactions",
                name: "client-transactions",
                component: ClientTransactions,
                meta: {
                    title: 'shopexperts - Client Transactions',
                },
            },
            {
                path: "settings",
                name: "client-settings",
                component: ClientSettings,
                meta: {
                    title: 'shopexperts - Client Settings',
                },
            },
        ]
    }
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, _from, next) => {
    document.title = (to.meta.title as string) || 'shopexperts';
    next();
});


export default router;
