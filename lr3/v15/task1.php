<?php
/**
 * Завдання 1: Створення класів та об'єктів
 * 
 * Демонстрація: клас Song, створення 3 об'єктів згідно з таблицею
 */
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/Song.php';

// Створюємо 3 об'єкти згідно з даними в таблиці
$song1 = new Song();
$song1->title = 'Червона рута';
$song1->artist = 'Софія Ротару';
$song1->duration = 215;

$song2 = new Song();
$song2->title = 'Обійми';
$song2->artist = 'Океан Ельзи';
$song2->duration = 264;

$song3 = new Song();
$song3->title = 'Плакала';
$song3->artist = 'KAZKA';
$song3->duration = 198;

$songs = [
    ['obj' => $song1, 'avatar' => 'avatar-indigo', 'initial' => '1'],
    ['obj' => $song2, 'avatar' => 'avatar-green', 'initial' => '2'],
    ['obj' => $song3, 'avatar' => 'avatar-amber', 'initial' => '3'],
];

ob_start();
?>

<div class="task-header">
    <h1>Створення об'єктів</h1>
    <p>Клас <code>Song</code> із властивостями: title, artist, duration</p>
</div>

<div class="code-block"><span class="code-comment">// Створюємо об'єкт та задаємо властивості</span>
<span class="code-variable">$song1</span> = <span class="code-keyword">new</span> <span class="code-class">Song</span>();
<span class="code-variable">$song1</span><span class="code-arrow">-></span><span class="code-method">title</span> = <span class="code-string">'Червона рута'</span>;
<span class="code-variable">$song1</span><span class="code-arrow">-></span><span class="code-method">artist</span> = <span class="code-string">'Софія Ротару'</span>;
<span class="code-variable">$song1</span><span class="code-arrow">-></span><span class="code-method">duration</span> = <span class="code-number">215</span>;</div>

<div class="section-divider">
    <span class="section-divider-text">Список пісень (3 об'єкти)</span>
</div>

<div class="users-grid">
    <?php foreach ($songs as $i => $data): ?>
    <div class="user-card">
        <div class="user-card-header">
            <div class="user-card-avatar <?= $data['avatar'] ?>"><?= $data['initial'] ?></div>
            <div>
                <div class="user-card-name"><?= htmlspecialchars($data['obj']->title) ?></div>
                <div class="user-card-label">Об'єкт #<?= $i + 1 ?></div>
            </div>
        </div>
        <div class="user-card-body">
            <div class="user-card-field">
                <span class="user-card-field-label">title</span>
                <span class="user-card-field-value"><?= htmlspecialchars($data['obj']->title) ?></span>
            </div>
            <div class="user-card-field">
                <span class="user-card-field-label">artist</span>
                <span class="user-card-field-value"><?= htmlspecialchars($data['obj']->artist) ?></span>
            </div>
            <div class="user-card-field">
                <span class="user-card-field-label">duration</span>
                <span class="user-card-field-value"><?= htmlspecialchars($data['obj']->duration) ?> сек.</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
renderDemoLayout($content, 'Завдання 1', 'task1-body');