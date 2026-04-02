<?php
session_start();

$secret_key = "indira@123";
$error = "";

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['access_key'])) {
    if ($_POST['access_key'] === $secret_key) {
        $_SESSION['authenticated'] = true;
    } else {
        $error = "Invalid Secret Key. Access Denied.";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Dashboard | Royal Heritage</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4e8d1; padding: 40px; color: #1a1a1b; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #5d001e; }
        h1 { color: #5d001e; }
        .login-form { display: flex; gap: 10px; margin-top: 20px; }
        input[type="password"] { padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #5d001e; color: #d4af37; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .error { color: red; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #5d001e; color: white; }
        .logout { float: right; color: #5d001e; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true): ?>
        <h1>Professor Evaluation Portal</h1>
        <p>Please enter the registration secret key to view live project data.</p>
        <form class="login-form" method="POST">
            <input type="password" name="access_key" placeholder="Enter Secret Key" required>
            <button type="submit">Authenticate</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <?php else: ?>
        <a href="?logout=true" class="logout">Logout</a>
        <h1>Live Reservations Data</h1>
        <p>Reading directly from <strong>bookings.json</strong> (No SQL)</p>
        
        <?php
        $file = 'bookings.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (!empty($data)) {
                echo "<table>";
                echo "<tr><th>Time</th><th>Name</th><th>Email</th><th>Suite</th><th>Check-in</th></tr>";
                foreach (array_reverse($data) as $booking) { // Newest first
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($booking['timestamp'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($booking['name'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($booking['email'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($booking['suite'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($booking['checkin'] ?? '') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No reservations found yet.</p>";
            }
        } else {
            echo "<p>The database file does not exist yet. Submit a form to generate it.</p>";
        }
        ?>
    <?php endif; ?>
</div>

</body>
</html>