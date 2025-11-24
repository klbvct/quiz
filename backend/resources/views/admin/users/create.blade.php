@extends('layouts.admin')

@section('title', 'Создание пользователя')

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.index') }}" class="btn-back">← Назад к списку</a>
        <h1>Создание пользователя</h1>
    </div>
</div>

<div class="edit-grid">
    <!-- Форма создания -->
    <div class="edit-section">
        <div class="section-card">
            <h2>Данные пользователя</h2>
            
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Имя <span class="required">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           class="form-control @error('name') is-invalid @enderror"
                           required
                           autofocus>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           class="form-control @error('email') is-invalid @enderror"
                           required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birthdate">Дата рождения</label>
                    <input type="date" 
                           id="birthdate" 
                           name="birthdate" 
                           value="{{ old('birthdate') }}" 
                           class="form-control @error('birthdate') is-invalid @enderror">
                    @error('birthdate')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider"></div>

                <h3>Пароль</h3>

                <div class="form-group">
                    <label for="password">Пароль <span class="required">*</span></label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           required
                           minlength="8">
                    <small class="form-hint">Минимум 8 символов</small>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля <span class="required">*</span></label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control"
                           required
                           minlength="8">
                </div>

                <div class="divider"></div>

                <h3>Права и доступ</h3>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="has_access" 
                               {{ old('has_access') ? 'checked' : '' }}>
                        <span>Доступ к тестированию</span>
                    </label>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="is_admin" 
                               {{ old('is_admin') ? 'checked' : '' }}>
                        <span>Права администратора</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Создать пользователя</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Информация -->
    <div class="info-section">
        <div class="section-card">
            <h3>ℹ️ Информация</h3>
            <ul class="info-list">
                <li>После создания пользователь сможет войти в систему используя указанный email и пароль</li>
                <li>Если установлен флаг "Доступ к тестированию", пользователь сразу сможет проходить тесты</li>
                <li>Права администратора дают доступ к этой панели управления</li>
                <li>Email должен быть уникальным в системе</li>
            </ul>
        </div>

        <div class="section-card">
            <h3>📋 Обязательные поля</h3>
            <ul class="info-list">
                <li><strong>Имя</strong> - отображаемое имя пользователя</li>
                <li><strong>Email</strong> - используется для входа</li>
                <li><strong>Пароль</strong> - минимум 8 символов</li>
            </ul>
        </div>

        <div class="section-card">
            <h3>🔐 Безопасность</h3>
            <p>Пароль будет автоматически зашифрован перед сохранением в базу данных. Восстановить исходный пароль будет невозможно.</p>
        </div>
    </div>
</div>
@endsection
