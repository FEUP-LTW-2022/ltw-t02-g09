<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/restaurant.class.php');

  $db = getDatabaseConnection();

  $restaurantId = $_SESSION['id'];
  $isChecked = (bool)($_POST['isChecked']);

  Restaurant::toggleFavoriteRestaurant($db, $session->getEmail(), $restaurantId, $isChecked);

  header('Location: ../pages/profile.php');

?>