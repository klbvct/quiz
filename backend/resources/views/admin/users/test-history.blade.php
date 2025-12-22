@extends('layouts.admin')

@section('title', 'Історія тестувань - ' . $user->name)

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-back">← Назад до користувача</a>
        <h1>Історія тестувань: {{ $user->name }}</h1>
    </div>
</div>

<div class="section-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Усі завершені тестування</h2>
        <div class="test-stats">
            <span class="badge badge-info">Всього: {{ $completedSessions->total() }}</span>
        </div>
    </div>

    @if($completedSessions->count() > 0)
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата початку</th>
                        <th>Дата завершення</th>
                        <th>Тривалість</th>
                        <th>ID Сесії</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedSessions as $index => $session)
                        <tr>
                            <td>{{ $completedSessions->firstItem() + $index }}</td>
                            <td>{{ $session->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                @if($session->completed_at)
                                    {{ $session->completed_at->format('d.m.Y H:i') }}
                                @else
                                    <span class="badge badge-warning">В процесі</span>
                                @endif
                            </td>
                            <td>
                                @if($session->completed_at)
                                    {{ $session->created_at->diffForHumans($session->completed_at, true) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <code style="background: #e2e8f0; padding: 4px 8px; border-radius: 4px; font-size: 13px;">#{{ $session->id }}</code>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($session->result)
                                        <a href="{{ route('quiz.report.download', $session->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           target="_blank">
                                            📄 Скачати PDF
                                        </a>
                                    @else
                                        <span class="text-muted">Результати недоступні</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($completedSessions->hasPages())
            <div class="pagination-container">
                {{ $completedSessions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <p>У цього користувача немає завершених тестувань</p>
        </div>
    @endif
</div>
@endsection
