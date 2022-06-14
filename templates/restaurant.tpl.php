<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');

  require_once(__DIR__ . '/../database/connection.db.php');

  require_once(__DIR__ . '/../database/restaurant.class.php');
  require_once(__DIR__ . '/../database/category.class.php');
  require_once(__DIR__ . '/../database/menu.class.php');
  require_once(__DIR__ . '/../database/dish.class.php');
?>

<?php function drawRestaurants(array $restaurants, array $categories) { ?>
  <header class="main_rest">
    <div class="searchRestaurant">
      <p id="title-main-page"> What you need is here.</p>
      <p id="second-title-main-page">Ask and receive wherever you are.</p>
      <form class="mainsearch" onsubmit="event.preventDefault();" role="search">
        <input id="search-restaurants" type="search" placeholder="Search..." autofocus required />  
      </form>
    </div>
  </header>
  <h2 id="title-ctg">Categories</h2>
  <div class="categories-select">
    <form method="get">
    <select name="rest-category" id="search-restaurant-by-category">
    <option value="" disabled selected>Select Your Desired Category</option>
        <?php foreach($categories as $category){ ?>
          <option value="<?=$category->name?>"><?=$category->name?></option>
        <?php } ?>
    </select>
    </form>
  </div>  
  <h2 id="title-rest">Restaurants</h2>
  <section id="restaurants">
    <?php foreach($restaurants as $restaurant) { ?> 
      <div class="rest_items">
        <a href="/../pages/restaurant.php?id=<?=$restaurant->restaurantId?>">
        <img src="../images/restaurants/thumbs_small/<?=$restaurant->imageId?>.jpg" alt="Screen 2" <?=$restaurant->restaurantId?>">
        </a>
        <span class="caption">
          <a href="/../pages/restaurant.php?id=<?=$restaurant->restaurantId?>">
              <?=$restaurant->restaurantName?>
          </a>
          <?php $db = getDatabaseConnection(); ?>
          <?php $category = Category::getCategory($db, $restaurant->categoryId)?>
          <p><?=$category->name?></p>
        </span>
      </div>
    <?php } ?>
  </section>
<?php } ?>

<?php function drawRestaurant(Restaurant $restaurant, array $categories, array $dishes, Address $address, bool $isOwner, Session $session) { ?>

  <?php if($isOwner) {?>
    <div> 
      <a href="../pages/edit_restaurant.php">edit restaurant</a>
    </div>

    <div>
      <a href="../pages/add_dish.php">add dish</a>
    </div>
  <?php } ?>



  <header class="restBanner">
    <img src="../images/restaurants/originals/<?=$restaurant->imageId?>.jpg" alt="banner" <?=$restaurant->restaurantId?>">
  </header>
  <div class="rest_design">
    <img src="../images/restaurants/thumbs_small/<?=$restaurant->imageId?>.jpg" alt="Screen 1" <?=$restaurant->restaurantId?>">
    <h1 class="RestaurantName"><?=$restaurant->restaurantName?></h1>
    
  <?php if ($session->isLoggedIn())
    drawMarkRestaurantAsFavorite(Restaurant::isFavoriteRestaurantDB($session->getEmail(), $restaurant->restaurantId));
    ?>
  </div>

  <div>
                <?= $address->addressOne ?>
            </div>

            <div>
                <?= $address->addressTwo ?>
            </div>

            <div>
                <?= $address->city?>
            </div>

            <div>
                <?= $address->country?>
            </div>

            <div>
                <?= $address->postalcode?>
          </div>
  </div>

  <section id="restaurants">
  <input id="search-dishes" type="search" placeholder="Search..." autofocus required/>
  <div class="RestaurantCategory">
    <?php foreach($categories as $category) { ?>
      <?php drawCategory($category, $dishes); ?>
      <?php } ?>
  </div>
  </section>
<?php } ?>

<?php function drawCategory(Category $category, array $dishes){ ?>
    <h2 class="CategoryName"><?=$category->name?></h2>
      <?php foreach($dishes as $dish) { ?>
          <?php if($dish->categoryId === $category->categoryId){ ?>
            <?php DrawDish($dish); ?>
          <?php } ?>
      <?php } ?>
<?php } ?>

<?php function DrawDish(Dish $dish){ ?>
  <div id="dishes" dish-id="<?=$dish->dishId?>">
    <h4 id="dish-name"><?=$dish->name?></h4>
    <p id="dish-price"><?=number_format($dish->price, 2, '.', '')?></p>
    <img src="../images/dishes/thumbs_small/<?=$dish->imageId?>.jpg" alt="Screen 1">
    <p class="DishIngredients">
      <?=$dish->ingredients?>
      </p>
    <p class="Dish">
      <?php if($dish->isVegan) echo "Vegan";  
            else echo "" ?> 
    </p>
  <input class="quantity" type="number" value="1">
  <button type="submit" class="registerbtn">Purchase</button>
</div>
  
<?php } ?>


<?php function drawOwnerRestaurants(array $restaurants, array $categories){ ?>
  <h3 id="title_rest"> My Restaurants </h3>
  <section id="restaurants">
    <?php foreach($restaurants as $restaurant) { ?>
      
      <div class="rest_items">
        <img src="../images/restaurants/thumbs_small/<?=$restaurant->imageId?>.jpg" alt="Screen 2" <?=$restaurant->restaurantId?>">
        <span class="caption">
          <a href="/../pages/restaurant.php?id=<?=$restaurant->restaurantId?>">
              <?=$restaurant->restaurantName?>
          </a>
          <?php $db = getDatabaseConnection(); ?>
          <?php $category = Category::getCategory($db, $restaurant->categoryId)?>
          <p><?=$category->name?></p>
        </span>
      </div>
      </article>
    <?php } ?>
  </section>

<?php } ?>


<?php 

function drawMarkRestaurantAsFavorite(bool $isChecked){ ?>
  <?php 
    $imageUrl = $isChecked ? "../images/assets/favorite-star-full.png"
        :  "../images/assets/favorite-star-empty.png"
            ?>
    <form action="../actions/action_favorite_restaurant.php" method="post">
      <label><input name="isChecked" type="checkbox" class="hide" value="<?=$isChecked?"true":"false"?>" <?=$isChecked? "checked = 'checked'":""?> ></label>
      <button type="submit"><img src="<?=$imageUrl?>" width="20" height="20"></button>
    </form>

<?php } ?>
