<?php
require_once 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT status FROM workouts WHERE id = ?");
        $stmt->execute([$id]);
        $current_status = $stmt->fetchColumn();
        $new_status = ($current_status === 'выполнена') ? 'не выполнена' : 'выполнена';
        $sql = "UPDATE workouts SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_status, $id]);
    } catch (PDOException $e) {
        echo "Ошибка при обновлении статуса: " . $e->getMessage();
        exit();
    }
}

header("Location: index.php");
exit();
?>
