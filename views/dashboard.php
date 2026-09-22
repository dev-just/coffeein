<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Личный кабинет</h1>
        <p class="text-secondary mb-0">Здесь можно работать со списком поставщиков.</p>
    </div>
    <a class="btn btn-coffee" href="/suppliers">Открыть поставщиков</a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="dashboard-panel h-100">
            <div class="text-secondary mb-2">Мои поставщики</div>
            <div class="display-5 fw-bold coffee-number"><?= $suppliersCount ?></div>
            <a href="/suppliers">Перейти к списку</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dashboard-panel h-100">
            <h2 class="h5">Быстрое действие</h2>
            <p class="text-secondary">Добавьте новую компанию и контактные данные.</p>
            <a href="/suppliers/create">Добавить поставщика</a>
        </div>
    </div>
</div>
