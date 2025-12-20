// Landing page functionality

document.addEventListener('DOMContentLoaded', function() {
    // Добавляем класс для стилей landing page
    document.body.classList.add('landing-page');

    // Модальное окно
    const modal = document.getElementById('paymentModal');
    const startBtn1 = document.getElementById('startTestBtn');
    const startBtn2 = document.getElementById('startTestBtn2');
    const closeBtn = document.querySelector('.close');

    if (!modal || !startBtn1 || !closeBtn) {
        console.error('Landing page elements not found');
        return;
    }

    function openModal() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    startBtn1.addEventListener('click', openModal);
    
    if (startBtn2) {
        startBtn2.addEventListener('click', openModal);
    }
    
    closeBtn.addEventListener('click', closeModal);

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Обработка формы оплаты
    const paymentForm = document.getElementById('paymentForm');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('paymentEmail').value;
            const submitButton = paymentForm.querySelector('button[type="submit"]');
            
            // Блокируем кнопку
            submitButton.disabled = true;
            submitButton.textContent = 'Перевірка...';
            
            try {
                // Сначала проверяем, можно ли оплатить
                const checkResponse = await fetch('/api/check-access', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ email })
                });

                const checkData = await checkResponse.json();

                if (!checkData.can_pay) {
                    // Показываем сообщение и перенаправляем на вход
                    alert(checkData.message);
                    if (checkData.login_url) {
                        window.location.href = checkData.login_url;
                    }
                    submitButton.disabled = false;
                    submitButton.textContent = 'Оплатити';
                    return;
                }

                // Если можно оплатить - создаём платёж
                submitButton.textContent = 'Створення платежу...';
                
                const response = await fetch('/payment/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ email })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    if (errorData.error) {
                        alert(errorData.error);
                        if (errorData.login_url) {
                            window.location.href = errorData.login_url;
                        }
                        submitButton.disabled = false;
                        submitButton.textContent = 'Оплатити';
                        return;
                    }
                    throw new Error('Ошибка создания платежа');
                }

                const data = await response.json();
                
                // Создаем форму для отправки на LiqPay
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = data.action_url;
                form.innerHTML = `
                    <input type="hidden" name="data" value="${data.data}">
                    <input type="hidden" name="signature" value="${data.signature}">
                `;
                document.body.appendChild(form);
                form.submit();
            } catch (error) {
                alert('Произошла ошибка. Попробуйте еще раз.');
                console.error(error);
                submitButton.disabled = false;
                submitButton.textContent = 'Оплатити';
            }
        });
    }
});
