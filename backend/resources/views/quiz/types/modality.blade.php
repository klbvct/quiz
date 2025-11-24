<div class="questions-list modality-type">
    <p class="modality-hint">Для кожного питання оберіть оцінку від 1 до 4 для кожного варіанту. Кожна цифра може бути використана тільки один раз. Вже обрані цифри зникають зі списку.</p>
    @foreach($questions as $question)
    <div class="question-item modality-question" data-question-id="{{ $question['number'] }}">
        <div class="question-text"><strong>{{ $question['number'] }}.</strong> {{ $question['text'] }}</div>
        <div class="modality-options">
            @foreach($question['options'] as $index => $option)
            <div class="modality-option-group">
                <div class="option-text">{{ $option }}</div>
                <select name="answers[{{ $question['number'] }}][{{ $index }}]" 
                        class="modality-select" 
                        data-question="{{ $question['number'] }}"
                        data-option="{{ $index }}"
                        required>
                    <option value="">Оберіть...</option>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" 
                                {{ isset($savedAnswers[$question['number']][$index]) && $savedAnswers[$question['number']][$index] == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
