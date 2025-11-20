import React from 'react';
import { useHistory } from 'react-router-dom';
import LoginForm from '../components/LoginForm';

const Login = () => {
    const history = useHistory();

    const handleLoginSuccess = () => {
        history.push('/user-dashboard');
    };

    return (
        <div className="login-container">
            <h2>Login</h2>
            <LoginForm onLoginSuccess={handleLoginSuccess} />
        </div>
    );
};

export default Login;