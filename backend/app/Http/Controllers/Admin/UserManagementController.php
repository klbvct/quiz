<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\QuizSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Отобразить список всех пользователей
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Поиск
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по доступу
        if ($request->has('access_filter') && $request->access_filter !== '') {
            $query->where('has_access', $request->access_filter);
        }

        // Фильтр по роли
        if ($request->has('role_filter') && $request->role_filter !== '') {
            $query->where('is_admin', $request->role_filter);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Показать форму создания пользователя
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Создать нового пользователя
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'has_access' => ['boolean'],
            'is_admin' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birthdate' => $validated['birthdate'] ?? null,
            'password' => Hash::make($validated['password']),
            'has_access' => $request->has('has_access'),
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'Пользователь успешно создан');
    }

    /**
     * Показать форму редактирования пользователя
     */
    public function edit($id)
    {
        $user = User::with(['quizSessions' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Обновить данные пользователя
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'has_access' => ['boolean'],
            'is_admin' => ['boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->birthdate = $validated['birthdate'] ?? null;
        $user->has_access = $request->has('has_access');
        $user->is_admin = $request->has('is_admin');

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.edit', $id)
            ->with('success', 'Данные пользователя успешно обновлены');
    }

    /**
     * Переключить доступ пользователя
     */
    public function toggleAccess($id)
    {
        $user = User::findOrFail($id);
        $user->has_access = !$user->has_access;
        $user->save();

        return redirect()->back()->with('success', 'Доступ пользователя изменен');
    }

    /**
     * Удалить пользователя
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Запрет удаления себя
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Вы не можете удалить свою учетную запись');
        }

        // Удаление связанных данных
        QuizSession::where('user_id', $user->id)->delete();
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален');
    }
}
