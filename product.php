<?php
session_start();
include('conn.php');

// Check if search parameter is provided in the URL
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $select_query = "SELECT * FROM product WHERE product_title LIKE '%$search%' ORDER BY rand() LIMIT 0,9";
} else {
    $select_query = "SELECT * FROM product ORDER BY rand() LIMIT 0,9";
}

$result_query = mysqli_query($conn, $select_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_title = $_POST['product_title'];
    $product_price = floatval($_POST['product_price']); // Convert to float

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        $_SESSION['cart'][$product_id] = [
            'title' => $product_title,
            'price' => $product_price, // Store price as float
            'quantity' => 1,
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Product Page</title>
</head>
<body>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-light " style="background-color: black;">
            <div class="container-fluid">
                <img src="https://cmkt-image-prd.freetls.fastly.net/0.1.0/ps/2947931/4833/3217/m1/fpnw/wm1/cofswirlsinncm-.jpg?1499609613&s=02748e44fcea719032a50f68cd9693ff" alt="coffee" class="logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="coffee shop.html" style="color: white;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product.php" style="color: white;">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html" style="color: white;">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php" style="color: white;"><i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i></a>
                        </li>
                    </ul>
                    <form class="d-flex" method="get" action="product.php">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="bg-light">
            <h3 class="text-center">Coffee Store</h3>
            <p class="text-center">Coffee is a language in itself.</p>
        </div>

        <div class="row px-1">
            <div class="col-md-10">
                <div class="row">
                    <?php
                    while ($row = mysqli_fetch_assoc($result_query)) {
                        $id = $row['product_id'];
                        $title = $row['product_title'];
                        $description = $row['product_description'];
                        $category_name = $row['category_name'];
                        $p_image = $row['product_image'];
                        $price = $row['product_price'];
                        echo "<div class='col-md-4 mb-2'>
                            <div class='card'>
                                <img src='./product images/$p_image' class='card-img-top' alt='$title'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$title</h5>
                                    <p class='card-text'>$description</p>
                                    <form method='post' action=''>
                                        <input type='hidden' name='product_id' value='$id'>
                                        <input type='hidden' name='product_title' value='$title'>
                                        <input type='hidden' name='product_price' value='$price'>
                                        <button type='submit' name='add_to_cart' class='btn btn-primary' style='background-color: black;'>Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-2 bg-secondary p-0">
                <ul class="navbar-nav me-auto text-center">
                    <li class="nav-item" style="background-color: black !important;">
                        <a href="" class="nav-link text-light"><h4>Categories</h4></a>
                    </li>
                    <li class="nav-item ">
                        <a href="" class="nav-link text-light">Coffee Beans</a>
                    </li>
                    <li class="nav-item ">
                        <a href="" class="nav-link text-light">Coffee Powder</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-info p-3 text-center" style="background-color: black !important; color: white !important;">
            <p>Designed by Yaalini-2023 <a href="admin-login.html">Admin-login</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
