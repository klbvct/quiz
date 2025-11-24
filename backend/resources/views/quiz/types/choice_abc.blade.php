<div class="questions-list">
    @foreach($questions as $question)
    <div class="question-item">
        <div class="question-text"><strong>{{ $question['number'] }}.</strong> {{ $question['text'] }}</div>
        <div class="question-options vertical">
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
            <label class="option-label">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="c"
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == 'c' ? 'checked' : '' }}>
                <span>в) {{ $question['c'] }}</span>
            </label>
        </div>
    </div>
    @endforeach
</div>
