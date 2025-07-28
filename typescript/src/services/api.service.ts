import axios from 'axios';

const BASE_URL = `${import.meta.env.VITE_API_BASE_URL}/api`;

const api = axios.create({
    baseURL: BASE_URL,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Add request interceptor to handle CSRF tokens for Sanctum
api.interceptors.request.use(async (config) => {
    // For authenticated requests, ensure we have CSRF cookie
    if (!document.cookie.includes('XSRF-TOKEN')) {
        try {
            await axios.get(`${import.meta.env.VITE_API_BASE_URL}/sanctum/csrf-cookie`, {
                withCredentials: true
            });
        } catch (error) {
            console.warn('Failed to get CSRF cookie:', error);
        }
    }
    return config;
});

// Add response interceptor for better error handling
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 419) {
            // CSRF token mismatch - clear cookies and try to get new token
            document.cookie.split(";").forEach((c) => {
                const eqPos = c.indexOf("=");
                const name = eqPos > -1 ? c.substr(0, eqPos) : c;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
            });
        }
        return Promise.reject(error);
    }
);

export { api };

export default class ApiService {
    static async get(endpoint: string, params?: any) {
        return api.get(endpoint, { params });
    }

    static async post(endpoint: string, data: any, isMultiPart?: any) {
        const authHeader = api.defaults.headers.common['Authorization'];
        const config = isMultiPart
            ? {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    ...(authHeader && { Authorization: authHeader }),
                },
            }
            : {};
        return api.post(endpoint, data, config);
    }

    static async put(endpoint: string, data: any) {
        return api.put(endpoint, data);
    }

    static async delete(endpoint: string) {
        return api.delete(endpoint);
    }
}