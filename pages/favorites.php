<?php 
 declare(strict_types = 1);

 require_once(__DIR__ . '/../utils/session.php');
 $session = new Session();

 require_once(__DIR__ . '/../database/connection.db.php');

 require_once(__DIR__ . '/../database/restaurant.class.php');

 require_once(__DIR__ . '/../templates/common.tpl.php');
 require_once(__DIR__ . '/../templates/profile.tpl.php');

 $db = getDatabaseConnection();

 $email = $session->getEmail();

 $restaurants = Restaurant::getFavoriteRestaurants($db, $email);


 drawHeader($session);
 drawFavorite($restaurants);
 drawFooter();


?>