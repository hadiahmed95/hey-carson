import axios from 'axios';

const BASE_URL = `${import.meta.env.VITE_API_BASE_URL}/api`; // update as needed

const api = axios.create({
    baseURL: BASE_URL,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

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

    static async put(endpoint: string, data: any = null) {
        return api.put(endpoint, data);
    }

    static async patch(endpoint: string, data: any = null) {
        return api.patch(endpoint, data);
    }

    static async delete(endpoint: string) {
        return api.delete(endpoint);
    }
}