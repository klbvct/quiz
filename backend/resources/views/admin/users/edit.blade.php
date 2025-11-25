@extends('layouts.admin')

@section('title', 'Редагування користувача')

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.index') }}" class="btn-back">← Назад до списку</a>
        <h1>Редагування користувача</h1>
    </div>
</div>

<div class="edit-grid">
    <!-- Форма редагування -->
    <div class="edit-section">
        <div class="section-card">
            <h2>Основні дані</h2>
            
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Ім'я</label>
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
                    <label for="birthdate">Дата народження</label>
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
                               value="1"
                               {{ old('has_access', $user->has_access) ? 'checked' : '' }}>
                        <span>Доступ до тестування</span>
                    </label>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" 
                               name="is_admin" 
                               value="1"
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <span>Права адміністратора</span>
                    </label>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="is_admin" value="{{ $user->is_admin ? '1' : '0' }}">
                        <small class="form-hint">Ви не можете змінити свої права адміністратора</small>
                    @endif
                </div>

                <div class="divider"></div>

                <h3>Змінити пароль</h3>
                <p class="form-hint">Залиште порожнім, якщо не хочете змінювати пароль</p>

                <div class="form-group">
                    <label for="password">Новий пароль</label>
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
                    <label for="password_confirmation">Підтвердження пароля</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control"
                           minlength="8">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Скасувати</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Інформація про користувача -->
    <div class="info-section">
        <div class="section-card">
            <h2>Інформація</h2>
            
            <div class="info-item">
                <div class="info-label">ID користувача</div>
                <div class="info-value">{{ $user->id }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Дата реєстрації</div>
                <div class="info-value">{{ $user->created_at->format('d.m.Y H:i') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Останнє оновлення</div>
                <div class="info-value">{{ $user->updated_at->format('d.m.Y H:i') }}</div>
            </div>
        </div>

        <div class="section-card">
            <h2>Платежі</h2>
            
            @php
                $payments = \App\Models\Payment::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            @endphp
            
            @if($payments->count() > 0)
                <div class="payments-list">
                    @foreach($payments as $payment)
                        <div class="payment-item">
                            <div class="payment-status">
                                @if($payment->status === 'completed')
                                    <span class="badge badge-success">Завершено</span>
                                @elseif($payment->status === 'pending')
                                    <span class="badge badge-warning">В очікуванні</span>
                                @else
                                    <span class="badge badge-error">Відхилено</span>
                                @endif
                            </div>
                            <div class="payment-info">
                                <div class="payment-date">{{ $payment->created_at->format('d.m.Y H:i') }}</div>
                            </div>

                        </div>
                    @endforeach
                </div>
                
                <div class="payment-total">
                    <strong>Всього платежів:</strong> {{ $payments->count() }}<br>
                    <strong>Успішних:</strong> {{ $payments->where('status', 'completed')->count() }}<br>
                    <strong>Сума:</strong> {{ number_format($payments->where('status', 'completed')->sum('amount'), 0, ',', ' ') }} ₴
                </div>
            @else
                <p class="text-muted">Немає платежів</p>
            @endif
        </div>

        <div class="section-card">
            <h2>Статистика тестування</h2>
            
            @php
                $hasCompletedSession = $user->quizSessions->whereNotNull('completed_at')->count() > 0;
            @endphp
            
            @if($hasCompletedSession && !$user->can_retake)
                <div class="retake-section">
                    <p class="text-muted">Користувач завершив тестування. Ви можете дозволити повторне проходження тесту.</p>
                    <form method="POST" action="{{ route('admin.users.enable-retake', $user->id) }}" style="margin-top: 15px;">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Дозволити користувачу {{ $user->name }} пройти тест повторно? Поточний активний тест буде скинуто.')">
                            🔄 Дозволити повторне проходження
                        </button>
                    </form>
                </div>
                <div class="divider"></div>
            @endif
            
            @if($user->quizSessions->count() > 0)
                <div class="stats-list">
                    <div class="stat-item">
                        <div class="stat-label">Всього сесій</div>
                        <div class="stat-value">{{ $user->quizSessions->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Завершено</div>
                        <div class="stat-value">{{ $user->quizSessions->whereNotNull('completed_at')->count() }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">В процесі</div>
                        <div class="stat-value">{{ $user->quizSessions->whereNull('completed_at')->count() }}</div>
                    </div>
                </div>

                @php
                    $completedSession = $user->quizSessions->whereNotNull('completed_at')->first();
                @endphp

                @if($completedSession)
                    <div class="divider"></div>
                    <a href="{{ route('admin.users.quiz-results', $user->id) }}" class="btn btn-primary" style="width: 100%;">
                        📊 Переглянути результати тестування
                    </a>
                @endif

                <div class="divider"></div>

                <h3>Історія сесій</h3>
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
                <p class="text-muted">Користувач ще не проходив тестування</p>
            @endif
        </div>

        @if($user->id !== auth()->id())
            <div class="section-card danger-zone">
                <h2>Небезпечна зона</h2>
                <p class="text-muted">Видалення користувача незворотне. Всі його дані будуть видалені.</p>
                
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                      onsubmit="return confirm('Ви впевнені, що хочете видалити користувача {{ $user->name }}? Ця дія незворотна!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Видалити користувача</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
