<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(http_response_code(403));

  require_once(__DIR__ . '/../database/address.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/restaurant.class.php');
  require_once(__DIR__ . '/../database/category.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/profile.tpl.php');
  require_once(__DIR__ . '/../templates/restaurant.tpl.php');

  require_once(__DIR__ . '/../database/connection.db.php');
  $db = getDatabaseConnection();

  $user    = User::getUser($db, $session->getEmail());

  $address = Address::getAddressWithEmail($db, $session->getEmail());
  $categories  = Category::getCategories($db, 3);


  drawHeader($session);
  drawProfile($db, $user, $address);
  if($user->isOwner){
    $ownerRestaurants = Restaurant::getOwnerRestaurants($db, $session->getEmail());
    drawOwnerRestaurants($ownerRestaurants, $categories);
  }
  drawFooter();

?>