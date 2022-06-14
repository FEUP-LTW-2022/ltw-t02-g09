<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(http_response_code(403));
    

    require_once(__DIR__ . '/../database/connection.db.php');
    
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');
    require_once(__DIR__ . '/../database/address.class.php');
    require_once(__DIR__ . '/../database/order.class.php');


    require_once(__DIR__ . '/../templates/forms.tpl.php');


    $db = getDatabaseConnection();
    $restaurantId = $_SESSION['id'];
    $customerId  = $session->getEmail();

    $address = Address::getAddressWithEmail($db, $session->getEmail());

    Order::createOrder($db, $restaurantId, $customerId, floatval($_POST['price']), "teste", $address->id);

    $_SESSION['order-id'] = $db->lastInsertId();

?>