<div class="form-card card shadow-sm mx-auto">
    <div class="card-body p-4">
        <a href="/suppliers" class="text-secondary">← К списку</a>
        <h1 class="h3 mt-2 mb-4"><?= e($title) ?></h1>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e($formAction) ?>" novalidate class="needs-validation supplier-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Название компании *</label>
                <input class="form-control" id="name" name="name" value="<?= e($supplier['name']) ?>" required>
                <div class="invalid-feedback">Название компании обязательно.</div>
            </div>
            <div class="mb-3">
                <label for="contact_person" class="form-label">Контактное лицо</label>
                <input class="form-control" id="contact_person" name="contact_person" value="<?= e($supplier['contact_person']) ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="form-label">Телефон</label>
                <input class="form-control" id="phone" name="phone" type="tel" value="<?= e($supplier['phone']) ?>" placeholder="+7 999 000-00-00">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-coffee" type="submit"><?= e($submitText) ?></button>
                <a class="btn btn-outline-secondary" href="/suppliers">Отмена</a>
            </div>
        </form>
    </div>
</div>

