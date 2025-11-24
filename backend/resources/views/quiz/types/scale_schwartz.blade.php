<div class="questions-list scale-type schwartz-scale">
    @foreach($values as $value)
    <div class="question-item">
        <div class="question-text">{{ $value['number'] }}. {{ $value['text'] }}</div>
        <div class="scale-options scale-schwartz">
            @foreach($scale as $scaleValue)
            <label class="scale-option">
                <input type="radio" 
                       name="answers[{{ $value['number'] }}]" 
                       value="{{ $scaleValue }}"
                       {{ isset($savedAnswers[$value['number']]) && $savedAnswers[$value['number']] == $scaleValue ? 'checked' : '' }}
                       required>
                <span>{{ $scaleValue }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
