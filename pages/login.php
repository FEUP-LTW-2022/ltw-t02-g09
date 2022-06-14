<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  
  require_once(__DIR__ . '/../database/connection.db.php');

  if ($session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();

  

  drawHeader($session);
  drawLoginForm();
  drawFooter();
?>