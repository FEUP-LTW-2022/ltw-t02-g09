<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../templates/forms.tpl.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();


    $email = $_POST['login_email'];
    $password = $_POST['login_pswd'];

    $user = User::getUserWithPassword($db, $email, $password);

    if($user->isOwner){
        $session->setOwner();
    } else {
        $session->setCustomer();
    }

    if(isset($user)){
        $session->setEmail($user->email);
        $session->setName($user->name());
        $session->addMessage('success', 'Login successful!');
        header('Location: ../index.php');
    } else {
        $session->addMessage('error', 'Wrong password!');
        header('Location: ../pages/login.php');
    }

?>
