<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Добавляем индексы для таблицы quiz_answers
        Schema::table('quiz_answers', function (Blueprint $table) {
            // Составной индекс для быстрого поиска ответов по сессии и модулю
            $table->index(['session_id', 'module_number'], 'quiz_answers_session_module_index');
            
            // Индекс для вопроса (используется в поиске)
            $table->index('question_number', 'quiz_answers_question_index');
        });

        // Добавляем индексы для таблицы quiz_sessions
        Schema::table('quiz_sessions', function (Blueprint $table) {
            // Составной индекс для поиска активных сессий пользователя
            $table->index(['user_id', 'status'], 'quiz_sessions_user_status_index');
            
            // Индекс для сортировки по дате завершения
            $table->index('completed_at', 'quiz_sessions_completed_index');
            
            // Индекс для текущего модуля
            $table->index('current_module', 'quiz_sessions_current_module_index');
        });

        // Добавляем индексы для таблицы quiz_results
        Schema::table('quiz_results', function (Blueprint $table) {
            // Индекс для быстрого поиска результатов по пользователю
            $table->index('user_id', 'quiz_results_user_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->dropIndex('quiz_answers_session_module_index');
            $table->dropIndex('quiz_answers_question_index');
        });

        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->dropIndex('quiz_sessions_user_status_index');
            $table->dropIndex('quiz_sessions_completed_index');
            $table->dropIndex('quiz_sessions_current_module_index');
        });

        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropIndex('quiz_results_user_index');
        });
    }
};
