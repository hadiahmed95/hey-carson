import axios from 'axios';
import { useAlertStore } from '@/store/alert'

const BASE_URL = `${import.meta.env.VITE_API_BASE_URL}/api`; // update as needed

// Updated API factory function
const createApi = (role?: string) => {
    const baseURL = role ? `${BASE_URL}/v2/${role}` : BASE_URL;
    
    const apiInstance = axios.create({
        baseURL,
        withCredentials: true,
        headers: {
            'Content-Type': 'application/json',
        },
    });

    apiInstance.interceptors.request.use(config => {
        if (api.defaults.headers.common['Authorization']) {
            config.headers.Authorization = api.defaults.headers.common['Authorization'];
        }
        return config;
    });

    // Apply interceptors to each instance
    apiInstance.interceptors.response.use(
        response => response,
        error => {
            if (error.response) {
                const alert = useAlertStore()

                const message =
                    error.response.data?.message ||
                    error.response.data?.error ||
                    'Something went wrong. Please try again.'

                alert.show(message, 'error')
            }
            return Promise.reject(error);
        }
    );

    return apiInstance;
};

// Main API instance (for backward compatibility)
const api = createApi();

export { api, createApi };

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