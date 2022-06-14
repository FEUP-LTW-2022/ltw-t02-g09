<?php 
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../database/order.class.php');
    require_once(__DIR__ . '/../database/dish.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/order.tpl.php');

    $db = getDatabaseConnection();

    $email = $session->getEmail();

    $status = $_POST['change-order-status'];

    $orderId = $_POST['package-order-id'];

    Order::getOrderWithId($db, (int) $orderId);

    $order->status = $status;
    $order->save($db);

    header('Location: ../pages/order_received.php');
?>