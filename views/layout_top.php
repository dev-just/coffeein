<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Внутренний портал сети кофеен Кофеин для работы с поставщиками">
    <title><?= e($title ?? 'Кофеин') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="<?= $user ? 'app-page' : 'auth-page' ?>">
<nav class="navbar navbar-expand-lg coffee-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $user ? '/dashboard' : '/login' ?>">Кофеин</a>
        <?php if ($user): ?>
            <div class="d-flex align-items-center gap-3">
                <span class="d-none d-sm-inline">Здравствуйте, <?= e($user['name']) ?></span>
                <form action="/logout" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Выйти</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>

<main class="container">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>" role="alert">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>
