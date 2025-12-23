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
            
            <div class="user-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 12px; color: white; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="user-avatar" style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: bold; color: white; border: 3px solid rgba(255,255,255,0.3);">
                        {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 20px; color: white; font-weight: 600;">{{ $payment->user->name }}</h3>
                        <p style="margin: 0; opacity: 0.9; font-size: 14px;">{{ $payment->user->email }}</p>
                    </div>
                </div>
            </div>

            <div style="display: grid; gap: 12px; margin-bottom: 20px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #6c757d; font-size: 13px; font-weight: 500;">👤 ID користувача</span>
                        <span style="font-weight: 600; color: #212529;">#{{ $payment->user->id }}</span>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid {{ $payment->user->has_access ? '#28a745' : '#ffc107' }};">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #6c757d; font-size: 13px; font-weight: 500;">🎯 Доступ до тестування</span>
                        @if($payment->user->has_access)
                            <span class="badge badge-success">✓ Є</span>
                        @else
                            <span class="badge badge-warning">✗ Немає</span>
                        @endif
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #6c757d; font-size: 13px; font-weight: 500;">📅 Дата реєстрації</span>
                        <span style="font-weight: 600; color: #212529;">{{ $payment->user->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>

                @php
                    $totalPayments = \App\Models\Payment::where('user_id', $payment->user_id)->count();
                    $completedPayments = \App\Models\Payment::where('user_id', $payment->user_id)->where('status', 'completed')->count();
                    $totalAmount = \App\Models\Payment::where('user_id', $payment->user_id)->where('status', 'completed')->sum('amount');
                @endphp

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #6c757d; font-size: 13px; font-weight: 500;">💳 Всього платежів</span>
                        <span style="font-weight: 600; color: #212529;">{{ $completedPayments }}/{{ $totalPayments }}</span>
                    </div>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #fd7e14;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #6c757d; font-size: 13px; font-weight: 500;">💰 Сума платежів</span>
                        <span style="font-weight: 600; color: #212529;">{{ number_format($totalAmount, 0, ',', ' ') }} ₴</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.users.edit', $payment->user->id) }}" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; font-weight: 600;">
                👤 Перейти до профілю
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
                                <span class="payment-date">{{ $p->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <span class="badge badge-{{ $p->status === 'completed' ? 'success' : ($p->status === 'pending' ? 'warning' : 'error') }}">
                                @if($p->status === 'completed')
                                    Завершено
                                @elseif($p->status === 'pending')
                                    В очікуванні
                                @else
                                    Відхилено
                                @endif
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
