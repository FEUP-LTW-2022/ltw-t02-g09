<?php function drawRegisterForm() { ?> 
  <form action="../actions/action_register.php"  method="post">
  <div class="container">
    <h1 id="register-phrase">Sign Up</h1>

    <select name="user-type" class="select-form">
        <option value="" disabled selected>Select Your User Type</option>
        <option value="Owner">Owner</option>
        <option value="Customer">Customer</option>
    </select>

      <input type="email" placeholder="Enter Email" name="email" required>  

      <div class="form-group was-validated">
        <input type="text" placeholder="Enter Password"  name="password_reg" class="form-control form-control-filled" required pattern=".{8,}" autocorrect="off" autocapitalize="off" autocomplete="off">
      <label for="password_reg" class="form-control-label"></label>
      </div>


      <input type="text" placeholder="Enter First Name" name="first-name" required>
      
      <input type="text" placeholder="Enter Last Name" name="last-name" required>

      <div class="form-group was-validated">
        <input type="text" placeholder="+351" id="mobile" name="password_reg" class="form-control form-control-filled" required pattern="[0-9]*" minlength="9" maxlength="9" autocorrect="off" autocapitalize="off" autocomplete="off">
      <label for="mobile" class="mobile"></label>
      </div>

      <br br>
      <button type="submit" id="register-btn">Sign Up</button>
    
      <div id="create-account-wrap">
      <section class="new-text" id="new-text">
        <div class="column">
          <h2 class="cntr">Advantages of creating an account</h2>
          <ul>
            <li>
                <i class="check"></i>
                Allows for favourite restaurants
            </li>
            <li>
                <i class="check"></i>
                Avoid filling in your information repeatedly
            </li>
            <li>
                <i class="check"></i>
                Allows safer and easier payment
            </li>
          </ul>
        </div>
      </section>
        <p>Already have account? <a href="../pages/login.php">Log in</a>.</p>
      </div>
  </div>
</form>
  <?php } ?>


  <?php function drawLoginForm() { ?>
  <form action="../actions/action_login.php" method="post" class="login">
  <div class="container">
  <h1 id="login-phrase">Sign In</h1>
    <input type="email" name="login_email" id="login_email" placeholder="Enter Email">
    
    <input type="password" name="login_pswd" id= "login_pswd" placeholder="Enter Password">
    
    <button type="submit" name="login" id="login-btn">Sign In</button>
  </form>
  <div id="create-account-wrap">
  <p>Not a member? <a href="register.php">Create Account</a></p><p>
  </p></div>
  </div>
<?php } ?>


<?php function drawLogoutForm(string $name) { ?>
  <form action="action_logout.php" method="post" class="logout">
    <a href="profile.php"><?=$name?></a>
    <button type="submit">Logout</button>
  </form>
<?php } ?>

<?php function drawRestaurantForm(array $categories){ ?>
  <form action="../actions/action_create_restaurant.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>Add Your Restaurant</h1>

    <div>
      <input type="text" placeholder="Restaurant Name" name="rest-name" required>
    </div>

    <div>
      <select name="rest-price" class="select-form" required">
        <option value="" disabled selected>Select Price</option>
        <option value="cheap">$</option>
        <option value="medium">$$</option>
        <option value="expensive">$$$</option>
      </select>
    </div>
    
    <div>
        <select name="rest-category" class="select-form" required">
          <option value="" disabled selected>Select Category</option>
            <?php foreach($categories as $category){ ?>
              <option value="<?=$category->name?>"><?=$category->name?></option>
            <?php } ?>
        </select>
    </div>
    
    <div>
      <input type="text" name="image_title" placeholder="Enter Image Title" required>
    </div>

    <div>
    <input type="file" name="rest-image" required>
    </div>

    <div>
      <input type="text" placeholder="Address Line One" name="rest-addr-one" required>
    </div> 
    
    <div> 
      <input type="text" placeholder="Address Line Two" name="rest-addr-two">
    </div> 

    <div>
      <input type="text" name="rest-city" placeholder="Enter City" required>
    </div>

    <div>
      <input type="text" name="rest-country" placeholder="Enter Country" required>
    </div>

    <div>
      <input type="tel" name="rest-postalcode" placeholder="Enter Postalcode" pattern="[0-9]{4}-[0-9]{3}" required>
    </div>
    
    <div>
      <button type="submit" id="register-btn">Register</button>
    </div>
</form>

<?php } ?>

