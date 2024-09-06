<?php
session_start();
include('conn.php');

if (isset($_POST['remove_from_cart'])) {
    $product_id_to_remove = $_POST['remove_from_cart'];

    if (isset($_SESSION['cart'][$product_id_to_remove])) {
        // Get the product price and quantity to be removed
        $product_price = $_SESSION['cart'][$product_id_to_remove]['price'];
        $quantity_to_remove = $_SESSION['cart'][$product_id_to_remove]['quantity'];

        // Remove the selected product from the cart
        unset($_SESSION['cart'][$product_id_to_remove]);

        // Delete the corresponding item from the database
        $query = "DELETE FROM cart_items WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id_to_remove);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_POST['update_quantity'])) {
    $product_id_to_update = $_POST['update_quantity'];
    $new_quantity = $_POST['new_quantity'];

    if (isset($_SESSION['cart'][$product_id_to_update])) {
        // Update the quantity of the selected product in the cart
        $_SESSION['cart'][$product_id_to_update]['quantity'] = $new_quantity;
    }
}

$total_price = 0.0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $product) {
        $product_title = $product['title'];
        $product_price = floatval($product['price']);
        $quantity = $product['quantity'];
        $subtotal = $product_price * $quantity;
        $total_price += $subtotal;
        
        // Check if the product with the same product_id exists in the database
        $existing_product_query = "SELECT product_id FROM cart_items WHERE product_id = ?";
        $existing_product_stmt = $conn->prepare($existing_product_query);
        $existing_product_stmt->bind_param("i", $product_id);
        $existing_product_stmt->execute();
        $existing_product_stmt->store_result();
        $num_existing_products = $existing_product_stmt->num_rows;
        $existing_product_stmt->close();

        if ($num_existing_products > 0) {
            // If the product with the same product_id exists, update the record
            $update_query = "UPDATE cart_items SET product_title = ?, product_price = ?, quantity = ?, subtotal = ? WHERE product_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sdiid", $product_title, $product_price, $quantity, $subtotal, $product_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            // If the product doesn't exist, insert a new record
            $insert_query = "INSERT INTO cart_items (product_id, product_title, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("isdid", $product_id, $product_title, $product_price, $quantity, $subtotal);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="cart.css">
    <!-- ... (head section remains the same) ... -->
</head>
<body>
<div class="container">
    <h2>Your Cart</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $product) {
                    $product_title = $product['title'];
                    $product_price = floatval($product['price']);
                    $quantity = $product['quantity'];
                    $subtotal = $product_price * $quantity;
                    $total_price += $subtotal;
                    echo "<tr>
                        <td>$product_title</td>
                        <td>$product_price</td>
                        <td>
                            <form method='post' action='cart.php'>
                                <input type='number' name='new_quantity' value='$quantity' min='1'>
                                <input type='hidden' name='update_quantity' value='$product_id'>
                                <button type='submit' class='btn btn-primary'>Update Quantity</button>
                            </form>
                        </td>
                        <td>$subtotal</td>
                        <td>
                            <form method='post' action='cart.php'>
                                <input type='hidden' name='remove_from_cart' value='$product_id'>
                                <button type='submit' class='btn btn-danger'>Remove</button>
                            </form>
                        </td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <p>Total Price: Rs.<?php echo number_format($total_price, 2); ?></p>
</div>
</body>
</html>
