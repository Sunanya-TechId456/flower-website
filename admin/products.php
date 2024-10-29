<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   $select_products = mysqli_prepare($conn, "SELECT * FROM `products` WHERE name = ?");
   mysqli_stmt_bind_param($select_products, 's', $name);
   mysqli_stmt_execute($select_products);
   $result = mysqli_stmt_get_result($select_products);

   if (mysqli_num_rows($result) > 0) {
      $message[] = 'product name already exists!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'image size is too large';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = mysqli_prepare($conn, "INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
         mysqli_stmt_bind_param($insert_product, 'ssds', $name, $category, $price, $image);
         mysqli_stmt_execute($insert_product);

         $message[] = 'new product added!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_product_image = mysqli_prepare($conn, "SELECT * FROM `products` WHERE id = ?");
   mysqli_stmt_bind_param($delete_product_image, 'i', $delete_id);
   mysqli_stmt_execute($delete_product_image);
   $result_delete_image = mysqli_stmt_get_result($delete_product_image);
   $fetch_delete_image = mysqli_fetch_assoc($result_delete_image);
   unlink('../uploaded_img/' . $fetch_delete_image['image']);
   $delete_product = mysqli_prepare($conn, "DELETE FROM `products` WHERE id = ?");
   mysqli_stmt_bind_param($delete_product, 'i', $delete_id);
   mysqli_stmt_execute($delete_product);
   $delete_cart = mysqli_prepare($conn, "DELETE FROM `cart` WHERE pid = ?");
   mysqli_stmt_bind_param($delete_cart, 'i', $delete_id);
   mysqli_stmt_execute($delete_cart);
   header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- add products section starts  -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>select category --</option>
         <option value="gifts">gifts</option>
         <option value="home decor">home decor</option>
         <option value="events">events</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<!-- add products section ends -->

<!-- show products section starts  -->

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
      $show_products = mysqli_query($conn, "SELECT * FROM `products`");
      if (mysqli_num_rows($show_products) > 0) {
         while ($fetch_products = mysqli_fetch_assoc($show_products)) {  
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_products['price']; ?><span>/-</span></div>
         <div class="category"><?= $fetch_products['category']; ?></div>
      </div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

   </div>

</section>

<!-- show products section ends -->

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
