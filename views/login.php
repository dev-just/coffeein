<div class="auth-card card shadow-sm mx-auto">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">Вход</h1>
        <p class="text-secondary">Войдите, чтобы управлять поставщиками.</p>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login" novalidate class="needs-validation">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" id="email" name="email" type="email" value="<?= e($email) ?>" required autocomplete="email">
                <div class="invalid-feedback">Введите корректный email.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input class="form-control" id="password" name="password" type="password" required autocomplete="current-password">
                <div class="invalid-feedback">Введите пароль.</div>
            </div>
            <button class="btn btn-coffee w-100" type="submit">Войти</button>
        </form>

        <p class="mt-3 mb-0 text-center">Нет аккаунта? <a href="/register">Регистрация</a></p>
    </div>
</div>

