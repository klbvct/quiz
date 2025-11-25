@extends('layouts.admin')

@section('title', 'Створення користувача')

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.index') }}" class="btn-back">← Назад до списку</a>
        <h1>Створення користувача</h1>
    </div>
</div>

<div class="edit-grid">
    <!-- Форма створення -->
    <div class="edit-section">
        <div class="section-card">
            <h2>Дані користувача</h2>
            
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Ім'я <span class="required">*</span></label>
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
                    <label for="birthdate">Дата народження</label>
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
                    <small class="form-hint">Мінімум 8 символів</small>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Підтвердження пароля <span class="required">*</span></label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control"
                           required
                           minlength="8">
                </div>

                <div class="divider"></div>

                <h3>Права та доступ</h3>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="has_access" 
                               {{ old('has_access') ? 'checked' : '' }}>
                        <span>Доступ до тестування</span>
                    </label>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="is_admin" 
                               {{ old('is_admin') ? 'checked' : '' }}>
                        <span>Права адміністратора</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Створити користувача</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Скасувати</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Інформація -->
    <div class="info-section">
        <div class="section-card">
            <h3>ℹ️ Інформація</h3>
            <ul class="info-list">
                <li>Після створення користувач зможе увійти в систему використовуючи вказаний email та пароль</li>
                <li>Якщо встановлено прапорець "Доступ до тестування", користувач одразу зможе проходити тести</li>
                <li>Права адміністратора дають доступ до цієї панелі керування</li>
                <li>Email повинен бути унікальним в системі</li>
            </ul>
        </div>

        <div class="section-card">
            <h3>📋 Обов'язкові поля</h3>
            <ul class="info-list">
                <li><strong>Ім'я</strong> - відображуване ім'я користувача</li>
                <li><strong>Email</strong> - використовується для входу</li>
                <li><strong>Пароль</strong> - мінімум 8 символів</li>
            </ul>
        </div>

        <div class="section-card">
            <h3>🔐 Безпека</h3>
            <p>Пароль буде автоматично зашифровано перед збереженням у базу даних. Відновити початковий пароль буде неможливо.</p>
        </div>
    </div>
</div>
@endsection
