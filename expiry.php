<?php
include "db.php";

$today = date("Y-m-d");
$three_days = date("Y-m-d", strtotime("+3 days"));

$sql = "SELECT products.product_name,
production.batch_number,
production.expiry_date,
stock.quantity_available
FROM production
JOIN products
ON production.product_id = products.product_id
JOIN stock
ON production.production_id = stock.production_id
WHERE production.expiry_date <= '$three_days'
ORDER BY production.expiry_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expiry Alerts</title>
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
    <a href="notifications.php"> Notifications🔔</a>
</div>



<div class="main-content">

    <h1>Expiry Alerts</h1>

    <p>Check expired and soon-to-expire bakery products.</p>

    <table border="1" cellpadding="10">

        <tr>
            <th>Product Name</th>
            <th>Batch Number</th>
            <th>Expiry Date</th>
            <th>Available Quantity</th>
            <th>Status</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                if ($row['expiry_date'] < $today) {
                    $status = "Expired";
                } elseif ($row['expiry_date'] == $today) {
                    $status = "Expires Today";
                } else {
                    $status = "Expiring Soon";
                }
        ?>

        <tr>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo htmlspecialchars($row['batch_number']); ?></td>
            <td><?php echo $row['expiry_date']; ?></td>
            <td><?php echo $row['quantity_available']; ?></td>
            <td><?php echo $status; ?></td>
        </tr>

        <?php
            }
        } else {
            echo "<tr><td colspan='5'>No expiry alerts today.</td></tr>";
        }
        ?>

    </table>

    <br>

    <a href="index.php">Back to Dashboard</a>

</div>

</body>
</html>
```
