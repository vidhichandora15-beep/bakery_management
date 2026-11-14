<?php
include "db.php";

$today = date("Y-m-d");
$nextThreeDays = date("Y-m-d", strtotime("+3 days"));

$sql = "SELECT
            products.product_name,
            production.batch_number,
            production.expiry_date,
            production.quantity_produced
        FROM production
        JOIN products
        ON production.product_id = products.product_id
        WHERE production.expiry_date <= '$nextThreeDays'
        ORDER BY production.expiry_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expiry Notifications</title>
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


<h1>🔔 Expiry Notifications</h1>

<p>Expired and soon-to-expire products</p>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Product Name</th>
    <th>Batch Number</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
    <th>Status</th>
</tr>

<?php
if ($result && mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $expiry = $row["expiry_date"];

        if ($expiry < $today) {
            $status = "Expired";
        }
        elseif ($expiry == $today) {
            $status = "Expires Today";
        }
        else {
            $status = "Expiring Soon";
        }
?>

<tr>
    <td>
        <?php echo htmlspecialchars($row["product_name"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row["batch_number"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row["expiry_date"]); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row["quantity_produced"]); ?>
    </td>

    <td>
        <?php echo $status; ?>
    </td>
</tr>

<?php
    }

} else {
?>

<tr>
    <td colspan="5">No expiry notifications found.</td>
</tr>

<?php
}
?>

</table>

<br>

<a href="index.php">Back to Dashboard</a>

</body>
</html>
