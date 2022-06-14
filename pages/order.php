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

 $order = Order::getOrderWithCustomerId($db, $email);

 drawHeader($session);
 if($order != false){
    $ordered_dishes = Dish::getOrderDishes($db, $order->orderId);
    drawOrderedCustomer($order, $ordered_dishes);
 } else {
    drawNotOrdered();
 }
 drawFooter();


?>