export interface User {
    id: string;
    username: string;
    email: string;
    createdAt: Date;
}

export interface Admin {
    id: string;
    username: string;
    email: string;
    role: 'admin';
}

export interface AuthResponse {
    token: string;
    user: User | Admin;
}

export interface RegistrationData {
    username: string;
    email: string;
    password: string;
}

export interface LoginData {
    username: string;
    password: string;
}