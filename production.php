<?php
include "db.php";

$msg = "";

// Add production
if (isset($_POST['add'])) {

    $pid = $_POST['product_id'];
    $batch = $_POST['batch'];
    $pdate = $_POST['pdate'];
    $edate = $_POST['edate'];
    $qty = $_POST['qty'];

    if ($edate < $pdate) {
        $msg = "Expiry date must be after production date.";
    } else {

        $sql = "INSERT INTO production
        (product_id, batch_number, production_date,
        expiry_date, quantity_produced)
        VALUES ('$pid', '$batch', '$pdate',
        '$edate', '$qty')";

        if (mysqli_query($conn, $sql)) {

            $id = mysqli_insert_id($conn);

            $stock = "INSERT INTO stock
            (production_id, quantity_available)
            VALUES ('$id', '$qty')";

            mysqli_query($conn, $stock);

            $msg = "Production added successfully!";

        } else {
            $msg = "Error! Batch number may already exist.";
        }
    }
}

$products = mysqli_query($conn, "SELECT * FROM products");

$records = mysqli_query($conn,
"SELECT production.*, products.product_name
FROM production
JOIN products
ON production.product_id = products.product_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Production Management</title>
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
    <a href="notifications.php"> Notifications🔔)</a>
</div>


<div class="main-content">

<h1>Production Management</h1>

<p><?php echo $msg; ?></p>

<h2>Add Production</h2>

<form method="POST">

    <label>Product:</label>
    <select name="product_id" required>
        <option value="">Select Product</option>

        <?php while ($p = mysqli_fetch_assoc($products)) { ?>
            <option value="<?php echo $p['product_id']; ?>">
                <?php echo htmlspecialchars($p['product_name']); ?>
            </option>
        <?php } ?>
    </select>

    <label>Batch Number:</label>
    <input type="text" name="batch" required>

    <label>Production Date:</label>
    <input type="date" name="pdate" required>

    <label>Expiry Date:</label>
    <input type="date" name="edate" required>

    <label>Quantity:</label>
    <input type="number" name="qty" min="1" required>

    <button type="submit" name="add">Add Production</button>

</form>

<h2>Production Records</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Product</th>
    <th>Batch</th>
    <th>Production Date</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($records)) { ?>
<tr>
    <td><?php echo $row['production_id']; ?></td>
    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
    <td><?php echo htmlspecialchars($row['batch_number']); ?></td>
    <td><?php echo $row['production_date']; ?></td>
    <td><?php echo $row['expiry_date']; ?></td>
    <td><?php echo $row['quantity_produced']; ?></td>
</tr>
<?php } ?>

</table>

<a href="index.php">Back to Dashboard</a>

</div>
</body>
</html>
```
