<?php
include '_header.php';

$total_books = $conn->query("SELECT COUNT(id) AS total FROM books")->fetch_assoc()['total'];
$total_customers = $conn->query("SELECT COUNT(id) AS total FROM users WHERE role = 'customer'")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(id) AS total FROM orders")->fetch_assoc()['total'];
$revenue = $conn->query("SELECT SUM(total) AS revenue FROM orders WHERE status = 'shipped'")->fetch_assoc()['revenue'] ?: 0;
$out_of_stock_query = $conn->query("SELECT title, stock FROM books WHERE stock = 0");
$low_stock_query = $conn->query("SELECT title, stock FROM books WHERE stock > 0 AND stock < 10");
?>

<h1 class="page-title">Overview</h1>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="icon-wrapper bg-primary">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Total Books</h6>
                <h3 class="fw-bold"><?php echo $total_books; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="icon-wrapper bg-success">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Customers</h6>
                <h3 class="fw-bold"><?php echo $total_customers; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="icon-wrapper bg-warning text-dark">
                <i class="fas fa-receipt"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Total Orders</h6>
                <h3 class="fw-bold"><?php echo $total_orders; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="icon-wrapper bg-danger">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">Revenue</h6>
                <h3 class="fw-bold"><?php echo number_format($revenue, 2); ?> <small>USD</small></h3>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <?php if ($out_of_stock_query->num_rows > 0): ?>
        <div class="alert alert-danger-custom mb-3">
            <h4 class="alert-heading">The book is out of stock!</h4>
            <p>The following products are out of stock and need to be restocked:</p>
            <ul>
                <?php while ($book = $out_of_stock_query->fetch_assoc()): ?>
                    <li><strong><?php echo htmlspecialchars($book['title']); ?></strong></li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($low_stock_query->num_rows > 0): ?>
        <div class="alert alert-warning-custom mb-3">
            <h4 class="alert-heading">Book is almost out of stock!</h4>
            <p>The following products are in very limited stock:</p>
            <ul>
                <?php while ($book = $low_stock_query->fetch_assoc()): ?>
                    <li><strong><?php echo htmlspecialchars($book['title']); ?></strong> (remaining: <?php echo $book['stock']; ?>)</li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="mt-4">
    <h3 class="mb-3">Recent Orders</h3>
    <div class="card p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recent_orders_query = $conn->query("SELECT o.id, u.fullname, o.total, o.status, o.created_at FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
                    if ($recent_orders_query->num_rows > 0):
                        while($order = $recent_orders_query->fetch_assoc()):
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
                            <td><span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                        </tr>
                    <?php 
                        endwhile;
                    else: ?>
                        <tr><td colspan="5" class="text-center">No orders yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
?>