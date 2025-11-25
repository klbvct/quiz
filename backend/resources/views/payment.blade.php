@extends('layouts.app')

@section('title', 'Оплата доступу - Quiz Education')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="payment-page">
    <div class="payment-container">
        <div class="payment-header">
            <h1>Оплата доступу до тестування</h1>
            <p>Профорієнтаційне тестування для визначення вашої кар'єри</p>
        </div>
        
        <div class="payment-card">
            <div class="user-info-section">
                <p><strong>Користувач:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            </div>
            
            <div class="price-section">
                <h3>Вартість:</h3>
                <div class="price-amount">1 грн</div>
                <p class="price-note">Разовий платіж • Доступ назавжди</p>
            </div>
            
            <form id="paymentFormAuth" class="payment-form">
                @csrf
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                
                <button type="submit" class="btn btn-primary btn-pay">
                    <span>Оплатити</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </form>
            
            <div class="security-info">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span>Безпечна оплата через LiqPay</span>
            </div>
            
            <div class="back-link">
                <a href="{{ route('home') }}">← Повернутися до особистого кабінету</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/payment-auth.js') }}"></script>
@endpush
