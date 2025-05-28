<?php
session_start();
require_once "dbconfig.php";

// Redirect if not logged in
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: ragister.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to database
$conn = mysqli_connect(server, user, password, database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sssi", $name, $email, $phone, $user_id);
    mysqli_stmt_execute($update_stmt);

    // Reload to reflect changes
    header("Location: profile.php");
    exit;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
