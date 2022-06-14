<?php 
  declare(strict_types = 1); 

?>

<?php function drawOrderTable(){ ?>
    <h2>Shopping Cart</h2>
    <section id="cart">
      <table>
        <thead>
          <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>
        </thead>
        <tfoot>
          <tr><th colspan="1">Total:</th><th id="total-price-order">0</th></tr>
        </tfoot>
      </table>
      <input typ
      <input type="text" id="order-note" placeholder="leave an optional note"> 
      <label id="confirm-order">Confirm Order</label>
      <button type="submit" id="confirm-order-now">Order</button>
    </section>
<?php } ?>

<?php function drawNotOrdered() { ?>
  <p id="no-order"> No orders around here ;(</p>
<?php } ?>

<?php function drawOrderedCustomer(Order $order, array $dishes) { ?>
  <div class="order-done">
  <h2>Your Order status: <?=$order->status?></h2>
  <p id="date-order">
  <?=$order->datetime?>
  </p>
  <p id="final-order-price">
  <?=$order->price?>
  </p>

  <?php foreach($dishes as $dish){ ?>
    <p>
    <?=$dish['quantity']?>x
    <?=$dish['Name']?>
  </p>
  <?php }?>
  </div>
<?php } ?>

<?php function drawOrderedOwner(array $dishes, $order, Restaurant $restaurant){ ?>
    <div>
      <label>Date and time of order:</label>
      <?=$order['DateTime']?>
      <div>
      <label>Food order:</label>
      <?php foreach($dishes as $dish){ ?>
        <?=$dish['quantity']?>x
        <?=$dish['Name']?>
      <?php }?>
      </div>
      <label>Price:</label>
      <?=$order['TotalPrice']?>
      <div>
      <label customer-order-id="<?=$order['OrderId']?>">Customer name:</label>
      <?=$order['FirstName']?>
      <?=$order['LastName']?>
      </div>
      <div>
      <label>Customer note:</label>
      <?=$order['Note']?>
      </div>
      <div>
        <label>Customer address:</label>
        <?=$order['AddressLineOne']?>
        <?=$order['AddressLineTwo']?>
        <?=$order['City']?>
        <?=$order['Country']?>
        <?=$order['postalcode']?>
      </div>
        <?php if($order['Status'] == "Delivered") {?>
          <label>Order is delivered!</label>
          <form method="post" action="../actions/action_delete_order.php">
          <input type="number" name="package-order-id" class="hide" value="<?=$order['OrderId']?>">
          <button type="submit">delete</button>
          </form>
        <?php } else {?>
          <label>Change order status:</label>
          <form method="post" action="../actions/action_change_order_status.php">
          <select name="change-order-status">
            <option value="Received">Received</option>
            <option value="Preparing">Preparing</option>
            <option value="Ready">Ready</option>
            <option value="Delivered">Delivered</option>
          </select>
          <input type="number" name="package-order-id" class="hide" value="<?=$order['OrderId']?>">
          <button type="submit">submit</button>
          </form>
      <?php } ?>
    </div>

<?php }?>

<?php function drawNoAddress() { ?>
  <p id="no-address">Add your address to create your first order!</p>
<?php } ?>

<?php function drawOrderBeingProcessed() { ?>
  <p id="order-processed">Your order is being processed! See it's status in "My Orders"</p>
<?php } ?>

<?php function drawRestaurantOrder(Restaurant $restaurant){ ?>
  <h2>You have received orders!</h2>
  <h4>From your restaurant: <?=$restaurant->restaurantName?></h4>
<?php } ?>