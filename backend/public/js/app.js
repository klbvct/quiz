// Общие функции и инициализация

// Получаем CSRF токен
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Экспортируем для использования в других скриптах
if (typeof window !== 'undefined') {
    window.csrfToken = csrfToken;
}

// Общая функция для AJAX запросов
window.fetchWithCSRF = async function(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...options.headers
        }
    };
    
    return fetch(url, { ...options, ...defaultOptions });
};

// Инициализация после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('App initialized');
});
