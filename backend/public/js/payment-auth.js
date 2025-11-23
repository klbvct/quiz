// Payment functionality for authenticated users

document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentFormAuth');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Показываем загрузку
            submitButton.disabled = true;
            submitButton.innerHTML = '<span>Обработка...</span>';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('/payment/create', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Ошибка при создании платежа');
                }
                
                const data = await response.json();
                
                // Создаем форму для отправки на LiqPay
                const liqpayForm = document.createElement('form');
                liqpayForm.method = 'POST';
                liqpayForm.action = data.action_url;
                liqpayForm.style.display = 'none';
                
                // Добавляем поля data и signature
                const dataInput = document.createElement('input');
                dataInput.name = 'data';
                dataInput.value = data.data;
                liqpayForm.appendChild(dataInput);
                
                const signatureInput = document.createElement('input');
                signatureInput.name = 'signature';
                signatureInput.value = data.signature;
                liqpayForm.appendChild(signatureInput);
                
                document.body.appendChild(liqpayForm);
                liqpayForm.submit();
                
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка при создании платежа. Пожалуйста, попробуйте еще раз.');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    }
});
