@extends('layouts.admin')

@section('title', 'Редактирование пользователя')

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.index') }}" class="btn-back">← Назад к списку</a>
        <h1>Редактирование пользователя</h1>
    </div>
</div>

<div class="edit-grid">
    <!-- Форма редактирования -->
    <div class="edit-section">
        <div class="section-card">
            <h2>Основные данные</h2>
            
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           class="form-control @error('name') is-invalid @enderror"
                           required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
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
                           value="{{ old('birthdate', $user->birthdate) }}" 
                           class="form-control @error('birthdate') is-invalid @enderror">
                    @error('birthdate')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="has_access" 
                               {{ old('has_access', $user->has_access) ? 'checked' : '' }}>
                        <span>Доступ к тестированию</span>
                    </label>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="is_admin" 
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <span>Права администратора</span>
                    </label>
                    @if($user->id === auth()->id())
                        <small class="form-hint">Вы не можете изменить свои права администратора</small>
                    @endif
                </div>

                <div class="divider"></div>

                <h3>Изменить пароль</h3>
                <p class="form-hint">Оставьте пустым, если не хотите менять пароль</p>

                <div class="form-group">
                    <label for="password">Новый пароль</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           minlength="8">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control"
                           minlength="8">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Информация о пользователе -->
    <div class="info-section">
        <div class="section-card">
            <h2>Информация</h2>
            
            <div class="info-item">
                <div class="info-label">ID пользователя</div>
                <div class="info-value">{{ $user->id }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Дата регистрации</div>
                <div class="info-value">{{ $user->created_at->format('d.m.Y H:i') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Последнее обновление</div>
                <div class="info-value">{{ $user->updated_at->format('d.m.Y H:i') }}</div>
            </div>
        </div>

        <div class="section-card">
            <h2>Статистика тестирования</h2>
            
            @if($user->quizSessions->count() > 0)
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="stat-label">Всего сессий</div>
                        <div class="stat-value">{{ $user->quizSessions->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Завершено</div>
                        <div class="stat-value">{{ $user->quizSessions->whereNotNull('completed_at')->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">В процессе</div>
                        <div class="stat-value">{{ $user->quizSessions->whereNull('completed_at')->count() }}</div>
                    </div>
                </div>

                <div class="divider"></div>

                <h3>История сессий</h3>
                <div class="sessions-list">
                    @foreach($user->quizSessions->take(5) as $session)
                        <div class="session-item">
                            <div class="session-date">{{ $session->created_at->format('d.m.Y H:i') }}</div>
                            <div class="session-status">
                                @if($session->completed_at)
                                    <span class="badge badge-success">Завершено</span>
                                    <small>{{ $session->completed_at->format('d.m.Y H:i') }}</small>
                                @else
                                    <span class="badge badge-warning">Модуль {{ $session->current_module }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Пользователь еще не проходил тестирование</p>
            @endif
        </div>

        @if($user->id !== auth()->id())
            <div class="section-card danger-zone">
                <h2>Опасная зона</h2>
                <p class="text-muted">Удаление пользователя необратимо. Все его данные будут удалены.</p>
                
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                      onsubmit="return confirm('Вы уверены, что хотите удалить пользователя {{ $user->name }}? Это действие необратимо!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить пользователя</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
