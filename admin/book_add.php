<?php
include '_header.php';
$errors = [];
$title = $author = $price = $stock = $description = $category_id = '';
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
    $conn->begin_transaction();
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO books (title, author, price, stock, description, category_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiis", $title, $author, $price, $stock, $description, $category_id);
            $stmt->execute();
            $book_id = $conn->insert_id; 

            $cover_image_path = null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                $target_dir = "uploads/";
                $imageFileType = strtolower(pathinfo($_FILES["cover_image"]["name"], PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
                
                if (!in_array($imageFileType, $allowed_types)) {
                    throw new Exception("Only JPG, JPEG, PNG & GIF image files are allowed.");
                } 
                $new_image_name = "cover_" . $book_id . "." . $imageFileType;
                $target_file = $target_dir . $new_image_name;
                
                if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                    $cover_image_path = $new_image_name;
                } else {
                    throw new Exception("An error occurred while uploading the image.");
                }
            }
            if ($cover_image_path) {
                $img_stmt = $conn->prepare("INSERT INTO book_images (book_id, image_path, is_cover) VALUES (?, ?, 1)");
                $img_stmt->bind_param("is", $book_id, $cover_image_path);
                $img_stmt->execute();
            }
            $conn->commit();
            header("Location: book.php?message=New book added successfully!");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Error saving to database: " . $e->getMessage();
        }
    }
}
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="page-title">Add New Book</h1>
    <a href="book.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Go Back</a>
</div>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p class="mb-0"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <form action="book_add.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Book Title (*)</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author (*)</label>
                        <input type="text" name="author" id="author" class="form-control" value="<?php echo htmlspecialchars($author); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="8"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (*)</label>
                        <input type="number" name="price" id="price" class="form-control" step="1000" value="<?php echo htmlspecialchars($price); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock Quantity (*)</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="<?php echo htmlspecialchars($stock); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Select a category --</option>
                            <?php while($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Save Book</button>
            </div>
        </form>
    </div>
</div>
<?php
echo '</main>';
echo '</div>';
$conn->close();
?>