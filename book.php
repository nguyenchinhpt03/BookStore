<?php
require_once 'config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$book_id = (int)$_GET['id'];
$stmt = $conn->prepare(
    "SELECT b.*, c.name AS category_name, i.image_path
     FROM books b
     LEFT JOIN categories c ON b.category_id = c.id
     LEFT JOIN book_images i ON b.id = i.book_id AND i.is_cover = 1
     WHERE b.id = ?"
);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
if (!$book) {
    header("Location: index.php");
    exit();
}
include 'header.php';
?>
<div class="book-detail-container">
    <div class="book-image">
        <img src="admin/uploads/<?php echo !empty($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default_cover.jpg'; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
    </div>
    <div class="book-info">
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>

        <div class="book-meta">
            <p class="author">
                <i class="fas fa-user-edit"></i>
                <strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?>
            </p>
            <p class="category">
                <i class="fas fa-tags"></i>
                <strong>Category:</strong> <?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
            </p>
        </div>

        <h2 class="price"><?php echo number_format($book['price'], 2); ?> USD</h2>

        <p class="stock-status">
            <strong>Status:</strong>
            <?php
                if ($book['stock'] > 0) {
                    echo '<span class="status-badge in-stock">In stock</span> (' . $book['stock'] . ' available)';
                } else {
                    echo '<span class="status-badge out-of-stock">Out of stock</span>';
                }
            ?>
        </p>
        <hr>
        <div class="description">
            <h3>Product Description</h3>
            <p><?php echo !empty($book['description']) ? nl2br(htmlspecialchars($book['description'])) : 'No description available for this product.'; ?></p>
        </div>

        <?php if ($book['stock'] > 0): ?>
            <div class="cart-action-box">
                <form action="cart.php" method="post" class="add-to-cart-form">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $book['stock']; ?>" required>
                    </div>
                    <button type="submit" class="btn"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </form>
            </div>
        <?php else: ?>
            <p class="alert alert-danger">This product is currently out of stock.</p>
        <?php endif; ?>
    </div>
</div>
<?php
$stmt->close();
$conn->close();
?>