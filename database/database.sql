/*******************************************************************************
   Create Tables
********************************************************************************/


/* Creating the 3 types of user, (Owner, Customer and Driver) */



DROP TABLE IF EXISTS _Order;
CREATE TABLE _Order (
    OrderId      INTEGER       PRIMARY KEY AUTOINCREMENT,
    CustomerId                 REFERENCES Customer ON DELETE SET NULL
                                                   ON UPDATE CASCADE,
    RestaurantId               REFERENCES Restaurant ON DELETE SET NULL
                                                     ON UPDATE CASCADE,
    TotalPrice   FLOAT,
    TimeToArrive FLOAT,
    StatusOrder  VARCHAR (20)  CONSTRAINT statusFood CHECK (StatusOrder IN ('Received Order', 'Preparing', 'Delivering', 'Delivered') ),
    Price        FLOAT,
    IsPaid       BOOLEAN,
    Note         VARCHAR (500) 
);




DROP TABLE IF EXISTS Address;
CREATE TABLE Address (
    AddressId      INTEGER      PRIMARY KEY AUTOINCREMENT,
    AddressLineOne VARCHAR (50) NOT NULL,
    AddressLineTwo VARCHAR (50),
    City           VARCHAR (30) NOT NULL,
    Country        VARCHAR (30) CONSTRAINT contryNotFilled NOT NULL,
    postalcode     VARCHAR (30) 
);



DROP TABLE IF EXISTS Category;
CREATE TABLE Category (
    CategoryId INTEGER PRIMARY KEY,
    name       TEXT    NOT NULL
);



DROP TABLE IF EXISTS Customer;
CREATE TABLE Customer (
    CustomerId REFERENCES User ON DELETE SET NULL
                               ON UPDATE CASCADE,
    PRIMARY KEY (
        CustomerId
    )
);



DROP TABLE IF EXISTS Dish;
CREATE TABLE Dish (
    DishId      INTEGER PRIMARY KEY AUTOINCREMENT,
    MenuId              REFERENCES Menu ON DELETE SET NULL
                                        ON UPDATE CASCADE,
    CategoryId          REFERENCES Category ON DELETE SET NULL
                                            ON UPDATE CASCADE,
    Name        VARCHAR,
    Price       REAL,
    Ingredients TEXT,
    Vegan       BOOLEAN
);



DROP TABLE IF EXISTS Driver;
CREATE TABLE Driver (
    IdDriver      REFERENCES User ON DELETE SET NULL
                                  ON UPDATE CASCADE,
    IdRestaurant  REFERENCES Restaurant ON DELETE SET NULL
                                        ON UPDATE CASCADE,
    PRIMARY KEY (
        IdDriver
    )
);


DROP TABLE IF EXISTS Favorite;
CREATE TABLE Favorite (
    CustomerId   INTEGER REFERENCES Customer (CustomerId) ON DELETE SET NULL
                                                          ON UPDATE CASCADE
                         PRIMARY KEY,
    RestaurantId INTEGER REFERENCES Restaurant (RestaurantId) ON DELETE SET NULL
                                                              ON UPDATE CASCADE,
    DishId       INTEGER REFERENCES Dish (MenuId) ON DELETE SET NULL
                                                  ON UPDATE CASCADE
);




DROP TABLE IF EXISTS Menu;
CREATE TABLE Menu (
    MenuId       INTEGER      PRIMARY KEY AUTOINCREMENT,
    RestaurantId              REFERENCES Restaurant ON DELETE SET NULL
                                                    ON UPDATE CASCADE,
    MenuName     VARCHAR (75) 
);



DROP TABLE IF EXISTS Restaurant;
CREATE TABLE Restaurant (
    RestaurantId   INTEGER      PRIMARY KEY AUTOINCREMENT,
    RestaurantName VARCHAR (50),
    Review         REAL         CONSTRAINT outOfReview CHECK (review > 0 AND 
                                                              review <= 5),
    Price          VARCHAR (3) 
);


DROP TABLE IF EXISTS RestaurantAddress;
CREATE TABLE RestaurantAddress (
    RestaurantId INTEGER REFERENCES Restaurant (RestaurantId) ON DELETE SET NULL
                                                              ON UPDATE CASCADE,
    AddressId    INTEGER REFERENCES Address (AddressId) ON DELETE SET NULL
                                                        ON UPDATE CASCADE,
    PRIMARY KEY (
        RestaurantId,
        AddressId
    )
);



DROP TABLE IF EXISTS RestaurantOwner;
CREATE TABLE RestaurantOwner (
    OwnerId       REFERENCES User ON DELETE SET NULL
                                  ON UPDATE CASCADE,
    RestarauntId  REFERENCES Restaurant ON DELETE SET NULL
                                        ON UPDATE CASCADE,
    PRIMARY KEY (
        RestarauntId
    )
);



DROP TABLE IF EXISTS ReviewDish;
CREATE TABLE ReviewDish (
    ReviewId   INTEGER       PRIMARY KEY AUTOINCREMENT,
    CustomerId               REFERENCES Customer ON DELETE SET NULL
                                                 ON UPDATE CASCADE,
    DishId                   REFERENCES Dish ON DELETE SET NULL
                                             ON UPDATE CASCADE,
    Comment    VARCHAR (500),
    Stars      VARCHAR (5) 
);


DROP TABLE IF EXISTS ReviewRestaurant;
CREATE TABLE ReviewRestaurant (
    ReviewId     INTEGER       PRIMARY KEY AUTOINCREMENT,
    CustomerId                 REFERENCES Customer ON DELETE SET NULL
                                                   ON UPDATE CASCADE,
    RestaurantId               REFERENCES Restaurant ON DELETE SET NULL
                                                     ON UPDATE CASCADE,
    Comment      VARCHAR (500),
    Stars        VARCHAR (5) 
);



DROP TABLE IF EXISTS User;
CREATE TABLE User (
    Email     VARCHAR (50) PRIMARY KEY,
    Password  VARCHAR (32) NOT NULL,
    FirstName VARCHAR (30) NOT NULL,
    LastName  VARCHAR (30) NOT NULL,
    Mobile    VARCHAR (15) NOT NULL
                           UNIQUE,
    IsOwner   BOOLEAN
);

DROP TABLE IF EXISTS UserAddress;
CREATE TABLE UserAddress (
    UserId    VARCHAR (50) REFERENCES User (Email) ON DELETE SET NULL
                                                   ON UPDATE CASCADE,
    AddressId INTEGER      REFERENCES Address (AddressId) ON DELETE SET NULL
                                                          ON UPDATE CASCADE,
    PRIMARY KEY (
        UserId,
        AddressId
    )
);

DROP TABLE IF EXISTS RestaurantCategory;
CREATE TABLE RestaurantCategory (
    CategoryId   INTEGER REFERENCES Category (CategoryId) ON DELETE SET NULL
                                                          ON UPDATE CASCADE,
    RestaurantId INTEGER REFERENCES Restaurant (RestaurantId) ON DELETE SET NULL
                                                              ON UPDATE CASCADE,
    PRIMARY KEY (
        CategoryId,
        RestaurantId
    )
);

