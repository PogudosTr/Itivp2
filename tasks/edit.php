<?php
require_once 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM workouts WHERE id = ?");
    $stmt->execute([$id]);
    $workout = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$workout) {
        die("Тренировка не найдена.");
    }
} else {
    die("Идентификатор тренировки не указан.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $exercise_type = trim($_POST['exercise_type']);
    $duration = (int)$_POST['duration'];

    if (!empty($title) && !empty($exercise_type) && $duration > 0) {
        $sql = "UPDATE workouts SET title = ?, description = ?, exercise_type = ?, duration = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $exercise_type, $duration, $id]);
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Пожалуйста, заполните все обязательные поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать тренировку</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f7f6; color: #333; line-height: 1.6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; font-weight: 600; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input[type="text"], .form-group textarea, .form-group input[type="number"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 16px; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .button { display: inline-block; padding: 12px 20px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: bold; transition: background-color 0.3s, transform 0.2s; border: none; cursor: pointer; }
        .save-button { background-color: #3498db; }
        .back-button { background-color: #7f8c8d; }
        .error { color: #e74c3c; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактировать тренировку</h1>
        <?php if (isset($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="title">Название:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($workout['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($workout['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="exercise_type">Тип тренировки:</label>
                <input type="text" id="exercise_type" name="exercise_type" value="<?= htmlspecialchars($workout['exercise_type']) ?>" required>
            </div>
            <div class="form-group">
                <label for="duration">Продолжительность (мин):</label>
                <input type="number" id="duration" name="duration" value="<?= htmlspecialchars($workout['duration']) ?>" required min="1">
            </div>
            <button type="submit" class="button save-button">Сохранить изменения</button>
            <a href="index.php" class="button back-button">Отмена</a>
        </form>
    </div>
</body>
</html>
