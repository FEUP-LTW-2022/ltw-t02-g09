<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/address.class.php');

  require_once(__DIR__ . '/../templates/forms.tpl.php');

  $db = getDatabaseConnection();

  $address = Address::getAddressWithEmail($db, $session->getEmail());

  if ($address){
    $address->addressOne =  $_POST['add-one'];
    $address->addressTwo =  $_POST['add-two'];
    $address->city       =  $_POST['add-city'];
    $address->country    =  $_POST['add-country'];
    $address->postalcode =  $_POST['add-pc'];

    $address->save($db);
  }

  header('Location: ../pages/profile.php');
?>