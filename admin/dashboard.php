<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Fetch admin profile (Assuming you have a query for this)
$fetch_profile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `admin` WHERE id = '$admin_id'"));

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">update profile</a>
   </div>

   <div class="box">
      <?php
         $total_pendings = 0;
         $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'");
         while ($fetch_pendings = mysqli_fetch_assoc($select_pendings)) {
            $total_pendings += $fetch_pendings['total_price'];
         }
      ?>
      <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
      <p>total pendings</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
         $total_completes = 0;
         $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'completed'");
         while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
            $total_completes += $fetch_completes['total_price'];
         }
      ?>
      <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>
      <p>total completes</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders`");
         $numbers_of_orders = mysqli_num_rows($select_orders);
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>total orders</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`");
         $numbers_of_products = mysqli_num_rows($select_products);
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>products added</p>
      <a href="products.php" class="btn">see products</a>
   </div>

   <div class="box">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`");
         $numbers_of_users = mysqli_num_rows($select_users);
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>users accounts</p>
      <a href="users_accounts.php" class="btn">see users</a>
   </div>

   <div class="box">
      <?php
         $select_admins = mysqli_query($conn, "SELECT * FROM `admin`");
         $numbers_of_admins = mysqli_num_rows($select_admins);
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>admins</p>
      <a href="admin_accounts.php" class="btn">see admins</a>
   </div>

   <div class="box">
      <?php
         $select_messages = mysqli_query($conn, "SELECT * FROM `message`");
         $numbers_of_messages = mysqli_num_rows($select_messages);
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>new messages</p>
      <a href="messages.php" class="btn">see messages</a>
   </div>

   </div>

</section>

<!-- admin dashboard section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
