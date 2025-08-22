import type { TimeFormatterOptions } from "@/types.ts";

export function formatDate(dateString: string, isShowTime = false): string {
    if (!dateString) return "";

    const date = new Date(dateString);

    const day = date.getDate().toString().padStart(2, "0");
    const month = date.toLocaleString("en-US", { month: "short" });
    const year = date.getFullYear();

    const datePart = `${day} ${month}, ${year}`;
    if (!isShowTime) return datePart;

    const timePart = date.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    }).toLowerCase();

    return `${datePart} / ${timePart}`;
}

export function formatTimeAgo(
    timestamp: string | Date | number,
    options: TimeFormatterOptions = {}
): string {
    const {
        showFullDateAfterDays = 7,
        shortFormat = false,
        includeSeconds = false
    } = options

    // Convert timestamp to Date object
    let date: Date

    if (typeof timestamp === 'string') {
        // Handle ISO strings like "2025-08-12T11:15:42.0000000Z"
        date = new Date(timestamp)
    } else if (typeof timestamp === 'number') {
        // Handle Unix timestamps (both seconds and milliseconds)
        date = new Date(timestamp < 10000000000 ? timestamp * 1000 : timestamp)
    } else if (timestamp instanceof Date) {
        date = timestamp
    } else {
        return 'Invalid date'
    }

    // Check if date is valid
    if (isNaN(date.getTime())) {
        return 'Invalid date'
    }

    const now = new Date()
    const diffMs = now.getTime() - date.getTime()
    const diffSeconds = Math.floor(diffMs / 1000)
    const diffMinutes = Math.floor(diffSeconds / 60)
    const diffHours = Math.floor(diffMinutes / 60)
    const diffDays = Math.floor(diffHours / 24)
    const diffWeeks = Math.floor(diffDays / 7)
    const diffMonths = Math.floor(diffDays / 30)
    const diffYears = Math.floor(diffDays / 365)

    // If timestamp is in the future
    if (diffMs < 0) {
        return shortFormat ? 'now' : 'just now'
    }

    // Show full date for old timestamps
    if (diffDays > showFullDateAfterDays) {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    }

    // Format recent times
    if (diffYears > 0) {
        return shortFormat
            ? `${diffYears}y`
            : `${diffYears} year${diffYears > 1 ? 's' : ''} ago`
    }

    if (diffMonths > 0) {
        return shortFormat
            ? `${diffMonths}mo`
            : `${diffMonths} month${diffMonths > 1 ? 's' : ''} ago`
    }

    if (diffWeeks > 0) {
        return shortFormat
            ? `${diffWeeks}w`
            : `${diffWeeks} week${diffWeeks > 1 ? 's' : ''} ago`
    }

    if (diffDays > 0) {
        return shortFormat
            ? `${diffDays}d`
            : `${diffDays} day${diffDays > 1 ? 's' : ''} ago`
    }

    if (diffHours > 0) {
        return shortFormat
            ? `${diffHours}h`
            : `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`
    }

    if (diffMinutes > 0) {
        return shortFormat
            ? `${diffMinutes}m`
            : `${diffMinutes} minute${diffMinutes > 1 ? 's' : ''} ago`
    }

    if (includeSeconds && diffSeconds > 5) {
        return shortFormat
            ? `${diffSeconds}s`
            : `${diffSeconds} second${diffSeconds > 1 ? 's' : ''} ago`
    }

    return shortFormat ? 'now' : 'just now'
}

/**
 * Format absolute time with different options
 * @param timestamp - ISO string, Date object, or timestamp number
 * @param format - Format type: 'full', 'date', 'time', 'datetime'
 * @returns Formatted time string
 */
export function formatAbsoluteTime(
    timestamp: string | Date | number,
    format: 'full' | 'date' | 'time' | 'datetime' = 'full'
): string {
    const date = new Date(timestamp)

    if (isNaN(date.getTime())) {
        return 'Invalid date'
    }

    switch (format) {
        case 'full':
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            })

        case 'date':
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })

        case 'time':
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            })

        case 'datetime':
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            })

        default:
            return date.toLocaleString()
    }
}