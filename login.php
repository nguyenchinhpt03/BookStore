<?php
require_once 'config/db.php';
$errors = [];
$email = '';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $errors[] = "Please enter both email and password.";
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, fullname, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $session_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                $db_cart = [];
                $cart_stmt = $conn->prepare("SELECT book_id, quantity FROM cart_items WHERE user_id = ?");
                $cart_stmt->bind_param("i", $user['id']);
                $cart_stmt->execute();
                $cart_result = $cart_stmt->get_result();
                while ($item = $cart_result->fetch_assoc()) {
                    $db_cart[$item['book_id']] = $item['quantity'];
                }
                $cart_stmt->close();
                $merged_cart = $session_cart;
                foreach ($db_cart as $book_id => $quantity) {
                    if (isset($merged_cart[$book_id])) {
                        $merged_cart[$book_id] += $quantity;
                    } else {
                        $merged_cart[$book_id] = $quantity;
                    }
                }
                $conn->begin_transaction();
                try {
                    $delete_stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
                    $delete_stmt->bind_param("i", $user['id']);
                    $delete_stmt->execute();
                    $delete_stmt->close();

                    if (!empty($merged_cart)) {
                        $insert_stmt = $conn->prepare("INSERT INTO cart_items (user_id, book_id, quantity) VALUES (?, ?, ?)");
                        foreach ($merged_cart as $book_id => $quantity) {
                            $insert_stmt->bind_param("iii", $user['id'], $book_id, $quantity);
                            $insert_stmt->execute();
                        }
                        $insert_stmt->close();
                    }
                    $conn->commit();
                } catch (Exception $e) {
                    $conn->rollback();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['cart'] = $merged_cart;
                if ($user['role'] == 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $errors[] = "Incorrect email or password.";
            }
        } else {
            $errors[] = "Incorrect email or password.";
        }
        $stmt->close();
    }
    $conn->close();
}

include 'header.php';
?>

<h2>Login</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['registration_success'])): ?>
    <div class="alert alert-success">
        <p>Registration successful! Please log in.</p>
    </div>
<?php endif; ?>

<form action="login.php" method="post">
    <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>

<p class="form-switch">
    Don’t have an account? <a href="register.php">Register</a>
</p>
