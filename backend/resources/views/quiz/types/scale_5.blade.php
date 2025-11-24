<div class="questions-list scale-type">
    @foreach($questions as $question)
    <div class="question-item">
        <div class="question-text">{{ $question['number'] }}. {{ $question['text'] }}</div>
        <div class="scale-options">
            @foreach($scale as $value)
            <label class="scale-option">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="{{ $value }}"
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == $value ? 'checked' : '' }}
                       required>
                <span>{{ $value }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
