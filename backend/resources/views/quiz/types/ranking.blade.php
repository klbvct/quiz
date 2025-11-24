<div class="ranking-list">
    <p class="ranking-hint">Оберіть місце для кожної цінності від 1 до {{ $maxRank }}, де 1 - найважливіше, {{ $maxRank }} - найменш важливе. Вже обрані місця зникають зі списку.</p>
    @foreach($values as $value)
    <div class="ranking-item">
        <div class="ranking-text">{{ $value['number'] }}. {{ $value['text'] }}</div>
        <select name="answers[{{ $value['number'] }}]" 
                class="ranking-select" 
                data-question="{{ $value['number'] }}"
                required>
            <option value="">Оберіть...</option>
            @for($i = 1; $i <= $maxRank; $i++)
                <option value="{{ $i }}" 
                        {{ isset($savedAnswers[$value['number']]) && $savedAnswers[$value['number']] == $i ? 'selected' : '' }}>
                    {{ $i }}
                </option>
            @endfor
        </select>
    </div>
    @endforeach
</div>
