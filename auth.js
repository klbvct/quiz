// API Configuration
const API_BASE_URL = 'http://localhost:8000/api';

// Utility functions
function showMessage(elementId, message, type) {
    const messageElement = document.getElementById(elementId);
    if (messageElement) {
        messageElement.textContent = message;
        messageElement.className = `message show ${type}`;
        
        setTimeout(() => {
            messageElement.classList.remove('show');
        }, 5000);
    }
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

// Token management
function setAuthToken(token) {
    localStorage.setItem('auth_token', token);
}

function getAuthToken() {
    return localStorage.getItem('auth_token');
}

function removeAuthToken() {
    localStorage.removeItem('auth_token');
}

// User session management
function setCurrentUser(user) {
    localStorage.setItem('currentUser', JSON.stringify(user));
}

function getCurrentUser() {
    const user = localStorage.getItem('currentUser');
    return user ? JSON.parse(user) : null;
}

function removeCurrentUser() {
    localStorage.removeItem('currentUser');
}

// API functions
async function apiRequest(endpoint, method = 'GET', data = null) {
    const token = getAuthToken();
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
    
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    
    const config = {
        method,
        headers,
    };
    
    if (data && (method === 'POST' || method === 'PUT')) {
        config.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Ошибка сервера');
        }
        
        return result;
    } catch (error) {
        throw error;
    }
}

async function logout() {
    try {
        await apiRequest('/logout', 'POST');
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        removeAuthToken();
        removeCurrentUser();
        window.location.href = 'index.html';
    }
}

async function checkAuth() {
    const token = getAuthToken();
    
    if (!token) {
        window.location.href = 'index.html';
        return null;
    }
    
    try {
        const response = await apiRequest('/user', 'GET');
        if (response.success) {
            setCurrentUser(response.user);
            return response.user;
        }
    } catch (error) {
        console.error('Auth check failed:', error);
        removeAuthToken();
        removeCurrentUser();
        window.location.href = 'index.html';
    }
    
    return null;
}

// Registration form handler
if (document.getElementById('registerForm')) {
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.classList.add('loading');
        
        const name = document.getElementById('registerName').value.trim();
        const email = document.getElementById('registerEmail').value.trim().toLowerCase();
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        const acceptTerms = document.getElementById('acceptTerms').checked;
        
        // Client-side validation
        if (!name) {
            showMessage('registerMessage', 'Пожалуйста, введите ваше имя', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        if (!validateEmail(email)) {
            showMessage('registerMessage', 'Пожалуйста, введите корректный email', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        if (!validatePassword(password)) {
            showMessage('registerMessage', 'Пароль должен содержать минимум 6 символов', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        if (password !== confirmPassword) {
            showMessage('registerMessage', 'Пароли не совпадают', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        if (!acceptTerms) {
            showMessage('registerMessage', 'Пожалуйста, примите условия использования', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        // API call
        try {
            const response = await apiRequest('/register', 'POST', {
                name,
                email,
                password,
            });
            
            if (response.success) {
                // Автоматически входим после регистрации
                setAuthToken(response.token);
                setCurrentUser(response.user);
                showMessage('registerMessage', 'Регистрация успешна! Вход в систему...', 'success');
                
                setTimeout(() => {
                    window.location.href = 'home.html';
                }, 1500);
            }
        } catch (error) {
            showMessage('registerMessage', error.message || 'Ошибка регистрации', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
        }
    });
    
    // Real-time password confirmation validation
    const passwordInput = document.getElementById('registerPassword');
    const confirmPasswordInput = document.getElementById('registerConfirmPassword');
    
    confirmPasswordInput.addEventListener('input', function() {
        if (this.value && passwordInput.value !== this.value) {
            this.classList.add('error');
            this.classList.remove('success');
        } else if (this.value && passwordInput.value === this.value) {
            this.classList.add('success');
            this.classList.remove('error');
        } else {
            this.classList.remove('error', 'success');
        }
    });
}

// Login form handler
if (document.getElementById('loginForm')) {
    // Check if already logged in
    const token = getAuthToken();
    if (token) {
        window.location.href = 'home.html';
    }
    
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.classList.add('loading');
        
        const email = document.getElementById('loginEmail').value.trim().toLowerCase();
        const password = document.getElementById('loginPassword').value;
        const rememberMe = document.getElementById('rememberMe').checked;
        
        // Client-side validation
        if (!validateEmail(email)) {
            showMessage('loginMessage', 'Пожалуйста, введите корректный email', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        if (!password) {
            showMessage('loginMessage', 'Пожалуйста, введите пароль', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
            return;
        }
        
        // API call
        try {
            const response = await apiRequest('/login', 'POST', {
                email,
                password,
            });
            
            if (response.success) {
                setAuthToken(response.token);
                setCurrentUser(response.user);
                showMessage('loginMessage', 'Вход выполнен успешно! Перенаправление...', 'success');
                
                setTimeout(() => {
                    window.location.href = 'home.html';
                }, 1000);
            }
        } catch (error) {
            showMessage('loginMessage', error.message || 'Неверный email или пароль', 'error');
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
        }
    });
}

// Home page handler
if (window.location.pathname.endsWith('home.html')) {
    // Check authentication
    checkAuth().then(user => {
        if (user) {
            const userNameElement = document.getElementById('userName');
            if (userNameElement) {
                userNameElement.textContent = user.name;
            }
        }
    });
    
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Вы уверены, что хотите выйти?')) {
                logout();
            }
        });
    }
}

// Forgot password handler (placeholder)
const forgotPasswordLinks = document.querySelectorAll('.forgot-password');
forgotPasswordLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Функция восстановления пароля будет реализована в будущем');
    });
});

// Form input animations
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
    });
});

console.log('Quiz Education Auth System Loaded');
