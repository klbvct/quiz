// Auth pages functionality

document.addEventListener('DOMContentLoaded', function() {
    // Добавляем класс для стилей страниц авторизации
    document.body.classList.add('auth-page');

    // Функционал показа/скрытия пароля
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput) {
                const eyeOpen = this.querySelector('.eye-open');
                const eyeClosed = this.querySelector('.eye-closed');
                
                // Переключаем тип поля и иконки
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                }
            }
        });
    });
});
