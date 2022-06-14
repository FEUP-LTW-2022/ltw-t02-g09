<?php 
  declare(strict_types = 1); 

?>

<?php function drawProfile(PDO $db, User $user, $address){ ?>

    <section>
        <div>
            <a href="../pages/edit_profile.php" class="edit_profile">Edit</a>
            <?= ": " . $user->email ?>
        </div>
        
        <div>
            <?= $user->name() ?>
        </div>

        <div>
            <?= $user->mobile ?>
        </div>

        <?php if($user->getAddressStatus($db)){ ?>
            <div>
                <a href="../pages/address.php" class="edit_profile">Update Address</a>
            </div>
            
            <div>
                <h3><?= $user->name() ?>'s Address</h3>
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

        <?php } else { ?>
            <div>
                <a href="../pages/address.php" class="edit_profile">Add Address</a>
            </div>
        <?php } ?>

    </section>

<?php } ?>

<?php function drawFavorite(array $restaurants) {?>
    <h2 class="user-fav-rest">Your favorite restaurants</h2>
    <section id="restaurants">
    <?php foreach($restaurants as $restaurant){ ?>
        <div class="rest_items">
        <img src="../images/restaurants/thumbs_small/<?=$restaurant->imageId?>.jpg" alt="Screen 2" <?=$restaurant->restaurantId?>">
        <span class="caption">
          <a href="/../pages/restaurant.php?id=<?=$restaurant->restaurantId?>">
              <?=$restaurant->restaurantName?>
            </a>
        </span>
        </div>
    <?php } ?>
    </section>

<?php } ?>