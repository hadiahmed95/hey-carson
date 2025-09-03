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