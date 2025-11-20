@extends('layouts.app')

@section('title', 'Вход - Quiz Education')

@section('content')
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Вход</h1>
            <p>Войдите в свою учетную запись</p>
        </div>
        
        <form id="loginForm" class="auth-form">
            <div class="form-group">
                <label for="loginEmail">Email</label>
                <input type="email" id="loginEmail" name="email" placeholder="example@mail.com" required>
            </div>
            
            <div class="form-group">
                <label for="loginPassword">Пароль</label>
                <input type="password" id="loginPassword" name="password" placeholder="••••••••" required>
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" id="rememberMe">
                    <span>Запомнить меня</span>
                </label>
                <a href="#" class="forgot-password">Забыли пароль?</a>
            </div>
            
            <button type="submit" class="btn btn-primary">Войти</button>
            
            <div class="form-footer">
                <p>Нет учетной записи? <a href="{{ url('/register') }}">Зарегистрироваться</a></p>
            </div>
        </form>
        
        <div id="loginMessage" class="message"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Проверка авторизации - если уже авторизован, перенаправляем на главную
    if (localStorage.getItem('token')) {
        window.location.href = '/home';
    }

    // Обработка формы входа
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const messageDiv = document.getElementById('loginMessage');
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        try {
            const data = await apiRequest('/api/login', {
                method: 'POST',
                body: JSON.stringify({ email, password })
            });
            
            // Сохранение токена и данных пользователя
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            
            messageDiv.textContent = 'Успешный вход! Перенаправление...';
            messageDiv.className = 'message success';
            
            // Перенаправление на главную страницу
            setTimeout(() => {
                window.location.href = '/home';
            }, 1000);
        } catch (error) {
            messageDiv.textContent = error.message || 'Ошибка входа';
            messageDiv.className = 'message error';
        }
    });
</script>
@endpush
