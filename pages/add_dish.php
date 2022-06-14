<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if(!$session->isLoggedIn()) die(header('Location: ../index.php'));

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/category.class.php');
  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();


  $user = User::getUser($db, $session->getEmail());
  if($user->isOwner != 1) die(header('Location: ../index.php'));


  $categories  = Category::getCategories($db, 3);

  drawHeader($session);
  drawDishForm($categories);
  drawFooter();                                                                                                                       
?>