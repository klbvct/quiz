import { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { login, logout, register } from '../services/auth';
import { RootState } from '../store';

const useAuth = () => {
    const [loading, setLoading] = useState(false);
    const dispatch = useDispatch();
    const user = useSelector((state: RootState) => state.auth.user);

    const loginUser = async (email: string, password: string) => {
        setLoading(true);
        try {
            await dispatch(login(email, password));
        } catch (error) {
            console.error('Login failed:', error);
        } finally {
            setLoading(false);
        }
    };

    const registerUser = async (email: string, password: string) => {
        setLoading(true);
        try {
            await dispatch(register(email, password));
        } catch (error) {
            console.error('Registration failed:', error);
        } finally {
            setLoading(false);
        }
    };

    const logoutUser = () => {
        dispatch(logout());
    };

    useEffect(() => {
        // Optionally, you can add logic to check for user authentication status on mount
    }, []);

    return {
        user,
        loading,
        loginUser,
        registerUser,
        logoutUser,
    };
};

export default useAuth;