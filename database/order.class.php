<?php 

class Order
{
    public int      $orderId;
    public string   $customerId;
    public int      $restaurantId;
    public float    $price;
    public string   $datetime;
    public string   $status;
    public string   $note;
    public ?int     $customer_addressId;  


    public function __construct(int $orderId, string $customerId, int $restaurantId, float $price, string $datetime,  string $status, string $note, int $customer_addressId)
    {
        $this->orderId = $orderId;
        $this->customerId = $customerId;
        $this->restaurantId = $restaurantId;
        $this->price = $price;
        $this->datetime = $datetime;
        $this->status = $status;
        $this->note = $note;
        $this->customer_addressId = $customer_addressId;
    }


    static function createOrder(PDO $db, int $restaurantId, string $customerId, float $price, string $note, int $customer_addressId)
    {
    
        $date = new DateTime('now');
        $stmt = $db->prepare('
        INSERT INTO _Order(CustomerId, RestaurantId, TotalPrice, DateTime, Status, Note, AddressId) 
        VALUES(:CustomerId, :RestaurantId, :TotalPrice, :DateTime, :Status, :Note, :AddressId)');

    
        if($stmt->execute([
            ':CustomerId'       => $customerId,
            ':RestaurantId'     => $restaurantId,
            ':TotalPrice'       => $price,
            ':DateTime'         => (string) $date->format('d-M-Y H:i:s'),
            ':Status'           => "Received",
            ':Note'             => $note,   
            ':AddressId'        => $customer_addressId
        ]));
    }

    public static function getOrderWithCustomerId(PDO $db, string $id)
    {
        $stmt = $db->prepare('
        SELECT OrderId, CustomerId, RestaurantId, TotalPrice, DateTime, Status, Note, AddressId
        FROM _Order
        WHERE CustomerId = ?');

        $stmt->execute(array($id));
        
        $order = $stmt->fetch();

        if($order == null) return false;

        return new Order(
            $order['OrderId'],
            $order['CustomerId'],
            $order['RestaurantId'],
            $order['TotalPrice'],
            $order['DateTime'],
            $order['Status'],
            $order['Note'],
            $order['AddressId']
        );
    }

    function save(PDO $db){
        $stmt = $db->prepare('UPDATE _Order SET CustomerId = ?, RestaurantId = ?, TotalPrice = ?, DateTime = ?, Status = ?, Note = ?, AddressId = ? WHERE OrderId = ?');

        $stmt->execute(array($this->customerId, $this->restaurantId, $this->price, $this->datetime, $this->status, $this->note, $this->customer_addressId, $this->orderId));
    }



    static function getRestaurantOrders(PDO $db, int $restaurantId): array
    {
        $stmt = $db->prepare('
        SELECT OrderId, CustomerId, TotalPrice, DateTime, Status, Note, FirstName, LastName, AddressLineOne, AddressLineTwo, City, Country, postalcode
        FROM _Order, Address, User  
        WHERE _Order.AddressId = Address.AddressId
        AND User.Email = CustomerId
        AND restaurantId = ?');
        $stmt->execute(array($restaurantId));

        $orders = $stmt->fetchAll();

        return $orders;

    }


    public static function getOrderWithId(PDO $db, int $id): Order
    {
        $stmt = $db->prepare('
        SELECT OrderId, CustomerId, RestaurantId, TotalPrice, DateTime, Status, Note, AddressId
        FROM _Order
        WHERE OrderId = ?');

        $stmt->execute(array($id));
        
        $order = $stmt->fetch();

        return new Order(
            $order['OrderId'],
            $order['CustomerId'],
            $order['RestaurantId'],
            $order['TotalPrice'],
            $order['DateTime'],
            $order['Status'],
            $order['Note'],
            $order['AddressId']
        );
    }

    public static function deleteOrderWithId(PDO $db, int $id)
    {
        $stmt = $db->prepare('
        DELETE
        FROM _Order
        WHERE OrderId = ?');

        $stmt->execute(array($id));
    }



}

class OrderedDish
{
    public int $orderId;
    public int $dishId;
    public int $quantity;

    /**
     * @param int $orderId
     * @param int $dishId
     * @param int $quantity
     */
    public function __construct(int $orderId, int $dishId, int $quantity)
    {
        $this->orderId  = $orderId;
        $this->dishId   = $dishId;
        $this->quantity = $quantity;
    }



    static function addDishToOrder(PDO $db, $orderId, $dishId, $quantity)
    {
        $stmt = $db->prepare('
        INSERT INTO OrderedDish(OrderId, DishId, quantity) 
        VALUES(:OrderId, :DishId, :quantity)');

        $stmt->execute([
            ':OrderId'         => $orderId,
            ':DishId'          => $dishId,
            ':quantity'        => $quantity
        ]); 
    }

}

?>