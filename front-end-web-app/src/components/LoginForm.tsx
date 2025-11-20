import React, { useState } from 'react';

const LoginForm = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');

        // Call the login function from the auth service
        try {
            // Assuming you have a login function in your auth service
            await authService.login(email, password);
            // Redirect to user dashboard or handle successful login
        } catch (err) {
            setError('Invalid email or password');
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label htmlFor="email">Email:</label>
                <input
                    type="email"
                    id="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                />
            </div>
            <div>
                <label htmlFor="password">Password:</label>
                <input
                    type="password"
                    id="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                />
            </div>
            {error && <p>{error}</p>}
            <button type="submit">Login</button>
        </form>
    );
};

export default LoginForm;