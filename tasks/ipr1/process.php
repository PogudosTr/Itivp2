<?php
require_once 'config.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $flower_type = trim($_POST['flower_type'] ?? '');
    $bouquet_price = trim($_POST['bouquet_price'] ?? '');
    $recipient_name = trim($_POST['recipient_name'] ?? '');
    $delivery_address = trim($_POST['delivery_address'] ?? '');

    if (empty($flower_type)) {
        $errors[] = "Тип цветка не может быть пустым.";
    } elseif (strlen($flower_type) > 100) {
        $errors[] = "Тип цветка слишком длинный (макс. 100 символов).";
    }

    if (empty($bouquet_price)) {
        $errors[] = "Цена букета не может быть пустой.";
    } elseif (!is_numeric($bouquet_price) || $bouquet_price <= 0) {
        $errors[] = "Цена букета должна быть положительным числом.";
    }
    $bouquet_price_float = (float)$bouquet_price;

    if (empty($recipient_name)) {
        $errors[] = "Имя получателя не может быть пустым.";
    } elseif (strlen($recipient_name) > 255) {
        $errors[] = "Имя получателя слишком длинное (макс. 255 символов).";
    }

    if (empty($delivery_address)) {
        $errors[] = "Адрес доставки не может быть пустым.";
    }
    
    
    if (empty($errors)) {

        $sql = "INSERT INTO flower_orders (flower_type, bouquet_price, recipient_name, delivery_address) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sds", $param_type, $param_price, $param_name, $param_address);
            
            $param_type = $flower_type;
            $param_price = $bouquet_price_float;
            $param_name = $recipient_name;
            $param_address = $delivery_address;
            
            if (mysqli_stmt_execute($stmt)) {
                $message_type = 'success';
                $message = "🎉 **Данные успешно сохранены!** Ваш заказ принят. Номер заказа: " . mysqli_insert_id($link);
            } else {
                $message_type = 'error';
                $message = "❌ **Произошла ошибка при выполнении запроса:** " . mysqli_error($link);
            }

            mysqli_stmt_close($stmt);
        } else {
             $message_type = 'error';
             $message = "❌ **Ошибка подготовки SQL-запроса:** " . mysqli_error($link);
        }
        
    } else {
        $message_type = 'warning';
        $message = "⚠️ **Обнаружены ошибки валидации:**";
    }

    mysqli_close($link);

} else {
    header("location: form.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат Обработки Заказа</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        ul { list-style-type: disc; margin-left: 20px; }
        a { color: #ff69b4; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Результат Обработки Заказа</h1>
        
        <?php if (isset($message_type)): ?>
            <div class="<?php echo $message_type; ?>">
                <h2><?php echo $message; ?></h2>
                
                <?php 
                // Выводим список ошибок, если они были
                if ($message_type == 'warning' && !empty($errors)) {
                    echo "<ul>";
                    foreach ($errors as $err) {
                        echo "<li>" . htmlspecialchars($err) . "</li>";
                    }
                    echo "</ul>";
                } 
                ?>
            </div>
        <?php endif; ?>
        
        <p><a href="form.html">← Вернуться к форме заказа</a></p>
    </div>
</body>
</html>