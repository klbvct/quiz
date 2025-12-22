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
            'has_access' => $request->boolean('has_access'),
            'is_admin' => $request->boolean('is_admin'),
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
        $user->has_access = $request->boolean('has_access');
        $user->is_admin = $request->boolean('is_admin');

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

        // Удаляем пользователя (связанные данные удалятся каскадно благодаря onDelete('cascade'))
        // quiz_sessions -> quiz_answers и quiz_results удалятся автоматически
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален');
    }

    /**
     * Разрешить повторное прохождение теста
     */
    public function enableRetake($id)
    {
        $user = User::findOrFail($id);
        
        // Удаляем только активную сессию в процессе, завершенные оставляем
        QuizSession::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->delete();
        
        // Включаем флаг повторного прохождения
        $user->can_retake = true;
        $user->save();

        return redirect()->route('admin.users.edit', $id)
            ->with('success', 'Повторное прохождение теста разрешено. Пользователь может начать тест заново.');
    }

    public function quizResults($id)
    {
        $user = User::findOrFail($id);
        
        // Получаем завершенную сессию
        $completedSession = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        if (!$completedSession) {
            return redirect()->route('admin.users.edit', $id)
                ->with('error', 'Користувач ще не завершив тестування');
        }
        
        // Получаем результаты
        $quizResult = \App\Models\QuizResult::where('session_id', $completedSession->id)->first();
        
        // Получаем все ответы пользователя
        $answers = \App\Models\QuizAnswer::where('session_id', $completedSession->id)
            ->orderBy('module_number')
            ->orderBy('question_number')
            ->get()
            ->groupBy('module_number');
        
        // Загружаем данные модулей (вопросы и варианты ответов)
        $modulesData = [];
        foreach ($answers->keys() as $moduleNumber) {
            $modulesData[$moduleNumber] = $this->loadModuleData($moduleNumber);
        }
        
        return view('admin.users.quiz-results', compact('user', 'completedSession', 'quizResult', 'answers', 'modulesData'));
    }
    
    /**
     * Загрузить данные модуля из JSON
     */
    private function loadModuleData($module)
    {
        $path = storage_path("app/quiz/module{$module}.json");
        
        if (!file_exists($path)) {
            return null;
        }
        
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    
    /**
     * Экспорт результатов тестирования в CSV
     */
    public function exportQuizResults($id)
    {
        $user = User::findOrFail($id);
        
        // Получаем завершенную сессию
        $completedSession = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        if (!$completedSession) {
            return redirect()->route('admin.users.edit', $id)
                ->with('error', 'Користувач ще не завершив тестування');
        }
        
        // Получаем все ответы пользователя
        $answers = \App\Models\QuizAnswer::where('session_id', $completedSession->id)
            ->orderBy('module_number')
            ->orderBy('question_number')
            ->get();
        
        // Загружаем данные модулей
        $modulesData = [];
        foreach ($answers->groupBy('module_number')->keys() as $moduleNumber) {
            $modulesData[$moduleNumber] = $this->loadModuleData($moduleNumber);
        }
        
        // Создаем CSV
        $filename = 'quiz_results_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($user, $completedSession, $answers, $modulesData) {
            $file = fopen('php://output', 'w');
            
            // BOM для корректного отображения кириллицы в Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Заголовок файла
            fputcsv($file, ['Користувач', $user->name]);
            fputcsv($file, ['Email', $user->email]);
            fputcsv($file, ['Дата завершення', $completedSession->completed_at->format('d.m.Y H:i')]);
            fputcsv($file, []);
            
            // Заголовки таблицы
            fputcsv($file, ['Модуль', '№ Питання', 'Текст питання', 'Відповідь']);
            
            // Данные по каждому ответу
            foreach ($answers as $answer) {
                $moduleNumber = $answer->module_number;
                $moduleData = $modulesData[$moduleNumber] ?? null;
                $questionText = '';
                $answerText = '';
                
                // Получаем текст вопроса
                if ($moduleData) {
                    if (isset($moduleData['values'])) {
                        // Модули 4, 6
                        foreach ($moduleData['values'] as $value) {
                            if ($value['number'] == $answer->question_number) {
                                $questionText = $value['text'];
                                break;
                            }
                        }
                    } elseif (isset($moduleData['questions'])) {
                        foreach ($moduleData['questions'] as $q) {
                            if ($q['number'] == $answer->question_number) {
                                if (isset($q['text'])) {
                                    $questionText = $q['text'];
                                    
                                    // Для модулей с выбором добавляем выбранный вариант
                                    if (isset($q['a']) && isset($q['b'])) {
                                        $userChoice = strtolower(trim($answer->answer));
                                        if ($userChoice === 'a' && isset($q['a'])) {
                                            $questionText .= "\n" . $q['a'];
                                        } elseif ($userChoice === 'b' && isset($q['b'])) {
                                            $questionText .= "\n" . $q['b'];
                                        } elseif ($userChoice === 'c' && isset($q['c'])) {
                                            $questionText .= "\n" . $q['c'];
                                        }
                                    }
                                    
                                    // Для модуля 8 с опциями
                                    if (isset($q['options'])) {
                                        $answerValue = json_decode($answer->answer, true);
                                        if (is_array($answerValue)) {
                                            $parts = [];
                                            foreach ($answerValue as $k => $v) {
                                                $optionText = $q['options'][(int)$k] ?? "Опція $k";
                                                $parts[] = $optionText . ': ' . $v;
                                            }
                                            $answerText = implode('; ', $parts);
                                        }
                                    }
                                } elseif (isset($q['a']) && isset($q['b'])) {
                                    $userChoice = strtolower(trim($answer->answer));
                                    if ($userChoice === 'a') {
                                        $questionText = $q['a'];
                                    } elseif ($userChoice === 'b') {
                                        $questionText = $q['b'];
                                    }
                                }
                                break;
                            }
                        }
                    }
                }
                
                // Если answerText еще не заполнен (не модуль 8)
                if (empty($answerText)) {
                    $answerValue = $answer->answer;
                    $decoded = json_decode($answerValue, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $parts = [];
                        foreach ($decoded as $k => $v) {
                            $parts[] = $k . ': ' . (is_array($v) ? json_encode($v) : $v);
                        }
                        $answerText = implode('; ', $parts);
                    } else {
                        $answerText = $answerValue;
                    }
                }
                
                fputcsv($file, [
                    'Модуль ' . $moduleNumber,
                    $answer->question_number,
                    $questionText,
                    $answerText
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
