<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(http_response_code(403));
    

    require_once(__DIR__ . '/../database/connection.db.php');
    
    require_once(__DIR__ . '/../database/order.class.php');



    $db = getDatabaseConnection();
    
    $orderId = $_SESSION['order-id'];

    OrderedDish::addDishToOrder($db, $orderId, intval($_POST['dishId']), intval($_POST['dishQuantity']));

?>