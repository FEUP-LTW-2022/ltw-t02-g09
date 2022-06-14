<?php 
 declare(strict_types = 1);

 require_once(__DIR__ . '/../utils/session.php');
 $session = new Session();

 require_once(__DIR__ . '/../database/connection.db.php');

 require_once(__DIR__ . '/../database/order.class.php');
 require_once(__DIR__ . '/../database/dish.class.php');
 require_once(__DIR__ . '/../database/restaurant.class.php');

 require_once(__DIR__ . '/../templates/common.tpl.php');
 require_once(__DIR__ . '/../templates/order.tpl.php');

 $db = getDatabaseConnection();

 $email = $session->getEmail();

 $restaurantOwner = false;

 $orders = null;

 $restaurants = Restaurant::getOwnerRestaurants($db, $email);

 drawHeader($session);
 if($session->isLoggedIn()){
    if($email == $session->getEmail()){
        foreach($restaurants as $restaurant){
            $orders = Order::getRestaurantOrders($db, $restaurant->restaurantId);
            if($orders != null){
                drawRestaurantOrder($restaurant);
                foreach($orders as $order){
                    $ordered_dishes = Dish::getOrderDishes($db, $order['OrderId']);
                    drawOrderedOwner($ordered_dishes, $order, $restaurant);
                }
            }
        }
    }   
 }   
 drawFooter();


?>