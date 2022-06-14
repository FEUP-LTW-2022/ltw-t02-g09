<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if ($session->isLoggedIn()){
        $session->addMessage('error', 'You are already logged in');
    };

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../templates/forms.tpl.php');

    $db = getDatabaseConnection();


    if(isset($_POST['user-type'])){
        $isOwner = $_POST['user-type'];
    }

    if($isOwner === "Owner"){
        $isOwner = true;
    }else{
        $isOwner = false;
    }

    $email    = $_POST['email'];
    $password = $_POST['password'];

    User::createUser($db, $email, $password, $_POST['first-name'], $_POST['last-name'], $_POST['mobile'], $isOwner);

    $userLogged = User::getUserWithPassword($db, $email, $password);

    if($userLogged->isOwner){
        $session->setOwner();
    } else {
        $session->setCustomer();
    }
    
    if(isset($userLogged)){
        $session->setEmail($userLogged->email);
        $session->setName($userLogged->name());
        $session->addMessage('success', 'Login successful!');
        header('Location: ../index.php');
    } else {
        $session->addMessage('error', 'Wrong password!');
        $_SESSION['error'] = "Invalid username or password";
        header('Location: ../pages/login.php');
    }
?>