<?php
if (!isset($_GET['token'])) {
    die("Invalid request.");
}
$token = $_GET['token'];
?>

<form method="POST" action="update_password.php">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Update Password</button>
</form>
