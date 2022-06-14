<?php declare(strict_types = 1); 
  require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawHeader(Session $session) { ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/../css/style.css">
  <script src="../javascript/script.js" defer></script>
  <title>Faustino Express Delivery</title>
</head>
<body>
  <header class="responsive-header">
  <?php if($session->isLoggedin()){?>
  <input id="toggle1" type="checkbox"/>
  <label class="hamburger1" for="toggle1">
      <div class="top"></div>
      <div class="meat"></div>
      <div class="bottom"></div>
  </label>
  
  <a href="../index.php"><img src="../images/logo/deliver-logo.png" id="logo" width="120" "alt="my logo"/></a>
  <div class="login_register">
    <a href="/../pages/profile.php">Hello, <?= $session->getName() ?></a>
  </div>

  <nav class="menu1">
      <div class="user-options">
        <a href="/../pages/order.php">My Orders</a>
      </div>
      <div class="user-options">
        <a href="/../pages/favorites.php">Favorites</a>
      </div>

      <?php if($session->isOwner()){?>
      <div class="user-options">
        <a href="/../pages/order_received.php">Orders Received</a>
      </div>
      <div class="user-options">
        <a href="../pages/add_restaurant.php" class="edit_profile">Add restaurant</a>
      </div>  
      <?php } ?>
      <div class="user-options">
        <a href="../actions/action_logout.php" class="edit_profile">Sign Out</a>
      </div>
      </nav>
    <?php } else { ?>
      <a href="../index.php"><img src="../images/logo/deliver-logo.png" width="120" "alt="my logo"/></a>
      <div class="login_register">
        <a href="/../pages/register.php" class="register">Register</a>
        <a href="/../pages/login.php" class="login">Login</a>
      </div>
    <?php } ?>
    </header>
    <hr>
    <main>
<?php } ?>

<?php function drawFooter() { ?>
    </main>
    <hr>
    <footer>

      LTW  &copy; 2022
    </footer>
  </body>
</html>
<?php } ?>
