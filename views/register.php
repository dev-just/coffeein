<div class="auth-card card shadow-sm mx-auto">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">Регистрация</h1>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/register" novalidate class="needs-validation">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input class="form-control" id="name" name="name" value="<?= e($name) ?>" required autocomplete="name">
                <div class="invalid-feedback">Введите имя.</div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email">
                <div class="invalid-feedback">Введите корректный email.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input class="form-control" id="password" name="password" type="password" minlength="6" required autocomplete="new-password">
                <div class="invalid-feedback">Минимум 6 символов.</div>
            </div>
            <button class="btn btn-coffee w-100" type="submit">Зарегистрироваться</button>
        </form>

        <p class="mt-3 mb-0 text-center">Уже есть аккаунт? <a href="/login">Войти</a></p>
    </div>
</div>

