<?php 
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once('../database/connection.db.php');
require_once('../database/restaurant.class.php');

$db = getDatabaseConnection();

$mode = $_GET['mode'];


switch($mode){
    case 'category':
        $restaurants = Restaurant::searchRestaurantsByCategory($db, $_GET['search']);
        break;
    default:
        if($_GET['search'] == 'All'){
            $restaurants = Restaurant::getRestaurants($db, 100);
            break;
        }
        $restaurants = Restaurant::searchRestaurantsByName($db, $_GET['search']);
        break;
}
    
echo json_encode($restaurants);
?>