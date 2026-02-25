<?php
include '_header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: order.php");
    exit();
}

$order_id = (int)$_GET['id'];
$order_stmt = $conn->prepare(
    "SELECT o.*, u.fullname, u.email 
     FROM orders o 
     JOIN users u ON o.user_id = u.id 
     WHERE o.id = ?"
);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
if ($order_result->num_rows === 0) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    include '_footer.php';
    exit();
}
$order = $order_result->fetch_assoc();
$items_stmt = $conn->prepare(
    "SELECT oi.quantity, oi.price, b.title 
     FROM order_items oi 
     JOIN books b ON oi.book_id = b.id 
     WHERE oi.order_id = ?"
);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
?>
<div class="d-flex justify-content-between align-items-center">
    <h1 class="page-title">Order Details #<?php echo $order['id']; ?></h1>
    <a href="order.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Go Back</a>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
                <p class="mb-0"><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Order Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Total Amount:</strong> <span class="fw-bold text-danger"><?php echo number_format($order['total'], 2); ?> USD</span></p>
                <p class="mb-0"><strong>Current Status:</strong> 
                    <?php 
                        $status_badge = '';
                        switch ($order['status']) {
                            case 'shipped': $status_badge = 'bg-success'; break;
                            case 'processing': $status_badge = 'bg-info'; break;
                            case 'cancelled': $status_badge = 'bg-danger'; break;
                            default: $status_badge = 'bg-secondary';
                        }
                    ?>
                    <span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst($order['status']); ?></span>
                </p>
                <hr>
                <form action="order.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <div class="input-group">
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo ($order['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card p-0">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-box-open"></i> Products in this Order</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $items_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td class="text-end"><?php echo number_format($item['price'], 2); ?> USD</td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> USD</td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
echo '</main>';
echo '</div>';
$conn->close();
?>