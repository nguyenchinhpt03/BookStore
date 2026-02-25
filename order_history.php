<?php
require_once 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<div class="order-history-container">
    <h2>Order History</h2>
    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success">Your order has been placed successfully! Thank you for shopping with us.</div>
    <?php endif; ?>
    <?php if ($orders->num_rows > 0): ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <?php
                        $status_class = '';
                        switch($order['status']) {
                            case 'pending':
                                $status_class = 'status-pending';
                                break;
                            case 'processing':
                                $status_class = 'status-processing';
                                break;
                            case 'shipped':
                                $status_class = 'status-shipped';
                                break;
                            case 'cancelled':
                                $status_class = 'status-cancelled';
                                break;
                        }
                    ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($order['created_at'])); ?></td>
                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                        <td>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                            </span>
                        </td>
                        <td><a href="order_detail.php?id=<?php echo $order['id']; ?>">View</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not placed any orders yet.</p>
    <?php endif; ?>
</div>