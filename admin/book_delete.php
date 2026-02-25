<?php
include '_auth.php';
require_once '../config/db.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: book.php');
    exit();
}
$book_id = (int)$_GET['id'];
$conn->begin_transaction();
try {
    $delete_cart_items_query = $conn->prepare("DELETE FROM cart_items WHERE book_id = ?");
    $delete_cart_items_query->bind_param("i", $book_id);
    $delete_cart_items_query->execute();
    $delete_cart_items_query->close();
    $delete_order_items_query = $conn->prepare("DELETE FROM order_items WHERE book_id = ?");
    $delete_order_items_query->bind_param("i", $book_id);
    $delete_order_items_query->execute();
    $delete_order_items_query->close();
    $image_query = $conn->prepare("SELECT image_path FROM book_images WHERE book_id = ?");
    $image_query->bind_param("i", $book_id);
    $image_query->execute();
    $result = $image_query->get_result();
    while ($row = $result->fetch_assoc()) {
        $filename = $row['image_path'];
        if (!empty($filename)) {
            $file_path = 'uploads/' . $filename;
            if (file_exists($file_path) && is_file($file_path)) {
                unlink($file_path);
            }
        }
    }
    $image_query->close();
    $delete_images_query = $conn->prepare("DELETE FROM book_images WHERE book_id = ?");
    $delete_images_query->bind_param("i", $book_id);
    $delete_images_query->execute();
    $delete_images_query->close();

    $delete_book_query = $conn->prepare("DELETE FROM books WHERE id = ?");
    $delete_book_query->bind_param("i", $book_id);
    $delete_book_query->execute();

    if ($delete_book_query->affected_rows > 0) {
        $conn->commit();
        header("Location: book.php?message=Book and related records deleted successfully!");
    } else {
        $conn->rollback();
        header("Location: book.php?error=Book not found for deletion. It may have been deleted already.");
    }
    $delete_book_query->close();
    exit();

} catch (Exception $e) {
    $conn->rollback();
    header("Location: book.php?error=An error occurred during deletion: " . urlencode($e->getMessage()));
    exit();
}
?>