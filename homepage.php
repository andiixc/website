<?php
session_start();
include("connect.php");
require 'products.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Sneaker Store</title>
  <link rel="stylesheet" href="main.css">
  </head>
  
<body>
  <h1>Sole Street</h1>

  <h2>Step up your sneaker game with <span>Sole Street</span> — your ultimate destination for the latest, trendiest sneakers! Discover a curated selection of iconic brands, limited-edition releases, and everyday essentials that keep you stylish from street to sport.</h2>


  <div class="product-list">
    <?php foreach ($products as $id => $product) { ?>
      <div class="product">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h2><?php echo $product['name']; ?></h2>
        <p>Price: ₱<?php echo number_format($product['price'], 2); ?></p>
        <p><a href="product.php?id=<?php echo $id; ?>">View Details</a></p>
        <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?php echo $id; ?>">
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </div>
    <?php } ?>

  </div>
</body>
</html>