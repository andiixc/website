<?php
session_start();
require 'products.php';

// Initialize the cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle AJAX request to update quantity without page reload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = (int)$_POST['quantity'];

    if ($newQuantity > 0) {
        $_SESSION['cart'][$productId] = $newQuantity;
    } else {
        unset($_SESSION['cart'][$productId]); // Remove item if quantity is 0 or less
    }
    exit; // Stop further processing to send AJAX response
}

// Display cart items and calculate total
$cartItems = [];
$totalPrice = 0;

foreach ($_SESSION['cart'] as $id => $quantity) {
    if (isset($products[$id])) {
        $product = $products[$id];
        $product['quantity'] = $quantity;
        $product['total'] = $product['price'] * $quantity;
        $totalPrice += $product['total'];
        $cartItems[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cartss.css">
</head>
<body>

<h1>Your Cart</h1>

<div class="cart-items">
    <?php if (empty($cartItems)) { ?>
        <p>Your cart is empty.</p>
    <?php } else { ?>
        <?php foreach ($cartItems as $item) { ?>
            <div class="cart-item" id="cart-item-<?php echo $item['id']; ?>">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="100">
                <div class="cart-item-info">
                    <h2><?php echo $item['name']; ?></h2>
                    <p>Price: ₱<?php echo number_format($item['price'], 2); ?></p>
                    <div class="quantity-control">
                        <!-- Quantity controls with AJAX functionality -->
                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                        <input type="number" id="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                        <button onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                        <button onclick="removeItem(<?php echo $item['id']; ?>)">Remove</button>
                    </div>
                    <p>Total: ₱<span id="total-<?php echo $item['id']; ?>"><?php echo number_format($item['total'], 2); ?></span></p>
                </div>
            </div>
        <?php } ?>
        <p class="total-price">Total Price: ₱<span id="total-price"><?php echo number_format($totalPrice, 2); ?></span></p>
    <?php } ?>
</div>

<a href="homepage.php" class="back-to-store">Back to Store</a>

<script>
// Function to update quantity using AJAX
function updateQuantity(id, change) {
    const quantityInput = document.getElementById('quantity-' + id);
    let newQuantity = parseInt(quantityInput.value) + change;
    if (newQuantity < 1) newQuantity = 1;

    // Update visually
    quantityInput.value = newQuantity;

    // Send AJAX request to update quantity in PHP session
    const formData = new FormData();
    formData.append('update_quantity', true);
    formData.append('product_id', id);
    formData.append('quantity', newQuantity);

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        // Refresh total price display for the item and overall
        const itemPrice = parseFloat(document.getElementById('price-' + id).textContent.replace('₱', ''));
        document.getElementById('total-' + id).textContent = (itemPrice * newQuantity).toFixed(2);

        // Update total price in cart
        updateTotalPrice();
    });
}

// Function to remove item from cart
function removeItem(id) {
    const formData = new FormData();
    formData.append('remove_from_cart', true);
    formData.append('product_id', id);

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        // Remove item from the DOM
        document.getElementById('cart-item-' + id).remove();

        // Update total price in cart
        updateTotalPrice();
    });
}

// Function to calculate total price in the cart
function updateTotalPrice() {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        const totalElement = item.querySelector('[id^="total-"]');
        if (totalElement) total += parseFloat(totalElement.textContent);
    });
    document.getElementById('total-price').textContent = total.toFixed(2);
}
</script>

</body>
</html>


