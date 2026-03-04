<?php
/**
 * Завдання 10: Реєстраційна форма
 *
 * Варіант 15: логін "maksym_vovk15"
 * POST, сесія, cookie (мова на 6 місяців), завантаження фото
 */
session_start();
require_once __DIR__ . '/layout.php';

// --- Вибір мови (Cookie на 6 місяців) ---
$languages = [
    'uk' => '🇺🇦 Укр',
    'en' => '🇬🇧 Eng',
    'de' => '🇩🇪 Deu',
];

if (isset($_GET['lang']) && isset($languages[$_GET['lang']])) {
    $lang = $_GET['lang'];
    // 6 місяців = 6 * 30 днів * 24 год * 3600 сек
    setcookie('lang', $lang, time() + (6 * 30 * 24 * 3600), '/');
} elseif (isset($_COOKIE['lang']) && isset($languages[$_COOKIE['lang']])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'uk';
}

// --- Міста ---
$cities = [
    'Київ', 'Львів', 'Одеса', 'Харків', 'Дніпро',
    'Запоріжжя', 'Вінниця', 'Полтава', 'Чернігів', 'Тернопіль',
];

// --- Хобі ---
$hobbies = [
    'sport' => 'Спорт',
    'music' => 'Музика',
    'reading' => 'Читання',
    'gaming' => 'Ігри',
    'travel' => 'Подорожі',
];

// --- Отримання даних з сесії для автозаповнення ---
$sessionData = $_SESSION['reg_data'] ?? [];

// --- Обробка форми ---
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $city = $_POST['city'] ?? '';
    $selectedHobbies = $_POST['hobbies'] ?? [];
    $about = trim($_POST['about'] ?? '');

    // Валідація
    if ($login === '') {
        $errors[] = 'Логін не може бути порожнім';
    }
    if (mb_strlen($password) < 4) {
        $errors[] = 'Пароль повинен бути не менше 4 символів';
    }
    if ($password !== $password2) {
        $errors[] = 'Паролі не збігаються';
    }
    if (empty($gender)) {
        $errors[] = 'Оберіть стать';
    }
    if ($city === '') {
        $errors[] = 'Оберіть місто';
    }

    // Обробка фото
    $photoPath = $sessionData['photo'] ?? '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($_FILES['photo']['type'], $allowedTypes)) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $newName = 'user_' . uniqid() . '.' . $ext;
            $destination = $uploadDir . $newName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $photoPath = 'uploads/' . $newName;
            }
        } else {
            $errors[] = 'Недопустимий формат фото (дозволено: JPG, PNG, WEBP)';
        }
    }

    // Збереження в сесію навіть при помилках (для автозаповнення)
    $regData = [
        'login' => $login,
        'gender' => $gender,
        'city' => $city,
        'hobbies' => $selectedHobbies,
        'about' => $about,
        'photo' => $photoPath,
    ];
    $_SESSION['reg_data'] = $regData;

    // Якщо помилок немає — редирект на сторінку результату
    if (empty($errors)) {
        header('Location: task10_result.php');
        exit;
    }
}

// Дані для відображення в полях (пріоритет: POST -> Сесія -> Дефолт)
$formData = [
    'login' => $_POST['login'] ?? $sessionData['login'] ?? 'maksym_vovk15',
    'gender' => $_POST['gender'] ?? $sessionData['gender'] ?? '',
    'city' => $_POST['city'] ?? $sessionData['city'] ?? '',
    'hobbies' => $_POST['hobbies'] ?? $sessionData['hobbies'] ?? [],
    'about' => $_POST['about'] ?? $sessionData['about'] ?? '',
];

ob_start();
?>
<div class="demo-card demo-card-wide">
    <h2>Реєстрація (Варіант 15)</h2>

    <!-- Вибір мови (іконки/посилання) -->
    <div class="lang-selector" style="margin-bottom: 20px; text-align: right;">
        <?php foreach ($languages as $code => $name): ?>
        <a href="?lang=<?= $code ?>" class="btn-secondary <?= $lang === $code ? 'active' : '' ?>" style="text-decoration: none; padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px;">
            <?= $name ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="demo-result demo-result-error">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="demo-form">
        <!-- Логін -->
        <div class="form-group">
            <label for="login">Логін:</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($formData['login']) ?>" required>
        </div>

        <!-- Паролі -->
        <div class="form-row">
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" placeholder="Мін. 4 символи">
            </div>
            <div class="form-group">
                <label for="password2">Підтвердження:</label>
                <input type="password" id="password2" name="password2">
            </div>
        </div>

        <!-- Стать -->
        <div class="form-group">
            <label>Стать:</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?= $formData['gender'] === 'male' ? 'checked' : '' ?>> Чоловіча</label>
                <label><input type="radio" name="gender" value="female" <?= $formData['gender'] === 'female' ? 'checked' : '' ?>> Жіноча</label>
            </div>
        </div>

        <!-- Місто -->
        <div class="form-group">
            <label for="city">Місто:</label>
            <select id="city" name="city">
                <option value="">-- Оберіть місто --</option>
                <?php foreach ($cities as $c): ?>
                <option value="<?= $c ?>" <?= $formData['city'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Хобі -->
        <div class="form-group">
            <label>Хобі:</label>
            <div class="checkbox-group">
                <?php foreach ($hobbies as $val => $label): ?>
                <label>
                    <input type="checkbox" name="hobbies[]" value="<?= $val ?>" <?= in_array($val, $formData['hobbies']) ? 'checked' : '' ?>>
                    <?= $label ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Про себе -->
        <div class="form-group">
            <label for="about">Про себе:</label>
            <textarea id="about" name="about" rows="3"><?= htmlspecialchars($formData['about']) ?></textarea>
        </div>

        <!-- Фото -->
        <div class="form-group">
            <label for="photo">Фото профілю:</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <?php if (!empty($sessionData['photo'])): ?>
                <small style="color: green;">✔ Фото завантажено раніше</small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Зареєструватися</button>
    </form>
</div>
<?php
$content = ob_get_clean();
renderVariantLayout($content, 'Завдання 10');