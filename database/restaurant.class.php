<?php
declare(strict_types=1);

class Restaurant {
    public ?int    $restaurantId;
    public ?string $restaurantName;
    public ?string $price;
    public  int    $categoryId;
    public ?int    $imageId;

    public function __construct(int $restaurantId, string $restaurantName, 
                                        string $price, int $categoryId, int $imageId){
        
        $this->restaurantId   = $restaurantId;
        $this->restaurantName = $restaurantName;
        $this->price          = $price;
        $this->categoryId     = $categoryId;
        $this->imageId        = $imageId;
    }
    
    static function getRestaurants(PDO $db, int $count) : array{
        $stmt = $db->prepare('SELECT RestaurantId, RestaurantName, Price, CategoryId, ImageId FROM Restaurant LIMIT ?');
        $stmt->execute(array($count));

        $restaurants = array();
        while ($restaurant = $stmt->fetch()) {
            $restaurants[] = new Restaurant(
                $restaurant['RestaurantId'],
                $restaurant['RestaurantName'],
                $restaurant['Price'],
                $restaurant['CategoryId'],
                $restaurant['ImageId']
            );
        }

        return $restaurants;
    }

    function save($db){
        $stmt = $db->prepare('
            UPDATE Restaurant SET restaurantName = ?, Price = ?, CategoryId = ?, ImageId = ? 
            WHERE RestaurantId = ?');

        $stmt->execute(array($this->restaurantName, $this->price, $this->categoryId, $this->imageId, $this->restaurantId));
    }

    static function getRestaurant(PDO $db, int $id) : Restaurant {
        $stmt = $db->prepare('SELECT RestaurantId, RestaurantName, Price, CategoryId, ImageId FROM Restaurant WHERE RestaurantId = ?');
        $stmt->execute(array($id));

        $restaurant = $stmt->fetch();

        return new Restaurant(
            $restaurant['RestaurantId'],
            $restaurant['RestaurantName'],
            $restaurant['Price'],
            $restaurant['CategoryId'],
            $restaurant['ImageId']
        );
    }


    static function getOwnerRestaurant(PDO $db, string $id) : Restaurant {
        $stmt = $db->prepare('SELECT Restaurant.RestaurantId, RestaurantName, Price, CategoryId, ImageId 
        FROM Restaurant, RestaurantOwner 
        WHERE Restaurant.RestaurantId = RestaurantOwner.RestaurantId AND OwnerId = ?');
        $stmt->execute(array($id));

        $restaurant = $stmt->fetch();

        return new Restaurant(
            $restaurant['RestaurantId'],
            $restaurant['RestaurantName'],
            $restaurant['Price'],
            $restaurant['CategoryId'],
            $restaurant['ImageId']
        );
    }


    static function getRestaurantOwnerId(PDO $db, int $id) : string {
        $stmt = $db->prepare('SELECT OwnerId 
        FROM RestaurantOwner
        WHERE RestaurantId = ?');
        $stmt->execute(array($id));

        $ownerId = $stmt->fetch();

        return $ownerId['OwnerId'];
    }

    static function getOwnerRestaurants(PDO $db, string $id) : array {
        $stmt = $db->prepare('
        SELECT Restaurant.RestaurantId, RestaurantName, Price, CategoryId, ImageId
        FROM Restaurant, RestaurantOwner 
        WHERE Restaurant.RestaurantId = RestaurantOwner.RestaurantId 
        AND OwnerId = ?');
        $stmt->execute(array($id));

        $restaurants = array();
        while ($restaurant = $stmt->fetch()) {
            $restaurants[] = new Restaurant(
                $restaurant['RestaurantId'],
                $restaurant['RestaurantName'],
                $restaurant['Price'],
                $restaurant['CategoryId'],
                $restaurant['ImageId']
            );
        }

        return $restaurants;
    }

    public static function searchRestaurantsByName(PDO $db, string $search): array
    {
        $stmt = $db->prepare('SELECT RestaurantId, RestaurantName, Price, name, Restaurant.ImageId 
        FROM Restaurant JOIN Category ON Category.categoryId = Restaurant.categoryId
        WHERE RestaurantName LIKE ?');
        $stmt->execute(array($search.'%'));

        $restaurants = $stmt->fetchAll();

        return $restaurants;
    }

    //redo this function
    public static function searchRestaurantsByScore(PDO $db, float $score): array
    {
        $stmt = $db->prepare('SELECT RestaurantId, RestaurantName, Review, Price, CategoryId, (SELECT round(avg(rating), 3) as average FROM Review
        WHERE restaurantId = Restaurant.restaurantId) as avg FROM Restaurant  ORDER BY (avg) desc LIMIT ?');
        $stmt->execute(array($count));

        $restaurants = array();
        while ($restaurant = $stmt->fetch()) {
            if (floatval($restaurant['avg']) < $score) {
                break;
            }
            $restaurants[] = new Restaurant(
                $restaurant['RestaurantId'],
                $restaurant['RestaurantName'],
                $restaurant['Price'],
                $restaurant['CategoryId']
            );
            end($restaurants)->avg = $restaurant['avg'];
        }

        return $restaurants;
    }

    public static function searchRestaurantsByCategory(PDO $db, string $search): array
    {
        $stmt = $db->prepare('
        SELECT RestaurantId, RestaurantName, Price, name, Restaurant.ImageId
        FROM Restaurant JOIN Category ON Category.categoryId = Restaurant.categoryId
        AND name LIKE ?');
        $stmt->execute(array($search.'%'));

        $restaurants = $stmt->fetchAll();

        return $restaurants;
    }




    static function createRestaurant(PDO $db, User $user, string $restaurantName, string $price, int $categoryId, int $imageId){
        
        $stmt = $db-> prepare('
        INSERT INTO Restaurant(RestaurantName, Price, CategoryId, ImageId) 
        VALUES(:RestaurantName, :Price, :CategoryId, :ImageId)'
         );

        if($stmt->execute([
            ':RestaurantName' => $restaurantName,
            ':Price'          => $price,
            ':CategoryId'     => $categoryId,
            ':ImageId'        => $imageId
        ])){
        echo "Succesful added record";
        } else{
            echo "Error while adding record";
        }

        $stmt = $db-> prepare('
        INSERT INTO RestaurantOwner(OwnerId, RestaurantId) 
        VALUES(:OwnerId, :RestaurantId)'
         );

        $id = $db->lastInsertId();

         if($stmt->execute([
            ':OwnerId'       => $user->email,
            ':RestaurantId'  => $id,
        ])){
        echo "Succesful added record";
        } else{
            echo "Error while adding record";
        }

    }

    public static function toggleFavoriteRestaurant(PDO $db, string $customerId, int $restaurantId, bool $isChecked)
    {
        if (!$isChecked) {
            $stmt = $db->prepare('INSERT INTO FavoriteRestaurant VALUES(?,?)');
            $stmt->execute(array($customerId, $restaurantId));
        }
        else{
            $stmt = $db->prepare('DELETE FROM FavoriteRestaurant WHERE restaurantId = ? and customerId = ?');
            $stmt->execute(array($restaurantId, $customerId));
        }
    }

    public static function isFavoriteRestaurantDB($customerId, $restaurantId): bool
    {
        $db = getDatabaseConnection();
        $stmt = $db->prepare('SELECT * FROM FavoriteRestaurant WHERE customerId = ? AND restaurantId = ?');
        $stmt->execute(array($customerId, $restaurantId));
        if ($stmt->fetch()) {
            return true;
        }
        return false;
    }

    public static function getFavoriteRestaurants(PDO $db, string $customerId) : array{
        $stmt = $db->prepare('SELECT Restaurant.RestaurantId, RestaurantName, Price, CategoryId, ImageId FROM Restaurant, FavoriteRestaurant  
        WHERE Restaurant.RestaurantId = FavoriteRestaurant.RestaurantId
        AND CustomerId = ?');
        $stmt->execute(array($customerId));

        $restaurants = array();
        while ($restaurant = $stmt->fetch()) {
            $restaurants[] = new Restaurant(
                $restaurant['RestaurantId'],
                $restaurant['RestaurantName'],
                $restaurant['Price'],
                $restaurant['CategoryId'],
                $restaurant['ImageId']
            );
        }

        return $restaurants;

    }


}

?>