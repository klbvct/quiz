@extends('layouts.app')

@section('title', 'Главная - Quiz Education')

@section('content')
<div class="container">
    <div class="home-wrapper">
        <div class="header">
            <h1>Quiz Education</h1>
            <div class="user-info">
                <span id="userName">Пользователь</span>
                <button id="logoutBtn" class="btn btn-secondary">Выйти</button>
            </div>
        </div>
        
        <div class="content">
            <h2>Добро пожаловать!</h2>
            <p>Вы успешно вошли в систему.</p>
            <div class="dashboard">
                <div class="card">
                    <h3>Мои квизы</h3>
                    <p>Здесь будут отображаться ваши квизы</p>
                </div>
                <div class="card">
                    <h3>Статистика</h3>
                    <p>Здесь будет отображаться ваша статистика</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Проверка авторизации при загрузке страницы
    checkAuth();
    
    // Загрузка информации о пользователе
    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        document.getElementById('userName').textContent = user.name;
    }
    
    // Обработчик выхода
    document.getElementById('logoutBtn').addEventListener('click', async () => {
        try {
            await apiRequest('/api/logout', {
                method: 'POST'
            });
        } catch (error) {
            console.error('Ошибка при выходе:', error);
        } finally {
            // Очистка локального хранилища
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            
            // Перенаправление на страницу входа
            window.location.href = '/';
        }
    });
</script>
@endpush
