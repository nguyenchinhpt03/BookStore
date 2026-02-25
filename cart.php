<?php
require_once 'config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

function sync_cart_to_db($conn, $user_id, $cart) {
    if (!$user_id) return; 

    $conn->begin_transaction();
    try {
        $delete_stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        if (!empty($cart)) {
            $insert_stmt = $conn->prepare("INSERT INTO cart_items (user_id, book_id, quantity) VALUES (?, ?, ?)");
            foreach ($cart as $book_id => $quantity) {
                if ($quantity > 0) {
                    $insert_stmt->bind_param("iii", $user_id, $book_id, $quantity);
                    $insert_stmt->execute();
                }
            }
            $insert_stmt->close();
        }
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
    }
}

function get_cart_products($conn, $cart) {
    if (empty($cart)) return [];
    $product_ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $sql = "SELECT b.id, b.title, b.price, b.stock, i.image_path FROM books b LEFT JOIN book_images i ON b.id = i.book_id AND i.is_cover = 1 WHERE b.id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
    return $products;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if ($book_id > 0 && $quantity > 0) {
            $_SESSION['cart'][$book_id] = (isset($_SESSION['cart'][$book_id])) ? $_SESSION['cart'][$book_id] + $quantity : $quantity;
            sync_cart_to_db($conn, $user_id, $_SESSION['cart']);
        }
        header('Location: cart.php?message=Product has been added to cart!');
        exit();
    }

    if ($action === 'update') {
        if (isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $id => $qty) {
                $_SESSION['cart'][(int)$id] = max(1, (int)$qty);
            }
            sync_cart_to_db($conn, $user_id, $_SESSION['cart']);
        }
        header('Location: cart.php');
        exit();
    }

    if ($action === 'remove') {
        $book_id_to_remove = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
        if ($book_id_to_remove > 0) {
            unset($_SESSION['cart'][$book_id_to_remove]);
            sync_cart_to_db($conn, $user_id, $_SESSION['cart']);
        }
        header('Location: cart.php?message=Product removed!');
        exit();
    }
}

$cart_products = get_cart_products($conn, $_SESSION['cart']);
$item_count = count($_SESSION['cart']);

include 'header.php';
?>

<div class="page-container cart-page">
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success" style="width: 100%;"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="cart-empty" style="text-align: center; background: #fff; padding: 40px; border-radius: 8px;">
            <h1>Shopping Cart</h1>
            <p>Your cart is empty.</p>
            <a href="index.php" class="btn">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-layout">
            <form id="cart-form" action="cart.php" method="post">
                <input type="hidden" name="action" value="update">
                <div class="cart-main">
                    <div class="cart-header-section">
                        <h1>Shopping Cart</h1>
                        <span>(<?php echo $item_count; ?> products)</span>
                    </div>
                    <div class="cart-header-cols">
                        <div class="col-product">Product</div>
                        <div class="col-price">Unit price</div>
                        <div class="col-quantity">Quantity</div>
                        <div class="col-subtotal">Subtotal</div>
                    </div>

                    <div class="cart-items-list">
                        <?php $total_price = 0; ?>
                        <?php foreach ($_SESSION['cart'] as $book_id => $quantity): ?>
                            <?php
                            if (!isset($cart_products[$book_id])) continue;
                            $product = $cart_products[$book_id];
                            $subtotal = $product['price'] * $quantity;
                            $total_price += $subtotal;
                            ?>
                            <div class="cart-item" data-price="<?php echo $product['price']; ?>" data-stock="<?php echo $product['stock']; ?>">
                                <div class="item-product">
                                    <img src="admin/uploads/<?php echo !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'default_cover.jpg'; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                                    <div class="info">
                                        <a class="title" href="book.php?id=<?php echo $book_id; ?>"><?php echo htmlspecialchars($product['title']); ?></a>
                                    </div>
                                </div>
                                <div class="item-price"><span><?php echo number_format($product['price'], 2); ?>$</span></div>
                                <div class="item-quantity">
                                    <div class="quantity-selector">
                                        <button type="button" class="btn-qty minus" aria-label="Decrease quantity">-</button>
                                        <input type="text" name="quantities[<?php echo $book_id; ?>]" value="<?php echo $quantity; ?>" class="quantity-input" readonly>
                                        <button type="button" class="btn-qty plus" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>
                                <div class="item-subtotal"><span class="subtotal-value"><?php echo number_format($subtotal, 2); ?>$</span></div>
                                <div class="item-remove">
                                    <a href="#" class="remove-btn" data-book-id="<?php echo $book_id; ?>" title="Remove item">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
            
            <div class="cart-summary-wrapper">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal-sidebar"><?php echo number_format($total_price, 2); ?>$</span>
                    </div>
                    <hr>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="grand-total-sidebar"><?php echo number_format($total_price, 2); ?>$</span>
                    </div>
                    <small class="vat-notice">(VAT included if applicable)</small>
                    <a href="checkout.php" class="btn btn-checkout" style="text-align: center;">Proceed to Checkout</a>
                </div>
            </div>
        </div>

        <form id="remove-form" action="cart.php" method="post" style="display: none;">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="book_id" id="remove-book-id">
        </form>
    <?php endif; ?>
