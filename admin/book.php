<?php
include '_header.php';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql_params = [];
$sql_types = '';

$base_sql = "SELECT b.*, c.name as category_name, i.image_path 
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

$base_sql .= " GROUP BY b.id ORDER BY b.id DESC";
$books_query = $conn->prepare($base_sql);

if (!empty($search_query)) {
    $books_query->bind_param($sql_types, ...$sql_params);
}

$books_query->execute();
$books_result = $books_query->get_result();

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="page-title">Book Management</h1>
    <a href="book_add.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Book</a>
</div>

<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
<?php endif; ?>

<form action="book.php" method="get" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i> Search</button>
        <?php if (!empty($search_query)): ?>
            <a href="book.php" class="btn btn-outline-danger">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="card p-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th class="text-center">Cover Image</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Stock</th>
                        <th>Category</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($books_result->num_rows > 0): ?>
                        <?php while ($book = $books_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo $book['id']; ?></strong></td>
                                <td class="text-center">
                                    <img src="uploads/<?php echo !empty($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default_cover.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($book['title']); ?>" 
                                         style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td class="text-end"><?php echo number_format($book['price'], 2); ?> USD</td>
                                <td class="text-center"><?php echo $book['stock']; ?></td>
                                <td><?php echo htmlspecialchars($book['category_name'] ?? 'N/A'); ?></td>
                                <td class="text-center">
                                    <a href="book_edit.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="book_delete.php?id=<?php echo $book['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger delete-book-btn" 
                                       title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="delete-confirm-box" class="message-box-overlay">
    <div class="message-box">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to permanently delete this book? This action cannot be undone.</p>
        <div class="message-box-buttons">
            <button id="cancel-delete" class="btn btn-cancel">Cancel</button>
            <a id="confirm-delete-link" href="#" class="btn btn-confirm">Delete</a>
        </div>
    </div>
</div>

<?php
echo '</main>';
echo '</div>';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteConfirmBox = document.getElementById('delete-confirm-box');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const confirmDeleteLink = document.getElementById('confirm-delete-link');

    function closeDeleteConfirmBox() {
        deleteConfirmBox.classList.remove('show');
        setTimeout(() => { deleteConfirmBox.style.display = 'none'; }, 300);
    }

    document.querySelectorAll('.delete-book-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const deleteUrl = this.getAttribute('href');
            confirmDeleteLink.setAttribute('href', deleteUrl);
            deleteConfirmBox.style.display = 'flex';
            setTimeout(() => { deleteConfirmBox.classList.add('show'); }, 10);
        });
    });

    if(cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeDeleteConfirmBox);
    }
    if(deleteConfirmBox) {
        deleteConfirmBox.addEventListener('click', function(event) {
            if (event.target === deleteConfirmBox) {
                closeDeleteConfirmBox();
            }
        });
    }
});
</script>

<?php
$books_query->close();
$conn->close();
?>