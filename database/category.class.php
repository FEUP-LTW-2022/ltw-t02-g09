<?php
declare(strict_types=1);

class Category {
    public int    $categoryId;
    public string $name;
    public int    $imageId;

    public function __construct(int $categoryId, string $name, int $imageId){
        
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->imageId = $imageId;

    }

    static function getCategories(PDO $db, int $count) : array{
        $stmt = $db->prepare('SELECT CategoryId, name, ImageId FROM Category LIMIT ?');
        $stmt->execute(array($count));

        $categories = array();
        while ($category = $stmt->fetch()) {
            $categories[] = new Category(
                $category['CategoryId'],
                $category['name'],
                $category['ImageId']
            );
        }

        return $categories;
    }

    static function getCategoriesWithDishes(PDO $db, int $count) : array{
        $stmt = $db->prepare('SELECT CategoryId, name, ImageId 
        FROM Category JOIN Dish
        LIMIT ?');
        $stmt->execute(array($count));

        $categories = array();
        while ($category = $stmt->fetch()) {
            $categories[] = new Category(
                $category['CategoryId'],
                $category['name'],
                $category['ImageId']
            );
        }

        return $categories;
    }

    function save($db){
        $stmt = $db->prepare('
            UPDATE Category SET name = ?
            WHERE CategoryId = ?');

        $stmt->execute(array($this->RestauratName, $this->id));
    }

    static function getCategory(PDO $db, int $id) : Category {
        $stmt = $db->prepare('SELECT CategoryId, name, ImageId FROM Category WHERE CategoryId = ?');
        $stmt->execute(array($id));

        $category = $stmt->fetch();

        return new Category(
            $category['CategoryId'],
            $category['name'],
            $category['ImageId']
        );
    }

    static function getCategoryByName(PDO $db, string $name) : Category {
        $stmt = $db->prepare('SELECT CategoryId, name, ImageId FROM Category WHERE name = ?');
        $stmt->execute(array($name));

        $category = $stmt->fetch();

        return new Category(
            $category['CategoryId'],
            $category['name'],
            $category['ImageId']
        );
    }

    static function getRestaurantCategory(PDO $db, int $id) : Category {
        $stmt = $db->prepare('SELECT Category.CategoryId, name, Category.ImageId
        FROM Category, RestaurantCategory 
        WHERE Category.CategoryId = RestaurantCategory.CategoryId AND RestaurantId = ?');
        $stmt->execute(array($id));

        $category = $stmt->fetch();

        return new Category(
            $category['CategoryId'],
            $category['name'],
            $category['ImageId']
        );
    }

    static function createCategory(PDO $db, $name, $imageId){
        
        $stmt = $db-> prepare('
        INSERT INTO Category(Name, ImageId) 
        VALUES(:Name, :ImageId)'
         );

        $stmt->execute([
            ':Name'            => $name,
            ':ImageId'         => $imageId
        ]); 
    }
}

?>