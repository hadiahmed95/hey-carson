import { useLoaderStore } from '@/store/loader';
import Profile from "@/assets/icons/dummy-profile.png";

export function handleImgError(event: Event) {
    const target = event.target as HTMLImageElement
    target.src = Profile
}

export function capitalize(text: string): string {
    if (!text) return "";
    return text.charAt(0).toUpperCase() + text.slice(1);
}

export async function withLoader<T>(callback: () => Promise<T>): Promise<T> {
    const loader = useLoaderStore();
    loader.isLoading = true;
    try {
        return await callback();
    } finally {
        loader.isLoading = false;
    }
}

export function validateEmail(email: string) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return re.test(email)
}

export function getS3URL(relativePath: string) {
    return `${import.meta.env.VITE_S3_URL}/${relativePath}`;
}

export function isValidUrl(url: string) {
    try {
        const withProtocol = url.match(/^[a-zA-Z][a-zA-Z\d+\-.]*:\/\//)
            ? url
            : `https://${url}`;

        new URL(withProtocol);
        return true;
    } catch {
        return false;
    }
}

export function generateInitialsAvatar(name: string): { initials: string; bgColor: string } {
    if (!name) return { initials: 'NA', bgColor: 'bg-coolGray' };
    
    const words = name.trim().split(' ');
    const initials = words.slice(0, 2).map(word => word.charAt(0).toUpperCase()).join('');
    
    const colors = [
        'bg-primary', 'bg-success', 'bg-link', 'bg-pending', 
        'bg-info', 'bg-darkGreen', 'bg-brandBlue', 'bg-lightBlue',
        'bg-orangeBrown', 'bg-deepBlue', 'bg-deepViolet', 'bg-coolGray'
    ];
    
    // Use character sum to determine consistent color for same name
    const charSum = initials.split('').reduce((sum, char) => sum + char.charCodeAt(0), 0);
    const colorIndex = charSum % colors.length;
    
    return {
        initials: initials || 'NA',
        bgColor: colors[colorIndex] || 'bg-coolGray'
    };
}