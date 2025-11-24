document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quiz-form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Проверка заполнения всех обязательных полей
            const requiredInputs = form.querySelectorAll('[required]');
            let allFilled = true;
            
            requiredInputs.forEach(input => {
                if (input.type === 'radio') {
                    const name = input.name;
                    const checked = form.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        allFilled = false;
                    }
                } else if (!input.value) {
                    allFilled = false;
                }
            });
            
            if (!allFilled) {
                e.preventDefault();
                alert('Будь ласка, дайте відповідь на всі питання перед продовженням.');
                return false;
            }
        });
    }
    
    // Для ranking типа (legacy number inputs) - проверка уникальности значений
    const rankingInputs = document.querySelectorAll('.ranking-input');
    if (rankingInputs.length > 0) {
        rankingInputs.forEach(input => {
            input.addEventListener('blur', function() {
                checkRankingDuplicates();
            });
        });
    }
    
    function checkRankingDuplicates() {
        const values = [];
        const inputs = document.querySelectorAll('.ranking-input');
        let hasDuplicates = false;
        
        inputs.forEach(input => {
            input.style.borderColor = '#e5e7eb';
            if (input.value && values.includes(input.value)) {
                input.style.borderColor = '#ef4444';
                hasDuplicates = true;
            }
            if (input.value) {
                values.push(input.value);
            }
        });
        
        return !hasDuplicates;
    }
    
    // Для ranking типа (новые dropdown selects) - динамическая фильтрация
    initRankingDropdowns();
    
    // Для modality типа (модуль 8) - динамическая фильтрация в пределах каждого вопроса
    initModalityDropdowns();
});

// Динамическая фильтрация рангов для модуля 4
function initRankingDropdowns() {
    const selects = document.querySelectorAll('.ranking-select');
    if (selects.length === 0) return;

    // Определяем максимальный ранг из первого select
    const firstSelect = selects[0];
    const maxRank = firstSelect.querySelectorAll('option').length - 1; // минус пустая опция

    function updateDropdowns() {
        // Собираем все выбранные значения
        const selectedValues = new Set();
        selects.forEach(select => {
            if (select.value) {
                selectedValues.add(select.value);
            }
        });

        // Для каждого select обновляем доступные опции
        selects.forEach(select => {
            const currentValue = select.value;
            
            // Сохраняем текущий выбор
            const temp = currentValue;
            
            // Очищаем все опции
            select.innerHTML = '<option value="">Оберіть...</option>';
            
            // Добавляем только доступные опции
            for (let i = 1; i <= maxRank; i++) {
                const value = i.toString();
                
                // Добавляем опцию если она либо текущая, либо не выбрана в других select
                if (value === currentValue || !selectedValues.has(value)) {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    
                    // Восстанавливаем selected для текущего значения
                    if (value === temp) {
                        option.selected = true;
                    }
                    
                    select.appendChild(option);
                }
            }
        });
    }

    // Добавляем обработчики события change на все селекты
    selects.forEach(select => {
        select.addEventListener('change', updateDropdowns);
    });

    // Инициализируем состояние при загрузке (если есть сохраненные ответы)
    updateDropdowns();
}

// Динамическая фильтрация для модуля 8 (modality)
function initModalityDropdowns() {
    const questions = document.querySelectorAll('.modality-question');
    if (questions.length === 0) return;

    // Для каждого вопроса инициализируем независимую фильтрацию
    questions.forEach(question => {
        const questionId = question.getAttribute('data-question-id');
        const selects = question.querySelectorAll('.modality-select');
        
        if (selects.length === 0) return;

        function updateQuestionDropdowns() {
            // Собираем все выбранные значения в пределах этого вопроса
            const selectedValues = new Set();
            selects.forEach(select => {
                if (select.value) {
                    selectedValues.add(select.value);
                }
            });

            // Для каждого select обновляем доступные опции
            selects.forEach(select => {
                const currentValue = select.value;
                
                // Очищаем все опции
                select.innerHTML = '<option value="">Оберіть...</option>';
                
                // Добавляем только доступные опции (1-4)
                for (let i = 1; i <= 4; i++) {
                    const value = i.toString();
                    
                    // Добавляем опцию если она либо текущая, либо не выбрана в других select
                    if (value === currentValue || !selectedValues.has(value)) {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = value;
                        
                        // Восстанавливаем selected для текущего значения
                        if (value === currentValue) {
                            option.selected = true;
                        }
                        
                        select.appendChild(option);
                    }
                }
            });
        }

        // Добавляем обработчики события change на все селекты этого вопроса
        selects.forEach(select => {
            select.addEventListener('change', updateQuestionDropdowns);
        });

        // Инициализируем состояние при загрузке
        updateQuestionDropdowns();
    });
}
