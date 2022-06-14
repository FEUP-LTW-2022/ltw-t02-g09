<?php

class User{
    public string $email;
    public string $password;
    public string $firstName;
    public string $lastName;
    public string $mobile;
    public bool   $isOwner;
    public bool   $haveAddress;
    
    public function __construct(string $email, string $password, string $firstName, string $lastName, string $mobile, bool $isOwner){
        
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mobile = $mobile;
        $this->isOwner = $isOwner;
    }

    function getAddressStatus(PDO $db) : bool{
        $stmt = $db->prepare('
        SELECT HaveAddress
        FROM User
        WHERE Email = ?
        ');

        $stmt->execute(array($this->email));

        if($isAddress = $stmt->fetch()){
            return $isAddress['HaveAddress'];
        }
    }

    function setHaveAddress(PDO $db, bool $haveAddress){
        $stmt = $db->prepare('UPDATE User SET HaveAddress = ? WHERE Email = ?');

        $stmt->execute(array($haveAddress, $this->email));
    }

    function name(){
        return $this->firstName . ' ' . $this->lastName;
    }

    function save(PDO $db){
        $stmt = $db->prepare('UPDATE User SET Password = ?, FirstName = ?, LastName = ?, Mobile = ? WHERE Email = ?');

        $stmt->execute(array($this->password, $this->firstName, $this->lastName, $this->mobile, $this->email));
    }

    
    static function GetUserWithPassword(PDO $db, string $email, string $password) : ? User{
        $stmt = $db->prepare('
        SELECT Email, Password, FirstName, LastName, Mobile, isOwner
        FROM User
        WHERE Email = ? AND Password = ?
        ');

        $stmt->execute(array($email, $password));

        if($user = $stmt->fetch()){
            return new User(
                $user['Email'],
                $user['Password'],
                $user['FirstName'],
                $user['LastName'],
                $user['Mobile'],
                $user['IsOwner']
            );
        } else return null;
    }


    static function getUser(PDO $db, string $email) : User {
        $stmt = $db->prepare('
            SELECT Email, Password, FirstName, LastName, Mobile, isOwner
            FROM User
            WHERE Email = ?
        ');
  
        $stmt->execute(array($email));
        
        if($user = $stmt->fetch()){
            return new User(
                $user['Email'],
                $user['Password'],
                $user['FirstName'],
                $user['LastName'],
                $user['Mobile'],
                $user['IsOwner']
            );
        } else return null;
      }

    static function createUser(PDO $db, $email, $password, $firstName, $lastName, $mobile, $isOwner){
        $stmt = $db-> prepare('
        INSERT INTO User(Email, Password, FirstName, LastName, Mobile, IsOwner) 
        VALUES(:Email, :Password, :FirstName, :LastName, :Mobile, :IsOwner)'
         );

        if($stmt->execute([
            ':Email'     => $email,
            ':Password'  => $password,
            ':FirstName' => $firstName,
            ':LastName'  => $lastName,
            ':Mobile'    => $mobile,
            ':IsOwner'   => (bool) $isOwner
        ])){
        echo "Succesful added record";
        } else{
            echo "Error while adding record";
        }   
    }

    static function addUserAddress(PDO $db, $email, $addressOne, $addressTwo, $city, $country, $postalcode){
        $stmt = $db-> prepare('
            INSERT INTO Address(AddressLineOne, AddressLineTwo, City, Country, Postalcode)
            VALUES(:AddressLineOne, :AddressLineTwo, :City, :Country, :Postalcode)
        ');

        if($stmt->execute([
            ':AddressLineOne' => $addressOne,
            ':AddressLineTwo' => $addressTwo,
            ':City'           => $city,
            ':country'        => $country,
            ':postalcode'     => $postalcode
        ])){
            echo "New Address";
        } else {
            echo "error adding new address";
        }

        echo $db->lastInsertId();

        $addressId = $db->sqlite_last_insert_rowid();

        $stmt = $db-> prepare('
            INSERT INTO UserAddress(UserId, AddressId)
            VALUES(:UserId, :AddressId)
        ');
        
        $stmt->execute([
            ':UserId'    => $email,
            ':AddressId' => $addressId
        ]);    

    }
        

}

?>