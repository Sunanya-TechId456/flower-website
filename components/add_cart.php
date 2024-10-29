<?php

if (isset($_POST['add_to_cart'])) {

    if ($user_id == '') {
        header('location:login.php');
    } else {

        // Sanitize inputs
        $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
        $image = filter_var($_POST['image'], FILTER_SANITIZE_STRING);
        $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);

        // Check if the item is already in the cart
        $check_cart_numbers = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        mysqli_stmt_bind_param($check_cart_numbers, "si", $name, $user_id);
        mysqli_stmt_execute($check_cart_numbers);
        $result = mysqli_stmt_get_result($check_cart_numbers);

        if (mysqli_num_rows($result) > 0) {
            $message[] = 'already added to cart!';
        } else {
            // Insert new item into the cart
            $insert_cart = mysqli_prepare($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
            mysqli_stmt_bind_param($insert_cart, "iissss", $user_id, $pid, $name, $price, $qty, $image);
            mysqli_stmt_execute($insert_cart);
            $message[] = 'added to cart!';
        }

    }

}

?>