</div>

<div id="delete-confirm-box" class="message-box-overlay">
    <div class="message-box">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to remove this item from your cart?</p>
        <div class="message-box-buttons">
            <button id="cancel-delete" class="btn btn-cancel">Cancel</button>
            <button id="confirm-delete" class="btn btn-confirm">Remove</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartForm = document.getElementById('cart-form');
    let debounceTimer;

    function formatCurrency(value) {
        return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    }

    function updateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.dataset.price);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            const subtotal = price * quantity;
            grandTotal += subtotal;
            item.querySelector('.subtotal-value').textContent = formatCurrency(subtotal);
        });

        document.getElementById('subtotal-sidebar').textContent = formatCurrency(grandTotal);
        document.getElementById('grand-total-sidebar').textContent = formatCurrency(grandTotal);
    }

    function handleQuantityChange() {
        updateTotals();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const formData = new FormData(cartForm);
            fetch('cart.php', {
                method: 'POST',
                body: formData
            }).catch(error => console.error('Error updating cart:', error));
        }, 1500);
    }

    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const minusBtn = selector.querySelector('.minus');
        const plusBtn = selector.querySelector('.plus');
        const input = selector.querySelector('.quantity-input');
        const stock = parseInt(selector.closest('.cart-item').dataset.stock);

        minusBtn.addEventListener('click', function() {
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                handleQuantityChange();
            }
        });

        plusBtn.addEventListener('click', function() {
            if (parseInt(input.value) < stock) {
                input.value = parseInt(input.value) + 1;
                handleQuantityChange();
            } else {
                alert('The requested quantity is not available in stock!');
            }
        });
    });

    const deleteConfirmBox = document.getElementById('delete-confirm-box');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const removeForm = document.getElementById('remove-form');
    const removeBookIdInput = document.getElementById('remove-book-id');

    function closeDeleteConfirmBox() {
        deleteConfirmBox.classList.remove('show');
        setTimeout(() => { deleteConfirmBox.style.display = 'none'; }, 300);
    }

    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookId = this.dataset.bookId;
            removeBookIdInput.value = bookId;
            
            deleteConfirmBox.style.display = 'flex';
            setTimeout(() => { deleteConfirmBox.classList.add('show'); }, 10);
        });
    });

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeDeleteConfirmBox);
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            removeForm.submit();
        });
    }

    if (deleteConfirmBox) {
        deleteConfirmBox.addEventListener('click', function(event) {
            if (event.target === deleteConfirmBox) {
                closeDeleteConfirmBox();
            }
        });
    }
});
</script>