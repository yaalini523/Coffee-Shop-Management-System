<?php
include('conn.php');
if(isset($_POST['submit'])){
    $title=$_POST['product_title'];
    $description=$_POST['Description'];
    $keyword=$_POST['Keyword'];
    $categories=$_POST['categories'];
    $price=$_POST['price'];

    $p_image=$_FILES['images']['name'];

    $tmp_image=$_FILES['images']['tmp_name'];

    if($title=='' or $description=='' or $keyword=='' or $categories=='' or $price=='' or $p_image=='' ){
        echo "<script>alert('Please Fill All The Available Fields')</script>";
        exit();
    }else{
        move_uploaded_file($tmp_image,"./product images/$p_image");
    }

    $insert_products="insert into product(product_title,product_description,product_keyword,category_name,product_image,product_price) values('$title','$description','$keyword','$categories','$p_image','$price')";
    $result_query=mysqli_query($conn,$insert_products);
    if($result_query){
        echo "<script>alert('Successfully Inserted')</script>";
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
    <title>Document</title>
</head>
<body class="bg-light">
    <div class="container mt-3">
        <h1 class="text-center">Insert Product</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_title" class="form-label">Product Title</label>
                <input type="text" name="product_title" id="product_title" class="form-control" placeholder="Enter Product Title" autocomplete="off" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="Description" class="form-label">Product Description</label>
                <input type="text" name="Description" id="Description" class="form-control" placeholder="Enter Description" autocomplete="off" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="Keyword" class="form-label">Product Keyword</label>
                <input type="text" name="Keyword" id="Keyword" class="form-control" placeholder="Enter Keyword" autocomplete="off" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <select name="categories" id="categories" class="form-select">
                    <option value="">select categories</option>
                    <?php
                        $select_query="select * from categories";
                        $result_query=mysqli_query($conn,$select_query);
                        while($row=mysqli_fetch_assoc($result_query)){
                            $category_name=$row['category_name'];
                            echo "<option value='$category_name'>$category_name</option>";

                        }
                    ?>
                    
                </select>
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="images" class="form-label">Product Images</label>
                <input type="file" name="images" id="images" class="form-control" placeholder="Enter Product images"  required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="price" class="form-label">Product Price</label>
                <input type="text" name="price" id="price" class="form-control" placeholder="Enter Product price" autocomplete="off" required="required">
            </div>
            <div class="form-outline mb-4 w-50 m-auto">
                <input type="submit" name="submit" class="btn btn-info mb-3 px-3" value="Insert Product">
            </div>

        </form>
    </div>
    
</body>
</html>