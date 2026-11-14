<?php
require_once "db.php";

// Count total products
$productQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM products"
);
$productData = mysqli_fetch_assoc($productQuery);
$totalProducts = $productData['total'];

// Count production batches
$productionQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM production"
);
$productionData = mysqli_fetch_assoc($productionQuery);
$totalBatches = $productionData['total'];

// Count available stock
$stockQuery = mysqli_query(
    $conn,
    "SELECT COALESCE(SUM(quantity_available), 0) AS total
     FROM stock"
);
$stockData = mysqli_fetch_assoc($stockQuery);
$totalStock = $stockData['total'];

// Count expired batches with stock
$expiredQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM production p
     JOIN stock s ON p.production_id = s.production_id
     WHERE p.expiry_date < CURDATE()
       AND s.quantity_available > 0"
);
$expiredData = mysqli_fetch_assoc($expiredQuery);
$totalExpired = $expiredData['total'];

// Count batches expiring within 3 days, including today
$soonQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM production p
     JOIN stock s ON p.production_id = s.production_id
     WHERE p.expiry_date BETWEEN CURDATE()
       AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
       AND s.quantity_available > 0"
);
$soonData = mysqli_fetch_assoc($soonQuery);
$totalSoon = $soonData['total'];
?>

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
    <a href="notifications.php"> Notifications🔔 (<?php echo $totalExpired + $totalSoon; ?>)</a>
</div>

<div class="main">

    <header>
        <div>
            <h1>Bakery Dashboard</h1>
            <p>Welcome to your Bakery Production Management System!</p>
        </div>
        <div class="bell">
            <span><?php echo $totalExpired + $totalSoon; ?></span>
        </div>
    </header>

    <div class="cards">

        <div class="card">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts; ?></p>
        </div>

        <div class="card">
            <h3>Production Batches</h3>
            <p><?php echo $totalBatches; ?></p>
        </div>

        <div class="card">
            <h3>Available Stock</h3>
            <p><?php echo $totalStock; ?></p>
        </div>

        <div class="card warning">
            <h3>Expiring Soon</h3>
            <p><?php echo $totalSoon; ?></p>
        </div>

        <div class="card danger">
            <h3>Expired Batches</h3>
            <p><?php echo $totalExpired; ?></p>
        </div>

    </div>

    <section class="welcome">
        <h2>Manage Your Bakery </h2>
        <p>Add bakery products, record daily production, monitor stock
        and get alerts before products expire.</p>
    </section>

    <section class="alerts">
        <h2>Expiry Alert Summary </h2>

        <?php if ($totalExpired > 0): ?>
            <p class="expired">
                <?php echo $totalExpired; ?> batch(es) are expired
                and still have stock.
            </p>
        <?php endif; ?>

        <?php if ($totalSoon > 0): ?>
            <p class="expiring">
                 <?php echo $totalSoon; ?> batch(es) will expire
                within the next 3 days.
            </p>
        <?php endif; ?>

        <?php if ($totalExpired == 0 && $totalSoon == 0): ?>
            <p class="fresh">
                No expired or near-expiry batches found.
            </p>
        <?php endif; ?>
    </section>

</div>

</body>
</html>