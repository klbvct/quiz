@extends('layouts.admin')

@section('title', 'Керування користувачами')

@section('content')
<div class="page-header">
    <div class="header-with-actions">
        <h1>Керування користувачами</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Створити користувача</a>
    </div>
</div>

<!-- Фільтри та пошук -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filters-form">
        <div class="filter-group">
            <input type="text" 
                   name="search" 
                   placeholder="Пошук за іменем або email" 
                   value="{{ request('search') }}"
                   class="search-input">
        </div>

        <div class="filter-group">
            <select name="access_filter" class="filter-select">
                <option value="">Всі користувачі</option>
                <option value="1" {{ request('access_filter') === '1' ? 'selected' : '' }}>З доступом</option>
                <option value="0" {{ request('access_filter') === '0' ? 'selected' : '' }}>Без доступу</option>
            </select>
        </div>

        <div class="filter-group">
            <select name="role_filter" class="filter-select">
                <option value="">Всі ролі</option>
                <option value="1" {{ request('role_filter') === '1' ? 'selected' : '' }}>Адміністратори</option>
                <option value="0" {{ request('role_filter') === '0' ? 'selected' : '' }}>Користувачі</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Застосувати</button>
        @if(request()->hasAny(['search', 'access_filter', 'role_filter']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Скинути</a>
        @endif
    </form>
</div>

<!-- Таблиця користувачів -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ім'я</th>
                <th>Email</th>
                <th>Дата народження</th>
                <th>Доступ</th>
                <th>Роль</th>
                <th>Реєстрація</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d.m.Y') : '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.toggle-access', $user->id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="badge-btn {{ $user->has_access ? 'badge-success' : 'badge-warning' }}">
                                {{ $user->has_access ? 'Є' : 'Немає' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge badge-info">Адмін</span>
                        @else
                            <span class="badge badge-default">Користувач</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d.m.Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" title="Редагувати">
                                ✏️
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                      onsubmit="return confirm('Ви впевнені, що хочете видалити користувача {{ $user->name }}?')"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Видалити">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Користувачів не знайдено</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Пагінація -->
@if($users->hasPages())
    <div class="pagination-container">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endif
@endsection
