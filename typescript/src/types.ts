import type {Component} from "vue";

export interface IButton {
    title?: string
    icon?: string | Component
    iconPosition?: 'left' | 'right'
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'icon'
    size?: 'sm' | 'md' | 'lg' | 'icon'
    rounded?: 'sm' | 'md' | 'full'
    fullWidth?: boolean
    ariaLabel?: string
    type?: 'button' | 'submit' | 'reset'
}

export interface INav {
    label: string
    icon: string
    path: string
    isLinkOnly?: boolean
}

export interface IDropdown {
    label: string
    value: string
}

export interface ILead {
    id: number,
    name: string,
    displayUrl: string,
    website: string,
    email: string,
    plan: string,
    directChatCount: number,
    quoteRequestCount: number,
    lifetimeSpendCount: string,
    joinedOn: string,
}

export interface IListing {
    id: number,
    name: string
    displayUrl: string | null
    avatarInfo?: {
        initials: string
        bgColor: string
    }
    type: string
    email: string
    storeTitle: string
    storeUrl: string
    country: string
    jobTitle: string
    language: string
    minimumProjectBudget: string
    status: string
    statusUpdatedAt: string
    servicesOffered: string[]
    totalReviews?: number
    averageRating?: number
    expertData?: any
    onLoginAs?: () => void
}

export interface IReview {
    expert: {
        id: number;
        name: string;
        photo: string;
        company_type: string;
        recurringExpert: boolean;
        isShopexpertUser: boolean;
        rank: string;
        storeUrl?: string;
        storeTitle?: string;
    };
    reviewer: {
        id: number;
        name: string;
        photo: string;
        storeTitle: string;
        storeUrl: string;
        recurringClient: boolean;
        rating: number;
        quality?: number;
        communication?: number;
        timeToStart?: number;
        valueForMoney?: number;
        comment: string;
        recommendation: string;
        isShopexpertUser: boolean;
        valueRange?: string; // 'anonymous' or ''
    };
    id?: number;
    projectId?: number;
    projectTitle?: string;
    postedAt: string;
    response: string;
    projectValue?: string;
    reviewSource?: string;
    status?: string;
}

export interface ITransaction {
    type: string
    paymentMethod: string
    paymentDate: string
    transactionAmount: string
    prepaidHours?: string
    client?: {
        name: string
        email: string
        avatar: string
        plan: string
    }
    expert?: {
        name: string
        email: string
        avatar: string
    }
}

export interface ITranscationn {
    id: number;
    type: string;
    price?: number;
    total: number;
    project?: {
        id: number;
        name: string;
    };
    expert?: {
        first_name: string;
        last_name: string;
        photo: string;
        email: string;
        profile: {
            role: string;
        }
    };
    client?: {
        first_name: string;
        last_name: string;
        email: string;
        photo: string;
        shopify_plan: string;
    };
    created_at: string
}

export interface IStatusHistory {
    id: number,
    action: string,
    created_at: string,
}

export interface IQuote { //Todo: We will replace this with IQuotee soon
    title: string
    link: string
    hourlyRate: string
    estimatedTime: string
    deadline: string
    total: string
    status: string
    sentDate: string
    paidDate?: string
    rejectedDate?: string
    client: {
        name: string
        email: string
        avatar: string
        plan: string
    }
    expert: {
        name: string
        email: string
        avatar: string
    }
}

export interface IReferral {
    referrer: {
        name: string
        email: string
        avatar: string
    }
    referral: {
        name: string
        email: string
        avatar: string
        shopifyPlan: string
    }
    createdAccount: string
    completedProject: string
    amount: string
    status: string
    referredOn: string
    approvedOn?: string | null
    rejectedOn?: string | null
}

export interface ILeaderboard {
    rank: number
    name: string
    role: string
    rating?: string
    reviews?: number
    avatar: string
    responseTime?: string
}

export interface ICollectionQuote {
    id: number
    type: string
    hourlyRate: number
    status: string
    estimatedTime: string
    deadline: string
    totalPayment: string
    createdAt: string
}

export interface IExpert { //Todo: We will replace this with IExpertt soon
    id?: number;
    name: string;
    role: string;
    avatarUrl: string;
    submittedDate: string;
    initialStatus?: string;
    rating?: string;
    numberOfReviews?: number;
    pendingQuote?: string;
}

export interface IExpertMatched extends IExpert {
    title: string;
    request_id: number;
    request_type: string;
    developerRank: string;
}

