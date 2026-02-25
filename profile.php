<?php
require_once 'config/db.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT fullname, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    $update_stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ?, address = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $fullname, $phone, $address, $user_id);
    if ($update_stmt->execute()) {
        $_SESSION['fullname'] = $fullname; 
        $message = "Profile updated successfully!";
        $user['fullname'] = $fullname;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $message = "An error occurred. Please try again.";
    }
}
?>

<h2>Personal Information</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<form action="profile.php" method="post">
    <div class="form-group">
        <label>Email (cannot be changed):</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
    </div>
    <div class="form-group">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
    </div>
    <button type="submit" class="btn">Update Profile</button>
</form>
