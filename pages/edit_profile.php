<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/user.class.php');
  

  if (!$session->isLoggedIn()) die(http_response_code(500));

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getEmail());

  drawHeader($session);
  drawProfileForm($user);
  drawFooter();


?>