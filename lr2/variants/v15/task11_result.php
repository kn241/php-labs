<?php
/**
 * Завдання 11: Калькулятор — результати обчислень
 *
 * Варіант 15: X = 6, Y = 3
 * sin(6)=-0.2794, cos(6)=0.9602, tg(6)=-0.2910, 6^3=216, 6!=720
 */
require_once __DIR__ . '/layout.php';

// --- Функції (Function/func.php) ---

function my_sin(float $x): float
{
    return sin($x);
}

function my_cos(float $x): float
{
    return cos($x);
}

function my_tan(float $x): float
{
    return tan($x);
}

function my_tg(float $x): string|float
{
    $cosX = cos($x);
    if (abs($cosX) < 1e-10) {
        return 'Не визначено (cos(x) = 0)';
    }
    return sin($x) / $cosX;
}

function my_pow(float $x, float $y): float
{
    return pow($x, $y);
}

function my_factorial(int $n): string|int
{
    if ($n < 0) {
        return 'Не визначено (x < 0)';
    }
    if ($n > 20) {
        return 'Занадто велике число';
    }
    if ($n <= 1) {
        return 1;
    }
    return $n * my_factorial($n - 1);
}

// --- Обробка даних ---
// Якщо дані не прийшли через POST, спробуємо GET (для зручності тестування) або ставимо дефолтні 6 та 3
$x = isset($_POST['x']) ? (float)$_POST['x'] : (isset($_GET['x']) ? (float)$_GET['x'] : 6);
$y = isset($_POST['y']) ? (float)$_POST['y'] : (isset($_GET['y']) ? (float)$_GET['y'] : 3);

// Обчислення результатів
$results = [
    ['func' => 'sin(x)', 'expression' => "sin($x)", 'value' => my_sin($x)],
    ['func' => 'cos(x)', 'expression' => "cos($x)", 'value' => my_cos($x)],
    ['func' => 'tg(x)', 'expression' => "tan($x)", 'value' => my_tan($x)],
    ['func' => 'sin(x)/cos(x)', 'expression' => "my_tg($x)", 'value' => my_tg($x)],
    ['func' => 'x^y', 'expression' => "{$x}^{$y}", 'value' => my_pow($x, $y)],
    ['func' => 'x!', 'expression' => (int)$x . '!', 'value' => my_factorial((int)$x)],
];

ob_start();
?>
<div class="demo-card demo-card-wide">
    <h2>Результати обчислень (Варіант 15)</h2>

    <div class="demo-grid-2">
        <div class="demo-result demo-result-info">
            <h3>Значення X</h3>
            <div class="demo-result-value"><?= htmlspecialchars((string)$x) ?></div>
        </div>
        <div class="demo-result demo-result-info">
            <h3>Значення Y</h3>
            <div class="demo-result-value"><?= htmlspecialchars((string)$y) ?></div>
        </div>
    </div>

    <table class="demo-table mt-15">
        <thead>
            <tr>
                <th>Функція</th>
                <th>Вираз</th>
                <th>Результат</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><strong><?= htmlspecialchars($row['func']) ?></strong></td>
                <td><code><?= htmlspecialchars($row['expression']) ?></code></td>
                <td>
                    <?php
                    if (is_string($row['value'])) {
                        echo '<span class="demo-tag demo-tag-error">' . htmlspecialchars($row['value']) . '</span>';
                    } else {
                        // Виводимо 4 знаки після коми для тригонометрії, або ціле число
                        echo (floor($row['value']) == $row['value']) ? $row['value'] : number_format($row['value'], 4, '.', '');
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="flex-buttons mt-15">
        <a href="task11_calc.php" class="btn-secondary">Повернутися до калькулятора</a>
    </div>

    <div class="demo-code" style="white-space: pre;">// Результати для Варіанта 15 (x=<?= $x ?>, y=<?= $y ?>)
my_sin(<?= $x ?>) = <?= number_format($results[0]['value'], 4) ?>
my_cos(<?= $x ?>) = <?= number_format($results[1]['value'], 4) ?>
my_tan(<?= $x ?>) = <?= number_format($results[2]['value'], 4) ?>
my_pow(<?= $x ?>, <?= $y ?>) = <?= $results[4]['value'] ?>
my_factorial(<?= (int)$x ?>) = <?= $results[5]['value'] ?>
    </div>
</div>
<?php
$content = ob_get_clean();
renderVariantLayout($content, 'Завдання 11');