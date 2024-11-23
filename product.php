<?php
// product.php
session_start();
require 'products.php';

$id = $_GET['id'] ?? 1;
$product = $products[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?></title>
  <link rel="stylesheet" href="style1.css">
</head>
<body>
  <h1><?php echo $product['name']; ?></h1>
  <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
  <p><?php echo $product['description']; ?></p>
  <p>Price: $<?php echo $product['price']; ?></p>
  <form action="cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
    <button type="submit" name="add_to_cart">Add to Cart</button>
  </form>
  <p><a href="homepage.php">Back to Store</a></p>
</body>
</html>
