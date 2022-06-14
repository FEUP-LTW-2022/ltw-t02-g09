<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/restaurant.class.php');
  require_once(__DIR__ . '/../database/address.class.php');
  require_once(__DIR__ . '/../database/category.class.php');


  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();

  $restaurant = Restaurant::getRestaurant($db, $_SESSION['id']);

  $address = Address::getAddressWithResId($db, $_SESSION['id']);


  if ($address){
    $address->addressOne =  $_POST['rest-addr-one'];
    $address->addressTwo =  $_POST['rest-addr-two'];
    $address->city       =  $_POST['rest-city'];
    $address->country    =  $_POST['rest-country'];
    $address->postalcode =  $_POST['rest-postalcode'];

    $address->save($db);
  }

  $category = Category::getCategoryByName($db, $_POST['rest-category']);


  if ($restaurant){
    $restaurant->restaurantName =  $_POST['rest-name'];
    $restaurant->review         =  (float) $_POST['rest-review'];
    $restaurant->price          =  $_POST['rest-price'];
    $restaurant->categoryId     =  $category->categoryId;

    $restaurant->save($db);
  }



  header('Location: ../pages/profile.php');



?>