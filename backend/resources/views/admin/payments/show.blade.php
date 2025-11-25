@extends('layouts.admin')

@section('title', 'Деталі платежу #' . $payment->id)

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.payments.index') }}" class="btn-back">← Назад до списку</a>
        <h1>Деталі платежу #{{ $payment->id }}</h1>
    </div>
</div>

<div class="payment-detail-grid">
    <!-- Основна інформація -->
    <div class="detail-section">
        <div class="section-card">
            <h2>Інформація про платіж</h2>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">ID платежу</div>
                    <div class="info-value">{{ $payment->id }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Сума</div>
                    <div class="info-value"><strong class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} ₴</strong></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Статус</div>
                    <div class="info-value">
                        @if($payment->status === 'completed')
                            <span class="badge badge-success">Завершено</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge badge-warning">В очікуванні</span>
                        @else
                            <span class="badge badge-error">Відхилено</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Платіжний провайдер</div>
                    <div class="info-value">{{ $payment->payment_provider ?? 'LiqPay' }}</div>
                </div>

                @if($payment->transaction_id)
                    <div class="info-item">
                        <div class="info-label">ID транзакції</div>
                        <div class="info-value"><code>{{ $payment->transaction_id }}</code></div>
                    </div>
                @endif

                @if($payment->payment_data)
                    <div class="info-item full-width">
                        <div class="info-label">Додаткові дані</div>
                        <div class="info-value">
                            <pre class="json-data">{{ json_encode(json_decode($payment->payment_data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                <div class="info-item">
                    <div class="info-label">Дата створення</div>
                    <div class="info-value">{{ $payment->created_at->format('d.m.Y H:i:s') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Останнє оновлення</div>
                    <div class="info-value">{{ $payment->updated_at->format('d.m.Y H:i:s') }}</div>
                </div>
            </div>
        </div>

        <!-- Зміна статусу -->
        <div class="section-card">
            <h2>Змінити статус платежу</h2>
            <p class="text-muted">Зміна статусу на "Завершено" автоматично надасть користувачу доступ до тестування.</p>
            
            <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}">
                @csrf
                
                <div class="form-group">
                    <label for="status">Статус</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>В очікуванні</option>
                        <option value="completed" {{ $payment->status === 'completed' ? 'selected' : '' }}>Завершено</option>
                        <option value="failed" {{ $payment->status === 'failed' ? 'selected' : '' }}>Відхилено</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="note">Примітка (необов'язково)</label>
                    <textarea name="note" id="note" class="form-control" rows="3" placeholder="Причина зміни статусу..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Оновити статус</button>
            </form>
        </div>
    </div>

    <!-- Інформація про користувача -->
    <div class="sidebar-section">
        <div class="section-card">
            <h2>Користувач</h2>
            
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <h3>{{ $payment->user->name }}</h3>
                    <p>{{ $payment->user->email }}</p>
                </div>
            </div>

            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-label">ID користувача</div>
                    <div class="stat-value">{{ $payment->user->id }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Доступ до тестування</div>
                    <div class="stat-value">
                        @if($payment->user->has_access)
                            <span class="badge badge-success">Є</span>
                        @else
                            <span class="badge badge-warning">Немає</span>
                        @endif
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Дата реєстрації</div>
                    <div class="stat-value">{{ $payment->user->created_at->format('d.m.Y') }}</div>
                </div>
            </div>

            <a href="{{ route('admin.users.edit', $payment->user->id) }}" class="btn btn-secondary btn-block">
                Перейти до профілю
            </a>
        </div>

        <div class="section-card">
            <h3>Інші платежі користувача</h3>
            @php
                $userPayments = \App\Models\Payment::where('user_id', $payment->user_id)
                    ->where('id', '!=', $payment->id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            @endphp
            
            @if($userPayments->count() > 0)
                <div class="payments-list">
                    @foreach($userPayments as $p)
                        <div class="payment-item">
                            <div class="payment-info">
                                <span class="payment-amount">{{ number_format($p->amount, 0, ',', ' ') }} ₴</span>
                                <span class="payment-date">{{ $p->created_at->format('d.m.Y') }}</span>
                            </div>
                            <span class="badge badge-{{ $p->status === 'completed' ? 'success' : ($p->status === 'pending' ? 'warning' : 'error') }}">
                                {{ $p->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Інших платежів немає</p>
            @endif
        </div>
    </div>
</div>
@endsection
