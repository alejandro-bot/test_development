import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import type { User, UserState, UserCredentials } from '@/types/user';

export const useUserStore = defineStore('user', () => {
    // State
    const state = ref<UserState>({
        currentUser: null,
        users: [],
        loading: false,
        error: null
    });

    // Actions
    const login = async (credentials: UserCredentials) => {
        state.value.loading = true;
        state.value.error = null;
        try {
            const response = await axios.post('/api/auth/login', credentials);
            state.value.currentUser = response.data;
            return response.data;
        } catch (error) {
            state.value.error = error instanceof Error ? error.message : 'An error occurred';
            throw error;
        } finally {
            state.value.loading = false;
        }
    };

    const logout = () => {
        state.value.currentUser = null;
    };

    const fetchUsers = async () => {
        state.value.loading = true;
        state.value.error = null;
        try {
            const response = await axios.get('/api/users');
            state.value.users = response.data;
            return response.data;
        } catch (error) {
            state.value.error = error instanceof Error ? error.message : 'An error occurred';
            throw error;
        } finally {
            state.value.loading = false;
        }
    };

    // Getters
    const isAuthenticated = () => !!state.value.currentUser;
    const isAdmin = () => state.value.currentUser?.role === 'admin';

    return {
        state,
        login,
        logout,
        fetchUsers,
        isAuthenticated,
        isAdmin
    };
}); 