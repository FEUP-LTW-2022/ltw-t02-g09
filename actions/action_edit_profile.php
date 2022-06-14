<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/user.class.php');

  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getEmail());

  if ($user){
    $user->firstName = $_POST['edit-first-name'];
    $user->lastName  = $_POST['edit-last-name'];
    $user->password  = $_POST['edit-password'];
    $user->mobile    = $_POST['edit-mobile'];

      
    $session->setName($user->name());

    $user->save($db);


    $session->setName($user->name());
  }

  header('Location: ../pages/profile.php');
?>