<?php

// Including the database connection file
include 'components/connect.php';

// Starting the session
session_start();

// Checking if the user is logged in
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   // Redirecting to the user login page if not logged in
   header('location:user_login.php');
};

// Handling deletion of a single item from the cart
if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

// Handling deletion of all items from the cart
if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

// Handling updating quantity of items in the cart
if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- Font Awesome CDN link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">Shopping cart</h3>

   <div class="box-container">

   <?php
      // Initializing the grand total variable
      $grand_total = 0;
      // Selecting cart items for the logged-in user
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      // Checking if cart items exist
      if($select_cart->rowCount() > 0){
         // Looping through cart items
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <!-- Link to view item details -->
      <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <!-- Displaying item image -->
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <!-- Displaying item name -->
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="flex">
         <!-- Displaying item price -->
         <div class="price">Nrs.<?= $fetch_cart['price']; ?>/-</div>
         <!-- Input field for updating quantity -->
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <!-- Button to submit quantity update -->
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <!-- Displaying sub total for the item -->
      <div class="sub-total"> Sub Total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      <!-- Button to delete the item from cart -->
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
   // Updating grand total with sub total
   $grand_total += $sub_total;
      }
   }else{
      // Displaying message if cart is empty
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <!-- Displaying cart total section -->
   <div class="cart-total">
      <!-- Displaying grand total -->
      <p>Grand Total : <span>Nrs.<?= $grand_total; ?>/-</span></p>
      <!-- Link to continue shopping -->
      <a href="shop.php" class="option-btn">Continue Shopping.</a>
      <!-- Button to delete all items from cart -->
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete All Items ?</a>
      <!-- Link to proceed to checkout -->
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout.</a>
   </div>

</section>

<!-- Including footer -->
<?php include 'components/footer.php'; ?>

<!-- Including custom JavaScript file -->
<script src="js/script.js"></script>

</body>
</html>
