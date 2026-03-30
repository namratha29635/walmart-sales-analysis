<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "salesanalysis";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$insert_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store = $_POST['store'];
    $date = $_POST['date'];
    $weekly_sales = $_POST['weekly_sales'];
    $holiday_flag = $_POST['holiday_flag'];
    $temperature = $_POST['temperature'];
    $fuel_price = $_POST['fuel_price'];
    $cpi = $_POST['cpi'];
    $unemployment = $_POST['unemployment'];

    $stmt = $conn->prepare("INSERT INTO walmart_sales 
        (Store, Date, Weekly_Sales, Holiday_Flag, Temperature, Fuel_Price, CPI, Unemployment) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdiiddd", $store, $date, $weekly_sales, $holiday_flag, $temperature, $fuel_price, $cpi, $unemployment);

    if ($stmt->execute()) {
        $insert_msg = "✅ Sales record added!";
    } else {
        $insert_msg = "❌ Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Sale</title>
    <style>
        /* same styles you already have */
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        header { background: #28a745; color: white; padding: 20px 40px; text-align: center; font-size: 24px; font-weight: bold; }
        .container { max-width: 700px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.05); }
        input[type="text"], input[type="number"], input[type="date"] { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { background: #28a745; color: white; padding: 10px 25px; border: none; border-radius: 4px; cursor: pointer; }
        input[type="submit"]:hover { background: #218838; }
        .success { color: green; font-weight: bold; }
        a { display: block; margin-top: 20px; color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>

<header>➕ Add New Sales Record</header>
<div class="container">
    <?php if ($insert_msg): ?><p class="success"><?= $insert_msg ?></p><?php endif; ?>
    <form method="post">
        <label>Store:</label>
        <input type="number" name="store" required>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Weekly Sales:</label>
        <input type="number" step="0.01" name="weekly_sales" required>

        <label>Holiday Flag (1 = Holiday, 0 = Normal):</label>
        <input type="number" name="holiday_flag" min="0" max="1" required>

        <label>Temperature:</label>
        <input type="number" step="0.01" name="temperature" required>

        <label>Fuel Price:</label>
        <input type="number" step="0.01" name="fuel_price" required>

        <label>CPI:</label>
        <input type="number" step="0.01" name="cpi" required>

        <label>Unemployment:</label>
        <input type="number" step="0.01" name="unemployment" required>

        <input type="submit" value="Add Sale">
    </form>

    <a href="dashboard.php">⬅️ Go to Dashboard</a>
</div>

</body>
</html>
