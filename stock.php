
<?php
include "db.php";

// Get stock details
$sql = "SELECT stock.stock_id,
products.product_name,
production.batch_number,
production.expiry_date,
stock.quantity_available
FROM stock
JOIN production
ON stock.production_id = production.production_id
JOIN products
ON production.product_id = products.product_id";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Management</title>
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

    <h1>Stock Management</h1>
    <p>View available bakery stock.</p>

    <table border="1" cellpadding="10">

        <tr>
            <th>Stock ID</th>
            <th>Product Name</th>
            <th>Batch Number</th>
            <th>Expiry Date</th>
            <th>Available Quantity</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
        ?>

        <tr>
            <td><?php echo $row['stock_id']; ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td><?php echo htmlspecialchars($row['batch_number']); ?></td>
            <td><?php echo $row['expiry_date']; ?></td>
            <td><?php echo $row['quantity_available']; ?></td>
        </tr>

        <?php
            }
        } else {
            echo "<tr><td colspan='5'>No stock available</td></tr>";
        }
        ?>

    </table>

    <br>

    <a href="index.php">Back to Dashboard</a>

</div>

</body>
</html>
```
