<div class="questions-list scale-type">
    @foreach($questions as $question)
    <div class="question-item">
        <div class="question-text">{{ $question['number'] }}. {{ $question['text'] }}</div>
        <div class="scale-options scale-1-5">
            @for($i = 1; $i <= 5; $i++)
            <label class="scale-option">
                <input type="radio" 
                       name="answers[{{ $question['number'] }}]" 
                       value="{{ $i }}"
                       {{ isset($savedAnswers[$question['number']]) && $savedAnswers[$question['number']] == $i ? 'checked' : '' }}
                       required>
                <span>{{ $i }}</span>
            </label>
            @endfor
        </div>
    </div>
    @endforeach
</div>
