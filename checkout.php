<?php
require_once 'config/db.php';
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout');
    exit();
}
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fullname, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $delivery_address = trim($_POST['delivery_address']);
    $phone_number = trim($_POST['phone_number']);

    if (empty($customer_name) || empty($delivery_address) || empty($phone_number)) {
        $errors[] = "Please fill in all delivery information.";
    }

    if (empty($errors)) {
        $conn->begin_transaction();
        
        try {
            $cart_ids = array_keys($_SESSION['cart']);
            $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
            $types = str_repeat('i', count($cart_ids));
            $sql = "SELECT id, title, price, stock FROM books WHERE id IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$cart_ids);
            $stmt->execute();
            $products_result = $stmt->get_result();
            $books_in_db = [];
            while ($row = $products_result->fetch_assoc()) {
                $books_in_db[$row['id']] = $row;
            }

            $total = 0;
            foreach ($_SESSION['cart'] as $book_id => $quantity) {
                if ($quantity > $books_in_db[$book_id]['stock']) {
                    throw new Exception("Product '" . $books_in_db[$book_id]['title'] . "' is out of stock for the requested quantity.");
                }
                $total += $books_in_db[$book_id]['price'] * $quantity;
            }
            
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total, customer_name, delivery_address, phone_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idsss", $user_id, $total, $customer_name, $delivery_address, $phone_number);
            $stmt->execute();
            $order_id = $conn->insert_id;

            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
            $update_stock_stmt = $conn->prepare("UPDATE books SET stock = stock - ? WHERE id = ?");

            foreach ($_SESSION['cart'] as $book_id => $quantity) {
                $price = $books_in_db[$book_id]['price'];
                $item_stmt->bind_param("iiid", $order_id, $book_id, $quantity, $price);
                $item_stmt->execute();

                $update_stock_stmt->bind_param("ii", $quantity, $book_id);
                $update_stock_stmt->execute();
            }

            $conn->commit();

            unset($_SESSION['cart']);
            header('Location: order_history.php?order_success=1');
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Order failed: " . $e->getMessage();
        }
    }
}
?>

<h2>Checkout Information</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
    </div>
<?php endif; ?>

<div class="checkout-container">
    <div class="delivery-info">
        <h3>Delivery Information</h3>
        <form action="checkout.php" method="post">
            <div class="form-group">
                <label for="customer_name">Recipient Name</label>
                <input type="text" name="customer_name" id="customer_name" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="delivery_address">Delivery Address</label>
                <textarea name="delivery_address" id="delivery_address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn">Confirm Order</button>
        </form>
    </div>
</div>
