<?php
include '_auth.php';
require_once '../config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $allowed_statuses = ['pending', 'processing', 'shipped', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
include '_header.php';
$orders_query = $conn->query("SELECT o.*, u.fullname FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
?>
<h1 class="page-title">Order Management</h1>
<div class="card p-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orders_query->num_rows > 0): ?>
                        <?php while($order = $orders_query->fetch_assoc()): 
                            $status_badge = '';
                            switch ($order['status']) {
                                case 'shipped': $status_badge = 'bg-success'; break;
                                case 'processing': $status_badge = 'bg-info'; break;
                                case 'cancelled': $status_badge = 'bg-danger'; break;
                                default: $status_badge = 'bg-secondary';
                            }
                        ?>
                            <tr>
                                <td><strong>#<?php echo $order['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                                <td><?php echo number_format($order['total'], 2); ?> USD</td>
                                <td>
                                    <span class="badge <?php echo $status_badge; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td class="text-center">
                                    <a href="order_view.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
echo '</main>';
echo '</div>';
$conn->close();
?>