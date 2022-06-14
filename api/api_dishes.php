<?php 
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once('../database/connection.db.php');
require_once('../database/dish.class.php');

$db = getDatabaseConnection();

$restaurantId = $_SESSION['id'];

$dishes = Dish::searchDish($db, $_GET['search'], $restaurantId);

echo json_encode($dishes);

?>