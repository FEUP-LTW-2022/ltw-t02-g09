<?php 
declare(strict_types=1);


class Dish{
    public int       $dishId;
    public ?string   $name;
    public ?float    $price;
    public string    $ingredients;
    public ?bool     $isVegan;
    public ?int      $categoryId; 
    public int       $restaurantId;
    public int       $imageId; 

    public function __construct(int $dishId, string $name, float $price, string $ingredients, bool $isVegan, int $categoryId, int $restaurantId, int $imageId){
        
        $this->dishId       = $dishId;
        $this->name         = $name;
        $this->price        = $price;
        $this->ingredients  = $ingredients;
        $this->isVegan      = $isVegan;
        $this->categoryId   = $categoryId;
        $this->restaurantId = $restaurantId;
        $this->imageId      = $imageId;
    }

    static function getDishes(PDO $db, int $count) : array {
        $stmt = $db->prepare('SELECT DishId, Name, Price, Ingredients, Vegan, CategoryId, RestaurantId, ImageId  FROM Dish LIMIT ?' );
        $stmt->execute(array($count));

        $dishes = array();

        while($dish = $stmt->fetch()){
            $dishes[] = new Dish(
                $dish['DishId'],
                $dish['Name'],
                $dish['Price'],
                $dish['Ingredients'],
                (bool) $dish['Vegan'],
                $dish['CategoryId'],
                $dish['RestaurantId'],
                $dish['ImageId']
            );
        }

        return $dishes;
    }

    static function getRestaurantDishes(PDO $db, int $restaurantId) : array {
        $stmt = $db->prepare('
        SELECT DishId, Name, Price, Ingredients, Vegan, CategoryId, RestaurantId, ImageId
        FROM Dish
        WHERE RestaurantId = ?');
        $stmt->execute(array($restaurantId));

        $dishes = array();

        while($dish = $stmt->fetch()){
            $dishes[] = new Dish(
                $dish['DishId'],
                $dish['Name'],
                $dish['Price'],
                $dish['Ingredients'],
                (bool) $dish['Vegan'],
                $dish['CategoryId'],
                $dish['RestaurantId'],
                $dish['ImageId']
            );
        }

        return $dishes;
    }

    static function getDish(PDO $db, int $dishId) : Dish {
        $stmt = $db->prepare('SELECT DishId, Name, Price, Ingredients, Vegan, RestaurantId, ImageId FROM Dish WHERE DishId = ?');
        $stmt->execute(array($dishId));

        $dish=$stmt->fetch();
        
        return new Dish(
            $dish['DishId'],
            $dish['Name'],
            $dish['Price'],
            $dish['Ingredients'],
            $dish['Vegan'],
            $dish['CategoryId'],
            $dish['RestaurantId'],
            $dish['ImageId']
        );
    }

    static function createDish(PDO $db, $name, $price, $ingredients, $vegan, $categoryId, $restaurantId, $imageId){
        $stmt = $db-> prepare('
        INSERT INTO Dish(Name, Price, Ingredients, Vegan, CategoryId, RestaurantId, ImageId) 
        VALUES(:Name, :Price, :Ingredients, :Vegan, :CategoryId, :RestaurantId, :ImageId)'
         );

        $stmt->execute([
            ':Name'         => $name,
            ':Price'        => $price,
            ':Ingredients'  => $ingredients,
            ':Vegan'        => (bool) $vegan,
            ':CategoryId'   => $categoryId, 
            ':RestaurantId' => $restaurantId,
            ':ImageId'      => $imageId
        ]); 
    }

    static function searchDish(PDO $db, string $search, int $restaurantId){
        $stmt = $db->prepare('
        SELECT Dish.DishId, Dish.Name, Dish.Price, Ingredients, Vegan, Category.Name as CategoryName, Dish.ImageId
        FROM Dish, Restaurant, Category 
        WHERE Dish.RestaurantId = Restaurant.RestaurantId
        AND Category.CategoryId = Dish.CategoryId
        AND Restaurant.RestaurantId = ?
        AND Dish.Name LIKE ?');


        $stmt->execute(array($restaurantId, $search.'%'));

        $dishes = $stmt->fetchAll();

        return $dishes;
    }

    public static function isFavoriteDish($customerId, $dishId) : bool
    {
        $db = getDatabaseConnection();
        $stmt = $db->prepare('SELECT * FROM FavoriteDish WHERE customerId = ? AND dishId = ?');
        $stmt->execute(array($customerId, $dishId));
        if ($stmt->fetch()) {
            return true;
        }
        return false;
    }


    public static function toggleFavoriteDish(PDO $db, int $customerId, int $dishId, bool $isChecked)
    {
        if (!$isChecked) {
            $stmt = $db->prepare('INSERT INTO FavoriteDish VALUES(?,?)');
            $stmt->execute(array($customerId, $dishId));
        }
        else{
            $stmt = $db->prepare('DELETE FROM FavoriteDish WHERE CustomerId = ? and DishId = ?');
            $stmt->execute(array($customerId, $dishId));
        }
    }

    static function getOrderDishes(PDO $db, int $orderId): array
    {
        $stmt = $db->prepare('
        SELECT Name, quantity 
        FROM OrderedDish, Dish 
        WHERE Dish.DishId = OrderedDish.DishId
        AND OrderId = ?');
        $stmt->execute(array($orderId));
        
        $dishes = $stmt->fetchAll();

        return $dishes;
    }

}   

?>