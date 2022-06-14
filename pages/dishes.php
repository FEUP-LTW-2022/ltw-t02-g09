<?php
  declare(strict_types = 1);

  session_start();

  require_once('database/connection.db.php');
  require_once('database/menu.class.php');

  require_once('templates/common.tpl.php');
  require_once('templates/dish.tpl.php');

  $db = getDatabaseConnection();

  $dishes = Dish::getDishes($db, 8);

  drawHeader();
  drawDishes($dishes);
  drawFooter();
?>