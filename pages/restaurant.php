<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/restaurant.class.php');
  require_once(__DIR__ . '/../database/menu.class.php');
  require_once(__DIR__ . '/../database/category.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/address.class.php');
  require_once(__DIR__ . '/../database/dish.class.php');
  require_once(__DIR__ . '/../database/order.class.php');


  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/restaurant.tpl.php');
  require_once(__DIR__ . '/../templates/order.tpl.php');

  $db = getDatabaseConnection();

  $restaurantOwner = false;


  $ownerId = Restaurant::getRestaurantOwnerId($db, intval($_GET['id']));

  if($session->isLoggedIn()){
    if($ownerId == $session->getEmail()){
      $restaurantOwner = true;
    }
  }

  $restaurant  = Restaurant::getRestaurant($db, intval($_GET['id']));
  $menus       = Menu::getMenus($db, 10);

  $address     = Address::getAddressWithResId($db, intval($_GET['id']));

  
  $dishes      = Dish::getRestaurantDishes($db, $restaurant->restaurantId);
  $categories  = Category::getCategories($db, 10);

  $_SESSION['id'] = $restaurant->restaurantId;

  drawHeader($session);
  drawRestaurant($restaurant, $categories, $dishes, $address, $restaurantOwner, $session);
  if($session->isLoggedIn()){
    $order = Order::getOrderWithCustomerId($db, $session->getEmail());
    $userAddress = Address::getAddressWithEmail($db, $session->getEmail());
    if($userAddress != null){
      if($order == null){
        drawOrderTable();
      }
      else {
        drawOrderBeingProcessed();
      }
    }
    else {
      drawNoAddress();
    }
  }
  drawFooter();                                                                                                                       
?>