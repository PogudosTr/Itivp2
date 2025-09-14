<?php
require_once 'config.php';

// Проверяем, была ли отправлена форма методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы и обрабатываем их
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $exercise_type = isset($_POST['exercise_type']) ? trim($_POST['exercise_type']) : '';
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;
    
    // Простая валидация данных
    if (!empty($title) && !empty($exercise_type) && $duration > 0) {
        try {
            // Подготавливаем SQL-запрос с использованием подготовленных выражений для защиты от SQL-инъекций
            $sql = "INSERT INTO workouts (title, description, exercise_type, duration) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $description, $exercise_type, $duration]);

            // После успешной вставки перенаправляем пользователя на главную страницу
            // Это важно делать до любого вывода HTML
            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            // В случае ошибки выводим её для отладки
            echo "<p class='error-message'>Ошибка базы данных: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error-message'>Пожалуйста, заполните все обязательные поля (Название, Тип, Продолжительность).</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить тренировку</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f7f6; color: #333; line-height: 1.6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; font-weight: 600; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; transition: border-color 0.3s; }
        input[type="text"]:focus, input[type="number"]:focus, textarea:focus { border-color: #3498db; outline: none; }
        .button { padding: 10px 18px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: bold; transition: background-color 0.3s, transform 0.2s; border: none; cursor: pointer; }
        .button:hover { transform: translateY(-2px); }
        .save-button { background-color: #2ecc71; }
        .back-button { background-color: #95a5a6; }
        .button-group { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }
        .error-message { background-color: #fcecec; color: #e74c3c; border: 1px solid #e74c3c; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добавить новую тренировку</h1>
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="title">Название тренировки</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="exercise_type">Тип упражнения</label>
                <input type="text" id="exercise_type" name="exercise_type" required>
            </div>
            <div class="form-group">
                <label for="duration">Продолжительность (мин)</label>
                <input type="number" id="duration" name="duration" required min="1">
            </div>
            <div class="button-group">
                <a href="index.php" class="button back-button">Отмена</a>
                <button type="submit" class="button save-button">Сохранить</button>
            </div>
        </form>
    </div>
</body>
</html>