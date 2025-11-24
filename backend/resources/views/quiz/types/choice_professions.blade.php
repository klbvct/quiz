<div class="questions-list professions">
    @foreach($questions as $question)
    <div class="question-item profession-choice">
        <div class="question-number">{{ $question['number'] }}.</div>
        <div class="profession-options">
            <label class="profession-label">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="a" 
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == 'a' ? 'checked' : '' }}
                       required>
                <span>{{ $question['a'] }}</span>
            </label>
            <span class="or-separator">або</span>
            <label class="profession-label">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="b"
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == 'b' ? 'checked' : '' }}>
                <span>{{ $question['b'] }}</span>
            </label>
        </div>
    </div>
    @endforeach
</div>
