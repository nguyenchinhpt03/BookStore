<?php
require_once 'config/db.php';
include 'header.php';

$bestsellers_stmt = $conn->prepare(
    "SELECT b.*, SUM(oi.quantity) as total_sold, i.image_path
     FROM order_items oi
     JOIN books b ON oi.book_id = b.id
     LEFT JOIN book_images i ON b.id = i.book_id AND i.is_cover = 1
     GROUP BY b.id
     ORDER BY total_sold DESC
     LIMIT 5"
);
$bestsellers_stmt->execute();
$bestsellers_result = $bestsellers_stmt->get_result();

$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql_params = [];
$sql_types = '';
$base_sql = "SELECT b.*, i.image_path
             FROM books b
             LEFT JOIN categories c ON b.category_id = c.id
             LEFT JOIN book_images i ON b.id = i.book_id AND i.is_cover = 1";

if (!empty($search_query)) {
    $base_sql .= " WHERE b.title LIKE ? OR b.author LIKE ? OR c.name LIKE ?";
    $search_term = "%" . $search_query . "%";
    $sql_params[] = $search_term;
    $sql_params[] = $search_term;
    $sql_params[] = $search_term;
    $sql_types = 'sss';
}

$base_sql .= " GROUP BY b.id ORDER BY b.created_at DESC";

$total_stmt_sql = "SELECT COUNT(b.id) AS total FROM books b LEFT JOIN categories c ON b.category_id = c.id";
if (!empty($search_query)) {
    $total_stmt_sql .= " WHERE b.title LIKE ? OR b.author LIKE ? OR c.name LIKE ?";
}
$total_stmt = $conn->prepare($total_stmt_sql);
if (!empty($search_query)) {
    $total_stmt->bind_param($sql_types, ...$sql_params);
}
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_books = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_books / $records_per_page);
$total_stmt->close();

$all_books_stmt_sql = $base_sql . " LIMIT ? OFFSET ?";
$all_books_stmt = $conn->prepare($all_books_stmt_sql);
$sql_params[] = $records_per_page;
$sql_params[] = $offset;
$sql_types .= 'ii';
$all_books_stmt->bind_param($sql_types, ...$sql_params);
$all_books_stmt->execute();
$all_books_result = $all_books_stmt->get_result();
?>

<div style="text-align: center; margin-bottom: 40px;">
    <h1>Welcome to the Bookstore Online!</h1>
</div>

<?php if (empty($search_query)): ?>
<h2>Bestsellers</h2>
<div class="books-grid">
    <?php if ($bestsellers_result->num_rows > 0): ?>
        <?php while ($book = $bestsellers_result->fetch_assoc()): ?>
            <div class="book-card">
                <a href="book.php?id=<?php echo $book['id']; ?>">
                    <img src="admin/uploads/<?php echo !empty($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default_cover.jpg'; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    
                    <div class="book-details">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="price"><?php echo number_format($book['price'], 2); ?> USD</p>
                    </div>

                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No bestseller information available yet.</p>
    <?php endif; ?>
</div>

<hr style="margin: 40px 0;">
<?php endif; ?>

<?php if (!empty($search_query)): ?>
<h2>Search results for: "<?php echo htmlspecialchars($search_query); ?>"</h2>
<?php endif; ?>

<div class="books-grid">
    <?php if ($all_books_result->num_rows > 0): ?>
        <?php while ($book = $all_books_result->fetch_assoc()): ?>
            <div class="book-card">
                <a href="book.php?id=<?php echo $book['id']; ?>">
                    <img src="admin/uploads/<?php echo !empty($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default_cover.jpg'; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    
                    <div class="book-details">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="price"><?php echo number_format($book['price'], 2); ?> USD</p>
                    </div>

                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php 
    $pagination_url = 'index.php?';
    if (!empty($search_query)) {
        $pagination_url .= 'search=' . urlencode($search_query) . '&';
    }
    
    if ($page > 1): ?>
        <a href="<?php echo $pagination_url; ?>page=<?php echo $page - 1; ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="<?php echo $pagination_url; ?>page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="<?php echo $pagination_url; ?>page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php
$bestsellers_stmt->close();
$all_books_stmt->close();
$conn->close();
?>