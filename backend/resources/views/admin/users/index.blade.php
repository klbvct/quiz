@extends('layouts.admin')

@section('title', 'Управление пользователями')

@section('content')
<div class="page-header">
    <h1>Управление пользователями</h1>
</div>

<!-- Фильтры и поиск -->
<div class="filters-section">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filters-form">
        <div class="filter-group">
            <input type="text" 
                   name="search" 
                   placeholder="Поиск по имени или email" 
                   value="{{ request('search') }}"
                   class="search-input">
        </div>

        <div class="filter-group">
            <select name="access_filter" class="filter-select">
                <option value="">Все пользователи</option>
                <option value="1" {{ request('access_filter') === '1' ? 'selected' : '' }}>С доступом</option>
                <option value="0" {{ request('access_filter') === '0' ? 'selected' : '' }}>Без доступа</option>
            </select>
        </div>

        <div class="filter-group">
            <select name="role_filter" class="filter-select">
                <option value="">Все роли</option>
                <option value="1" {{ request('role_filter') === '1' ? 'selected' : '' }}>Администраторы</option>
                <option value="0" {{ request('role_filter') === '0' ? 'selected' : '' }}>Пользователи</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Применить</button>
        @if(request()->hasAny(['search', 'access_filter', 'role_filter']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Сбросить</a>
        @endif
    </form>
</div>

<!-- Таблица пользователей -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Дата рождения</th>
                <th>Доступ</th>
                <th>Роль</th>
                <th>Регистрация</th>
                <th>Действия</th>
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
                                {{ $user->has_access ? 'Есть' : 'Нет' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge badge-info">Админ</span>
                        @else
                            <span class="badge badge-default">Пользователь</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d.m.Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" title="Редактировать">
                                ✏️
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                      onsubmit="return confirm('Вы уверены, что хотите удалить пользователя {{ $user->name }}?')"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Удалить">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Пользователи не найдены</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Пагинация -->
@if($users->hasPages())
    <div class="pagination-container">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endif
@endsection
