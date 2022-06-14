<?php
declare(strict_types=1);

class Address {
    public int    $id;
    public string $addressOne;
    public string $addressTwo;
    public ?string $city;
    public string $country;
    public string $postalcode;

    public function __construct(int $id, string $addressOne, string $addressTwo, string $city, string $country, string $postalcode){
        
        $this->id = $id;
        $this->addressOne = $addressOne;
        $this->addressTwo = $addressTwo;
        $this->city       = $city;
        $this->country    = $country;
        $this->postalcode = $postalcode;
    }


    static function getAddress(PDO $db, string $email) : Address {
        $stmt = $db->prepare('SELECT AddressId, AddressLineOne, AddressLineTwo, City, Country, postalcode FROM Address WHERE AddressId = ?');
        $stmt->execute(array($id));

        $address = $stmt->fetch();

        return new Address(
            $address['AddressId'],
            $address['AddressLineOne'],
            $address['AddressLineTwo'],
            $address['City'],
            $address['Country'],
            $address['postalcode']
        );
    }


    function save(PDO $db){
        $stmt = $db->prepare('UPDATE Address SET AddressLineOne = ?, AddressLineTwo = ?, City = ?, Country = ?, postalcode = ? WHERE AddressId = ?');

        $stmt->execute(array($this->addressOne, $this->addressTwo, $this->city, $this->country, $this->postalcode, $this->id));
    }

    static function createAddress(PDO $db,string $addressOne, string $addressTwo, string $city, string $country, string $postalcode){
        $stmt = $db-> prepare('
        INSERT INTO Address(AddressLineOne, AddressLineTwo, City, Country, Postalcode) 
        VALUES(:AddressLineOne, :AddressLineTwo, :City, :Country, :Postalcode)'
         );

        if($stmt->execute([
            ':AddressLineOne'   => $addressOne,
            ':AddressLineTwo'   => $addressTwo,
            ':City'             => $city,
            ':Country'          => $country,
            ':Postalcode'       => $postalcode
        ])){
        echo "Succesful added record";
        } else{
            echo "Error while adding record";
        }   
    }

    static function getAddressWithEmail(PDO $db, string $email) {
        $stmt = $db->prepare('
        SELECT Address.AddressId, AddressLineOne, AddressLineTwo, City, Country, postalcode 
        FROM Address, UserAddress  
        WHERE Address.AddressId = UserAddress.AddressId AND UserAddress.UserId = ?
        ');
  
        $stmt->execute(array($email));
        
        if($address = $stmt->fetch()){
            return new Address(
                $address['AddressId'],
                $address['AddressLineOne'],
                $address['AddressLineTwo'],
                $address['City'],
                $address['Country'],
                $address['postalcode']
            );
        } else return null;
    }  

    static function getAddressWithResId(PDO $db, int $id) {
        
        $stmt = $db->prepare('
        SELECT Address.AddressId, AddressLineOne, AddressLineTwo, City, Country, postalcode 
        FROM Address, RestaurantAddress  
        WHERE Address.AddressId = RestaurantAddress.AddressId 
        AND RestaurantAddress.RestaurantId = ?
        ');
  
        $stmt->execute(array($id));
        
        if($address = $stmt->fetch()){
            return new Address(
                $address['AddressId'],
                $address['AddressLineOne'],
                $address['AddressLineTwo'],
                $address['City'],
                $address['Country'],
                $address['postalcode']
            );
        } else return null;
    }
    
}
?>