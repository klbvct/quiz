@extends('layouts.app')

@section('title', 'Регистрация - Quiz Education')

@section('content')
<div class="container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1>Регистрация</h1>
            <p>Создайте новую учетную запись</p>
        </div>
        
        <form id="registerForm" class="auth-form">
            <div class="form-group">
                <label for="registerName">Имя</label>
                <input type="text" id="registerName" name="name" placeholder="Ваше имя" required>
            </div>
            
            <div class="form-group">
                <label for="registerEmail">Email</label>
                <input type="email" id="registerEmail" name="email" placeholder="example@mail.com" required>
            </div>
            
            <div class="form-group">
                <label for="registerPassword">Пароль</label>
                <input type="password" id="registerPassword" name="password" placeholder="••••••••" required minlength="6">
                <small class="form-hint">Минимум 6 символов</small>
            </div>
            
            <div class="form-group">
                <label for="registerConfirmPassword">Подтвердите пароль</label>
                <input type="password" id="registerConfirmPassword" name="confirmPassword" placeholder="••••••••" required>
            </div>
            
            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" id="acceptTerms" required>
                    <span>Я согласен с <a href="#">условиями использования</a></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            
            <div class="form-footer">
                <p>Уже есть учетная запись? <a href="{{ url('/') }}">Войти</a></p>
            </div>
        </form>
        
        <div id="registerMessage" class="message"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Проверка авторизации - если уже авторизован, перенаправляем на главную
    if (localStorage.getItem('token')) {
        window.location.href = '/home';
    }

    // Обработка формы регистрации
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const messageDiv = document.getElementById('registerMessage');
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        
        // Проверка совпадения паролей
        if (password !== confirmPassword) {
            messageDiv.textContent = 'Пароли не совпадают';
            messageDiv.className = 'message error';
            return;
        }
        
        try {
            const data = await apiRequest('/api/register', {
                method: 'POST',
                body: JSON.stringify({ name, email, password })
            });
            
            // Автоматический вход после регистрации
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            
            messageDiv.textContent = 'Регистрация успешна! Перенаправление...';
            messageDiv.className = 'message success';
            
            // Перенаправление на главную страницу
            setTimeout(() => {
                window.location.href = '/home';
            }, 1000);
        } catch (error) {
            messageDiv.textContent = error.message || 'Ошибка регистрации';
            messageDiv.className = 'message error';
        }
    });
</script>
@endpush
