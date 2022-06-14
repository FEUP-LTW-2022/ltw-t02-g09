<?php
declare(strict_types=1);

class Menu
{
    public int $id;
    public int $restaurantId;
    public string $menuName;

    public function __construct(int $id, int $restaurantId, string $menuName)
    {
        $this->id           = $id;
        $this->restaurantId = $restaurantId;
        $this->menuName     = $menuName;
    }

    static function getMenus(PDO $db, int $count): array
    {
        $stmt = $db->prepare('SELECT MenuId, RestaurantId, MenuName FROM Menu LIMIT ?');
        $stmt->execute(array($count));

        $menus = array();
        while ($menu = $stmt->fetch()) {
            $menus[] = new Menu(
                $menu['MenuId'],
                $menu['RestaurantId'],
                $menu['MenuName']
            );
        }

        return $menus;
    }

    /**
     * @param PDO $db
     * @param int $id
     * @return Menu with menuId 'id'
     */
    static function getMenu(PDO $db, int $id): Menu
    {
        $stmt = $db->prepare('SELECT MenuId, RestaurantId, MenuName FROM Menu WHERE MenuId = ?');
        $stmt->execute(array($id));

        $menu = $stmt->fetch();

        return new Menu(
            $menu['MenuId'],
            $menu['RestaurantId'],
            $menu['MenuName']
        );
    }

    static function getMenuIdFromRestaurant(PDO $db, int $restaurantId): int
    {
        $stmt = $db->prepare('SELECT MenuId, RestaurantId, MenuName FROM Menu JOIN Restaurant USING(RestaurantId) WHERE RestaurantId = ?');
        $stmt->execute(array($restaurantId));

        $menu = $stmt->fetch();
        if (empty($menu)) return -1;
        return $menu['MenuId'];
    }


    static function getRestaurantMenu(PDO $db, int $restaurantId): Menu
    {
        $stmt = $db->prepare('
        SELECT MenuId, RestaurantId, MenuName FROM Restaurant JOIN Menu USING (RestaurantId) 
        WHERE RestaurantId = ?
      ');
        $stmt->execute(array($restaurantId));
        $menu = $stmt->fetch();
        return new Menu(
            $menu['MenuId'],
            $menu['RestaurantId'],
            $menu['MenuName']
        );
    }


    public static function createMenu(PDO $db, int $restaurantId): int
    {
        $stmt = $db->prepare('INSERT INTO Menu (restaurantId) Values(?)');
        $stmt->execute(array($restaurantId));

        $stmt = $db->prepare('SELECT restaurantId, MenuId, MenuName FROM Menu WHERE MenuId = (SELECT max(MenuId) FROM Menu)');
        $stmt->execute();
        $menu = $stmt->fetch();

        return intval($menu['MenuId']);
    }
}


?>