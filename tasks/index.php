<?php
// Подключаем файл с настройками базы данных.
require_once 'config.php';

// Инициализируем переменную для хранения данных о тренировках.
$workouts = []; 

try {
    // Выполняем SQL-запрос для получения всех тренировок, отсортированных по дате создания.
    // Убедитесь, что имя таблицы "workouts" написано правильно.
    $stmt = $pdo->query("SELECT * FROM workouts ORDER BY created_at DESC");
    // Получаем все результаты в виде ассоциативного массива.
    $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // В случае ошибки, выводим сообщение, чтобы можно было понять, что пошло не так.
    // Эта информация очень важна для отладки.
    echo "<p class='error-message'>Ошибка базы данных: " . $e->getMessage() . "</p>";
}

// Функция для определения CSS-класса в зависимости от статуса тренировки.
function getStatusClass($status) {
    return $status === 'выполнена' ? 'completed' : 'not-completed';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Журнал тренировок</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f7f6; color: #333; line-height: 1.6; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 20px; font-weight: 600; }
        .button { display: inline-block; padding: 10px 18px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: bold; transition: background-color 0.3s, transform 0.2s; border: none; cursor: pointer; }
        .button:hover { transform: translateY(-2px); }
        .add-button { background-color: #3498db; }
        .edit-button { background-color: #2ecc71; }
        .delete-button { background-color: #e74c3c; }
        .status-button { background-color: #f39c12; }
        .status-button.completed { background-color: #27ae60; }
        .workout-list { list-style: none; padding: 0; }
        .workout-item { background-color: #ecf0f1; padding: 15px; margin-bottom: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; transition: box-shadow 0.3s; }
        .workout-item:hover { box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .workout-details { flex-grow: 1; }
        .workout-title { margin: 0; font-size: 1.25em; font-weight: bold; color: #2c3e50; }
        .workout-meta { font-size: 0.9em; color: #7f8c8d; margin-top: 5px; }
        .workout-actions { display: flex; gap: 10px; }
        .status-badge { padding: 5px 10px; border-radius: 5px; font-size: 0.8em; font-weight: bold; color: #fff; }
        .status-badge.completed { background-color: #27ae60; }
        .status-badge.not-completed { background-color: #e74c3c; }
        .message { text-align: center; font-size: 1.1em; padding: 20px; border-radius: 8px; }
        .error-message { background-color: #fcecec; color: #e74c3c; border: 1px solid #e74c3c; }
        .info-message { background-color: #e7f3ff; color: #3498db; border: 1px solid #3498db; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Журнал тренировок</h1>
        <a href="add.php" class="button add-button">Добавить новую тренировку</a>
        <br><br>

        <?php if (!empty($workouts)): ?>
            <ul class="workout-list">
                <?php foreach ($workouts as $workout): ?>
                    <li class="workout-item">
                        <div class="workout-details">
                            <h2 class="workout-title"><?= htmlspecialchars($workout['title']) ?></h2>
                            <p><?= htmlspecialchars($workout['description']) ?></p>
                            <div class="workout-meta">
                                Тип: <?= htmlspecialchars($workout['exercise_type']) ?> | 
                                Продолжительность: <?= htmlspecialchars($workout['duration']) ?> мин. | 
                                Создано: <?= date('d.m.Y H:i', strtotime($workout['created_at'])) ?>
                            </div>
                        </div>
                        <div class="workout-actions">
                            <span class="status-badge <?= getStatusClass($workout['status']) ?>"><?= htmlspecialchars($workout['status']) ?></span>
                            <a href="edit.php?id=<?= $workout['id'] ?>" class="button edit-button">Редактировать</a>
                            <a href="update_status.php?id=<?= $workout['id'] ?>" class="button status-button">Изменить статус</a>
                            <a href="delete.php?id=<?= $workout['id'] ?>" class="button delete-button" onclick="return confirm('Вы уверены, что хотите удалить эту тренировку?');">Удалить</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="message info-message">
                <p>Пока нет записей о тренировках. Начните с добавления новой!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>