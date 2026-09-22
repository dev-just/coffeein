// Простая клиентская проверка форм Bootstrap
document.querySelectorAll('.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        var nameInput = form.querySelector('input[name="name"]');

        if (nameInput && nameInput.value.trim() === '') {
            nameInput.setCustomValidity('Введите значение');
        } else if (nameInput) {
            nameInput.setCustomValidity('');
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });
});

// Подтверждение удаления поставщика
document.querySelectorAll('.delete-form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!confirm('Точно удалить?')) {
            event.preventDefault();
        }
    });
});

