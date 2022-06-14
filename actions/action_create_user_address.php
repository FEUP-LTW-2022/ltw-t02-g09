<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if (!$session->isLoggedIn()) die(header('Location: /'));

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/address.class.php');
    require_once(__DIR__ . '/../templates/forms.tpl.php');

    $db = getDatabaseConnection();

    $user = User::getUser($db, $session->getEmail());
    $address = Address::createAddress($db, $_POST['add-one'], $_POST['add-two'],$_POST['add-city'], $_POST['add-country'], $_POST['add-pc']);

    $stmt = $db-> prepare('
        INSERT INTO UserAddress(UserId, AddressId) 
        VALUES(:UserId, :AddressId)'
    );

    $id = $db->lastInsertId();

    $stmt->execute([
        ':UserId'      => $session->getEmail(),
        ':AddressId'   => $id   
    ]);

    $user->setHaveAddress($db, true);

    header('Location: ../pages/profile.php');
?>