<?php
require_once 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    try {
        $sql = "DELETE FROM workouts WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "Ошибка при удалении: " . $e->getMessage();
        exit();
    }
}
header("Location: index.php");
exit();
?>
