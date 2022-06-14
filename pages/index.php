<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/restaurant.class.php');

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../templates/restaurant.tpl.php');

    $db = getDatabaseConnection();

    $restaurants = Restaurant::getRestaurants($db, 10);
    $categories  = Category::getCategories($db,10);

    drawHeader($session);
    drawRestaurants($restaurants, $categories);
    drawFooter();

?>