export interface IExpertDirectMessage extends IExpert {
    request_id: number;
    request_type: string;
    developerRank: string;
}

export interface IExpertQuote {
    id: number;
    title: string;
    request_id: number;
    request_type: string;
    expert: IExpertQuoteItem;
    additionalExperts?: IExpertQuoteItem[];
}

export interface IFeaturedExpert extends IExpert {
    hourly_rate: string | number
    services: string[]
}

export interface IExpertQuoteItem extends IExpert {
    hourlyRate?: number; // ← optional
    estimatedTime?: string;
    deadline?: string;
    totalToPay?: string;
    quoteStatus?: string;
}

export interface IMessage {
    id: number
    content: string
    createdAt: string
    project: {
        title: string
    }
    expert: {
        name: string
        avatarUrl: string
    }
}

export interface INotification {
    id: number
    content: string
    createdAt: string
    project: {
        title: string
    }
}

export interface IQuotee {
    id: number
    type: string
    hours: number
    deadline: string
    rate: number
    status: string
    created_at: string
}

export interface IExpertt {
    id: number;
    first_name: string
    last_name: string
    photo: string
    created_at: string
    company_type: string
    quotes?: IQuotee[]
    pendingQuote?: string
    profile: IProfile
    reviews_stat?: {
        rating: number
        reviews_count: number
    }
    service_categories?: string[] | null
}

export interface IProfile {
    role: string
    hourly_rate?: number | null
    country: string
}

export interface IRevieww {
    id: number;
    reviewer: {
        id: number;
        name: string;
        photo: string;
        storeTitle: string;
        storeUrl: string;
        recurringClient: boolean;
        rating: number;
        comment: string;
        recommendation: string;
        isShopexpertUser: boolean;
        // Additional properties from IReview
        quality?: number;
        communication?: number;
        timeToStart?: number;
        valueForMoney?: number;
        valueRange?: string; // 'anonymous' or ''
    };
    expert: {
        id: number;
        name: string;
        photo: string;
        company_type: string;
        recurringExpert: boolean;
        isShopexpertUser: boolean;
        rank: string;
        storeUrl: string;
        storeTitle: string;
    };
    postedAt: string;
    projectValue: string;
    reviewSource: string;
    response: string;
    status: string;
    // Additional properties from IReview
    projectId?: number;
    projectTitle?: string;
}

export interface ReviewRequestsResponse {
    pending_review_requests: IRevieww[];
}

export interface ClientReviewsResponse {
    written_reviews: IRevieww[];
}

export interface ExpertReviewsResponse {
    reviews: IRevieww[];
}

export interface IRequest {
    id: number
    type: string
    created_at: string
    expert: IExpertt
    pendingQuote?: string //Todo: Need to remove this
    project: {
        name: string
        description?: string
        status?: string | null
        is_additional_experts: boolean
        history: IStatusHistory[]
        invoices: ITranscationn[]
        active_assignment?: IAssignment
        additional_expert_profiles?: IExpertt[] | null
        quotes?: IQuotee[];
    }
}

export interface IAssignment {
    is_active: boolean
    offers: IQuotee[]
}

export interface IPackagedService {
    id: number
    title: string
    price: number
    delivery_time: string
    thumbnail: string
}

export interface IShopifyProductUpdate {
    id: number
    title: string
    category: number
    description: string
    published_at: string
}

export interface IReviewRequest {
    id: number
    expert: IExpertt
    message: string
    hired_on_shopexperts: boolean
    repeated_client: boolean
    created_at: string
}

export interface IPayment {
    id: number;
    click_id?: string | null;
    user_id: number;
    project_id?: number | null;
    offer_id?: number | null;
    expert_id?: number | null;
    stripe_payment_id?: string | null;
    amount: number;
    price: number;
    total: number;
    status: string;
    created_at?: string | null;
    updated_at?: string | null;
    deleted_at?: string | null;
}

export interface Card {
    id: number,
    card_type: string
    last_digits: string
    exp_date: string
    last_used: string
    default: boolean
}

export interface ILeadd {
    id: number,
    type: string
    created_at: string
    project: {
        name: string
    }
    client: {
        first_name: string
        last_name: string
        email: string
        url?: string
        photo?: string
        shopify_plan?: string
    }
}

export interface IExpertStat {
    leads: number
    cta_clicks: number
    listing_page_visits: number
    unique_visits: number
}