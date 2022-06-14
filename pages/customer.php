<?php
    declare(strict_types = 1);

    session_start();

    require_once('database/connection.db.php');
    
    require_once('templates/customer.class.php');

    require_once('templates/common.tpl.php');
    require_once('templates/customer.tpl.php');

    $db = getDatabaseConnection();


    $customer = Customer::getCustomer($db, 7);

?>