<?php function drawDishForm(array $categories){ ?>
  <form action="../actions/action_create_dish.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>Add Dish</h1>

    <div>
      <input type="text" placeholder="Enter Name" name="dish-name" required>
    </div>

    <div>
      <input type="text" placeholder="Enter Price (€)" name="dish-price" required>  
    </div>
    

    <div>
      <input type="text" name="dish-ing" placeholder="Enter Ingredients"required>
    </div>

    <div>
      <select name="dish-categ" class="select-form" required">
        <option value="" disabled selected>Select Category</option>
          <?php foreach($categories as $category){ ?>
            <option value="<?=$category->name?>"><?=$category->name?></option>
          <?php } ?>
      </select>
    </div>

    <div>
      <input type="text" name="image_title" placeholder="Enter Image Title">
    </div>
    <input type="file" name="dish-image">
    <div>
    <button type="submit" id="register-btn">Register</button>
    </div>
  </form>

<?php } ?>

<?php function drawProfileForm(User $user){ ?>
  <div class="container">
  <h2>Profile</h2>
  <form action="../actions/action_edit_profile.php" method="post" class="profile">

    <input id="edit-password" type="password" name="edit-password" value="<?=$user->password?>">

    <input id="edit-first-name" type="text" name="edit-first-name" value="<?=$user->firstName?>">
    
    <input id="edit-last-name" type="text" name="edit-last-name" value="<?=$user->lastName?>">  

    <input type="tel" value="<?=$user->mobile?>" name="edit-mobile" pattern="[0-9]{9}">
    
    <button type="submit" id="register-btn">Save</button>
  </form>
  </div>
<?php } ?>


<?php function drawAddressForm(PDO $db, User $user){ ?>
  <div class="container">
  <h2>Address</h2>
  <?php if($user->getAddressStatus($db)){ ?>
    <form action="/../actions/action_edit_user_address.php" method="post" class="profile">
  <?php } else { ?>
    <form action="/../actions/action_create_user_address.php" method="post" class="profile">
  <?php } ?>
      <div>
        <input id="add-one" type="text" name="add-one" placeholder="Address Line One" required>
      </div>

      <div>
        <input id="add-two" type="text" name="add-two" placeholder="Address Line Two">
      </div>

      <div>
        <input id="add-city" type="text" name="add-city" placeholder="Enter City" required>  
      </div>

      <div>
        <input id="add-country" type="text" name="add-country" placeholder="Enter Country" required>
      </div>

      <div>
        <input type="tel" name="add-pc" pattern="[0-9]{4}-[0-9]{3}"  placeholder="Enter Postalcode" required>  
      </div>

      <button type="submit" id="register-btn">Save</button>
    </div>
  </form>
<?php } ?>

<?php function drawOrderForm() { ?>
  <form action="../actions/action_edit_profile.php" method="post" class="profile">
    <h2>My Order</h2>
    <div>
      <label for="add-note-order">Leave a Note: </label>
      <input type="text" name="add-note-order">  
    </div>
    <button type="submit">Save</button>
  </form>
<?php } ?>

<?php function drawEditRestaurantForm(array $categories){ ?>
  <form action="../actions/action_edit_restaurant.php" method="post">
  <div class="container">
    <h1>Edit Your Restaurant</h1>

    <div>
      <input type="text" placeholder="Enter Restaurant Name" name="rest-name" required>
    </div>

    <div>
      <select name="rest-price" class="select-form" required">
        <option value="" disabled selected>Select Price</option>
        <option value="cheap">$</option>
        <option value="medium">$$</option>
        <option value="expensive">$$$</option>
      </select>
    </div>
    
    <div>
        <select name="rest-category" class="select-form">

            <?php foreach($categories as $category){ ?>
              <option value="" disabled selected>Select Category</option>
              <option value="<?=$category->name?>"><?=$category->name?></option>
            <?php } ?>
        </select>
    </div>
    
    <div>
      <input type="text" name="image_title" placeholder="Image Title">
    </div>

    <input type="file" name="rest-image">

    <div>
      <input type="text" placeholder="Address Line One" name="rest-addr-one" required>
    </div> 
    
    <div> 
      <input type="text" placeholder="Address Line Two" name="rest-addr-two">
    </div> 

    <div>
      <input type="text" name="rest-city" placeholder="Enter City" required>
    </div>

    <div>
      <input type="text" name="rest-country" placeholder="Enter Country" required>
    </div>

    <div>
      <input type="tel" name="rest-postalcode" placeholder="Enter Postalcode" pattern="[0-9]{4}-[0-9]{3}" required>
    </div>
    
    <div>
      <button type="submit" id="register-btn">Save</button>
    </div>
</form>
<?php } ?>