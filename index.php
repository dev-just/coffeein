<?php

require_once __DIR__ . '/functions.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = '/' . trim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($route === '/') {
        redirect(current_user() ? '/dashboard' : '/login');
    }

    if ($route === '/register') {
        require_guest();
        $errors = [];
        $name = '';
        $email = '';

        if ($method === 'POST') {
            verify_csrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($name === '') {
                $errors[] = 'Введите имя.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Введите корректный email.';
            }
            if (strlen($password) < 6) {
                $errors[] = 'Пароль должен содержать минимум 6 символов.';
            }

            if (!$errors) {
                $check = db()->prepare('SELECT id FROM users WHERE email = ?');
                $check->execute([$email]);

                if ($check->fetch()) {
                    $errors[] = 'Пользователь с таким email уже существует.';
                } else {
                    $stmt = db()->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
                    $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                    flash('success', 'Регистрация прошла успешно. Теперь войдите.');
                    redirect('/login');
                }
            }
        }

        render('register', compact('errors', 'name', 'email') + ['title' => 'Регистрация']);
        exit;
    }

    if ($route === '/login') {
        require_guest();
        $errors = [];
        $email = '';

        if ($method === 'POST') {
            verify_csrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Введите корректный email.';
            }
            if ($password === '') {
                $errors[] = 'Введите пароль.';
            }

            if (!$errors) {
                $stmt = db()->prepare('SELECT id, password FROM users WHERE email = ?');
                $stmt->execute([$email]);
                $foundUser = $stmt->fetch();

                if (!$foundUser || !password_verify($password, $foundUser['password'])) {
                    $errors[] = 'Неверный email или пароль.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $foundUser['id'];
                    redirect('/dashboard');
                }
            }
        }

        render('login', compact('errors', 'email') + ['title' => 'Вход']);
        exit;
    }

    if ($route === '/logout' && $method === 'POST') {
        require_auth();
        verify_csrf();
        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }

    if ($route === '/dashboard') {
        require_auth();
        $stmt = db()->prepare('SELECT COUNT(*) FROM suppliers WHERE user_id = ?');
        $stmt->execute([current_user()['id']]);
        $suppliersCount = (int) $stmt->fetchColumn();
        render('dashboard', compact('suppliersCount') + ['title' => 'Личный кабинет']);
        exit;
    }

    if ($route === '/suppliers') {
        require_auth();
        $stmt = db()->prepare('SELECT * FROM suppliers WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([current_user()['id']]);
        $suppliers = $stmt->fetchAll();
        render('suppliers', compact('suppliers') + ['title' => 'Поставщики']);
        exit;
    }

    if ($route === '/suppliers/create') {
        require_auth();
        $errors = [];
        $supplier = ['name' => '', 'contact_person' => '', 'phone' => ''];

        if ($method === 'POST') {
            verify_csrf();
            $supplier = [
                'name' => trim($_POST['name'] ?? ''),
                'contact_person' => trim($_POST['contact_person'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
            ];

            if ($supplier['name'] === '') {
                $errors[] = 'Название компании обязательно.';
            }

            if (!$errors) {
                $stmt = db()->prepare(
                    'INSERT INTO suppliers (name, contact_person, phone, user_id) VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([
                    $supplier['name'],
                    $supplier['contact_person'],
                    $supplier['phone'],
                    current_user()['id'],
                ]);
                flash('success', 'Поставщик добавлен.');
                redirect('/suppliers');
            }
        }

        render('supplier_form', compact('errors', 'supplier') + [
            'title' => 'Новый поставщик',
            'formAction' => '/suppliers/create',
            'submitText' => 'Добавить',
        ]);
        exit;
    }

    if (preg_match('#^/suppliers/edit/(\d+)$#', $route, $matches)) {
        require_auth();
        $id = (int) $matches[1];
        $stmt = db()->prepare('SELECT * FROM suppliers WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, current_user()['id']]);
        $supplier = $stmt->fetch();

        if (!$supplier) {
            http_response_code(404);
            render('404', ['title' => 'Поставщик не найден']);
            exit;
        }

        $errors = [];

        if ($method === 'POST') {
            verify_csrf();
            $supplier['name'] = trim($_POST['name'] ?? '');
            $supplier['contact_person'] = trim($_POST['contact_person'] ?? '');
            $supplier['phone'] = trim($_POST['phone'] ?? '');

            if ($supplier['name'] === '') {
                $errors[] = 'Название компании обязательно.';
            }

            if (!$errors) {
                $update = db()->prepare(
                    'UPDATE suppliers SET name = ?, contact_person = ?, phone = ? WHERE id = ? AND user_id = ?'
                );
                $update->execute([
                    $supplier['name'],
                    $supplier['contact_person'],
                    $supplier['phone'],
                    $id,
                    current_user()['id'],
                ]);
                flash('success', 'Данные поставщика обновлены.');
                redirect('/suppliers');
            }
        }

        render('supplier_form', compact('errors', 'supplier') + [
            'title' => 'Редактирование поставщика',
            'formAction' => '/suppliers/edit/' . $id,
            'submitText' => 'Сохранить',
        ]);
        exit;
    }

    if (preg_match('#^/suppliers/delete/(\d+)$#', $route, $matches) && $method === 'POST') {
        require_auth();
        verify_csrf();
        $stmt = db()->prepare('DELETE FROM suppliers WHERE id = ? AND user_id = ?');
        $stmt->execute([(int) $matches[1], current_user()['id']]);
        flash('success', 'Поставщик удален.');
        redirect('/suppliers');
    }

    http_response_code(404);
    render('404', ['title' => 'Страница не найдена']);
} catch (PDOException $error) {
    http_response_code(500);
    render('database_error', ['title' => 'Ошибка базы данных'], false);
}
