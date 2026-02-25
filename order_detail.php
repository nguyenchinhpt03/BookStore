<?php
require_once 'config/db.php';
include 'header.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "<p>Order not found or you don't have permission to view it.</p>";
    include 'footer.php';
    exit();
}

$item_stmt = $conn->prepare("SELECT oi.*, b.title FROM order_items oi JOIN books b ON oi.book_id = b.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$items = $item_stmt->get_result();
?>

<h2>Order Details #<?php echo $order['id']; ?></h2>
<div class="order-info">
    <p><strong>Date:</strong> <?php echo date("d/m/Y H:i", strtotime($order['created_at'])); ?></p>
    <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['status'])); ?></p>
    <p><strong>Recipient:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
</div>

<h3>Items in this Order</h3>
<table class="order-table">
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Quantity</th>
            <th>Price per Item</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['title']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="order_history.php" class="btn" style="margin-top: 20px; display: inline-block;"><i class="fas fa-arrow-left"></i> Back to Order History</a>