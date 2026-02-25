<?php
include '_auth.php';
require_once '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: book.php");
    exit();
}
$book_id = (int)$_GET['id'];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);

    if (empty($title)) $errors[] = "Book title cannot be empty.";
    if (empty($author)) $errors[] = "Author name cannot be empty.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Invalid book price.";
    if (!is_numeric($stock) || $stock < 0) $errors[] = "Invalid stock quantity.";

    if (empty($errors)) {
        $conn->begin_transaction();
        try {
            $update_stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, price = ?, stock = ?, description = ?, category_id = ? WHERE id = ?");
            $update_stmt->bind_param("ssdisii", $title, $author, $price, $stock, $description, $category_id, $book_id);
            $update_stmt->execute();
            $update_stmt->close();
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $target_dir = "uploads/";
                $imageFileType = strtolower(pathinfo($_FILES["cover_image"]["name"], PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];

                if (!in_array($imageFileType, $allowed_types)) {
                    throw new Exception("Only JPG, JPEG, PNG & GIF image files are allowed.");
                } 
                $new_image_name = "cover_" . $book_id . "." . $imageFileType;
                $target_file = $target_dir . $new_image_name;
                $old_image_path_query = $conn->prepare("SELECT image_path FROM book_images WHERE book_id = ? AND is_cover = 1");
                $old_image_path_query->bind_param("i", $book_id);
                $old_image_path_query->execute();
                $old_image_result = $old_image_path_query->get_result();
                if ($old_image_result->num_rows > 0) {
                    $old_image = $old_image_result->fetch_assoc();
                    if (!empty($old_image['image_path']) && file_exists($target_dir . $old_image['image_path'])) {
                        unlink($target_dir . $old_image['image_path']);
                    }
                }
                $old_image_path_query->close();
                if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                    $check_img_stmt = $conn->prepare("SELECT COUNT(*) FROM book_images WHERE book_id = ? AND is_cover = 1");
                    $check_img_stmt->bind_param("i", $book_id);
                    $check_img_stmt->execute();
                    $count = 0;
                    $check_img_stmt->bind_result($count);
                    $check_img_stmt->fetch();
                    $check_img_stmt->close();

                    if ($count > 0) {
                        $img_update_stmt = $conn->prepare("UPDATE book_images SET image_path = ? WHERE book_id = ? AND is_cover = 1");
                        $img_update_stmt->bind_param("si", $new_image_name, $book_id);
                    } else {
                        $img_update_stmt = $conn->prepare("INSERT INTO book_images (book_id, image_path, is_cover) VALUES (?, ?, 1)");
                        $img_update_stmt->bind_param("is", $book_id, $new_image_name);
                    }
                    $img_update_stmt->execute();
                    $img_update_stmt->close();
                } else {
                    throw new Exception("Error uploading the image.");
                }
            }
            
            $conn->commit();
            header("Location: book.php?message=Book updated successfully!");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Update error: " . $e->getMessage();
        }
    }
}
$stmt = $conn->prepare(
    "SELECT b.*, i.image_path 
     FROM books b
     LEFT JOIN book_images i ON b.id = i.book_id AND i.is_cover = 1
     WHERE b.id = ?"
);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book_result = $stmt->get_result();
if ($book_result->num_rows === 0) {
    header("Location: book.php");
    exit();
}
$book = $book_result->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
include '_header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="page-title">Edit Book</h1>
    <a href="book.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) echo "<p class='mb-0'>$error</p>"; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="book_edit.php?id=<?php echo $book_id; ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Book Title (*)</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author (*)</label>
                        <input type="text" name="author" id="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="10"><?php echo htmlspecialchars($book['description']); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (*)</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($book['price']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock Quantity (*)</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="<?php echo htmlspecialchars($book['stock']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Select a category --</option>
                            <?php while($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $book['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Cover Image</label>
                        <div>
                            <img src="uploads/<?php echo !empty($book['image_path']) ? htmlspecialchars($book['image_path']) : 'default_cover.jpg'; ?>" 
                                 alt="Cover" style="max-width: 150px; height: auto; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Change Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Update</button>
            </div>
        </form>
    </div>
</div>

<?php
echo '</main>';
echo '</div>';
$conn->close();
?>