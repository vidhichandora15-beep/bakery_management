<?php
include "db.php";

$today = date("Y-m-d");
$three_days = date("Y-m-d", strtotime("+3 days"));

// Total products
$result1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$row1 = mysqli_fetch_assoc($result1);

// Total production batches
$result2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM production");
$row2 = mysqli_fetch_assoc($result2);

// Total available stock
$result3 = mysqli_query($conn, "SELECT SUM(quantity_available) AS total FROM stock");
$row3 = mysqli_fetch_assoc($result3);

// Expired batches
$result4 = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM production
WHERE expiry_date < '$today'");
$row4 = mysqli_fetch_assoc($result4);

// Products expiring within 3 days, including today
$result5 = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM production
WHERE expiry_date >= '$today'
AND expiry_date <= '$three_days'");
$row5 = mysqli_fetch_assoc($result5);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bakery Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Management Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="sidebar">
    <h2 style="color:white"> 🍪Bakery Hub</h2>

    <a href="index.php" class="active">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="production.php">Production</a>
    <a href="expiry.php">Expiry Management</a>
    <a href="stock.php">Stock</a>
    <a href="reports.php">Reports</a>
    <a href="notifications.php"> Notifications🔔 </a>
</div>



<div class="main-content">

    <h1>Bakery Reports</h1>
    <p>Summary of bakery products, production and stock.</p>

    <div class="card">
        <h3>Total Products</h3>
        <h2><?php echo $row1['total']; ?></h2>
    </div>

    <div class="card">
        <h3>Total Production Batches</h3>
        <h2><?php echo $row2['total']; ?></h2>
    </div>

    <div class="card">
        <h3>Total Available Stock</h3>
        <h2><?php echo $row3['total'] ?? 0; ?></h2>
    </div>

    <div class="card">
        <h3>Expired Batches</h3>
        <h2><?php echo $row4['total']; ?></h2>
    </div>

    <div class="card">
        <h3>Expiring Within 3 Days</h3>
        <h2><?php echo $row5['total']; ?></h2>
    </div>

    <br>

    <a href="index.php">Back to Dashboard</a>

</div>

</body>
</html>
```
