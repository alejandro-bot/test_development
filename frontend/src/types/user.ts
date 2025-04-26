export interface User {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'user';
}

export interface UserState {
    currentUser: User | null;
    users: User[];
    loading: boolean;
    error: string | null;
}

export interface UserCredentials {
    email: string;
    password: string;
} 