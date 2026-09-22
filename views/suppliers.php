<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Поставщики</h1>
        <a href="/dashboard" class="text-secondary">← В личный кабинет</a>
    </div>
    <a class="btn btn-coffee" href="/suppliers/create">Добавить поставщика</a>
</div>

<?php if (!$suppliers): ?>
    <div class="card shadow-sm">
        <div class="card-body py-5 text-center">
            <h2 class="h5">Список пока пуст</h2>
            <p class="text-secondary">Добавьте первого поставщика.</p>
            <a class="btn btn-coffee" href="/suppliers/create">Добавить</a>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Компания</th>
                        <th>Контактное лицо</th>
                        <th>Телефон</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($supplier['name']) ?></td>
                        <td><?= e($supplier['contact_person']) ?: '—' ?></td>
                        <td><?= e($supplier['phone']) ?: '—' ?></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a class="btn btn-sm btn-outline-secondary" href="/suppliers/edit/<?= (int) $supplier['id'] ?>">Редактировать</a>
                                <form class="delete-form" action="/suppliers/delete/<?= (int) $supplier['id'] ?>" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

