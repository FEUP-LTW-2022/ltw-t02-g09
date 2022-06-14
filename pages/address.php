<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/forms.tpl.php');


  $db = getDatabaseConnection();


  $user = User::getUser($db, $session->getEmail());

  drawHeader($session);
  drawAddressForm($db, $user);
  drawFooter();
?>