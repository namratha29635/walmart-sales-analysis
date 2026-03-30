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

// Query helper
function runQuery($conn, $query) {
    $result = $conn->query($query);
    if (!$result || $result->num_rows == 0) return "<tr><td colspan='99'>No data found</td></tr>";

    $output = "<tr>";
    foreach ($result->fetch_fields() as $field) {
        $output .= "<th>" . htmlspecialchars($field->name) . "</th>";
    }
    $output .= "</tr>";

    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        foreach ($row as $val) {
            $output .= "<td>" . htmlspecialchars($val) . "</td>";
        }
        $output .= "</tr>";
    }

    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Walmart Sales Dashboard</title>
    <style>
        /* same styles */
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        header { background: #007BFF; color: white; padding: 20px 40px; text-align: center; font-size: 24px; font-weight: bold; }
        .container { max-width: 1100px; margin: 30px auto; padding: 20px; }
        .card { background: white; padding: 20px; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f1f1f1; text-align: left; }
        .section-title { border-left: 5px solid #007BFF; padding-left: 10px; margin-bottom: 15px; }
        a { display: inline-block; margin-bottom: 20px; color: #28a745; text-decoration: none; }
    </style>
</head>
<body>

<header>📊 Walmart Sales Analysis Dashboard</header>
<div class="container">
    <a href="add_sale.php">➕ Add New Sale</a>

    <?php
    $sections = [
        "Total Sales by Store" => "
            SELECT Store, SUM(Weekly_Sales) AS Total_Sales
            FROM walmart_sales
            GROUP BY Store
            ORDER BY Total_Sales DESC",
        "Holiday vs Non-Holiday Sales" => "
            SELECT CASE Holiday_Flag WHEN 1 THEN 'Holiday' ELSE 'Non-Holiday' END AS Type,
            ROUND(AVG(Weekly_Sales), 2) AS Average_Sales
            FROM walmart_sales
            GROUP BY Holiday_Flag",
        "Highest Weekly Sale" => "
            SELECT Store, Date, Weekly_Sales
            FROM walmart_sales
            ORDER BY Weekly_Sales DESC LIMIT 1",
        "Lowest Weekly Sale" => "
            SELECT Store, Date, Weekly_Sales
            FROM walmart_sales
            ORDER BY Weekly_Sales ASC LIMIT 1",
        "Monthly Sales Trend" => "
            SELECT MONTH(Date) AS Month, ROUND(SUM(Weekly_Sales), 2) AS Monthly_Sales
            FROM walmart_sales
            GROUP BY MONTH(Date)
            ORDER BY Month",
        "Best Week per Store" => "
            SELECT w1.Store, w1.Date, w1.Weekly_Sales
            FROM walmart_sales w1
            JOIN (
                SELECT Store, MAX(Weekly_Sales) AS Max_Sales
                FROM walmart_sales
                GROUP BY Store
            ) w2 ON w1.Store = w2.Store AND w1.Weekly_Sales = w2.Max_Sales",
        "Above Average Sales Stores" => "
            SELECT DISTINCT Store
            FROM walmart_sales
            WHERE Weekly_Sales > (SELECT AVG(Weekly_Sales) FROM walmart_sales)",
        "Join with Store Names (if 'stores' table exists)" => "
            SELECT s.Store_Name, ws.Date, ws.Weekly_Sales
            FROM walmart_sales ws
            JOIN stores s ON ws.Store = s.Store_ID
            ORDER BY ws.Weekly_Sales DESC LIMIT 10"
    ];

    foreach ($sections as $title => $sql) {
        echo "<div class='card'><h2 class='section-title'>$title</h2><table>" . runQuery($conn, $sql) . "</table></div>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
