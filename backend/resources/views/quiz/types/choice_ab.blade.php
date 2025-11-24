<div class="questions-list">
    @foreach($questions as $question)
    <div class="question-item">
        <div class="question-number">{{ $question['number'] }}.</div>
        <div class="question-options">
            <label class="option-label">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="a" 
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == 'a' ? 'checked' : '' }}
                       required>
                <span>а) {{ $question['a'] }}</span>
            </label>
            <label class="option-label">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="b"
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == 'b' ? 'checked' : '' }}>
                <span>б) {{ $question['b'] }}</span>
            </label>
        </div>
    </div>
    @endforeach
</div>
