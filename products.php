<?php
require_once "db.php";

$message = "";

if (isset($_POST['delete_product'])) {

    $product_id = (int)$_POST['product_id'];

    $check = mysqli_query($conn,
        "SELECT product_id FROM production
         WHERE product_id = $product_id"
    );

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Cannot delete! Production records exist for this product.');</script>";
    } else {
        $delete = mysqli_query($conn,
            "DELETE FROM products WHERE product_id = $product_id"
        );

        if ($delete) {
            echo "<script>alert('Product deleted successfully!'); window.location='products.php';</script>";
            exit;
        } else {
            echo "<script>alert('Product could not be deleted.');</script>";
        }
    }
}


// Add a new product
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["add_product"])) {

    $name = trim($_POST["product_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $price = $_POST["price"] ?? "";
    $description = trim($_POST["description"] ?? "");

    if ($name === "" || !is_numeric($price) || $price < 0) {
        $message = "Please enter a valid product name and price.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO products
             (product_name, category, price, description)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssds",
            $name,
            $category,
            $price,
            $description
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Product added successfully!";
        } else {
            $message = "Unable to add product.";
        }

        mysqli_stmt_close($stmt);
    }
}

// Delete a product only if it has no production records
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["delete_product"])) {

    $id = (int) $_POST["product_id"];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM products
         WHERE product_id = ?
         AND NOT EXISTS (
             SELECT 1 FROM production
             WHERE production.product_id = products.product_id
         )"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Product not found or has production records.";
    }

    mysqli_stmt_close($stmt);
}

// Fetch all products
$result = mysqli_query(
    $conn,
    "SELECT * FROM products ORDER BY product_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="sidebar">
    <h2 style="color:white">🍪Bakery Hub</h2>
    <a href="index.php">Dashboard</a>
    <a href="products.php" class="active">Products</a>
    <a href="production.php">Production</a>
    <a href="expiry.php">Expiry Management</a>
    <a href="stock.php">Stock</a>
    <a href="reports.php">Reports</a>
    <a href="notifications.php">🔔 Notifications</a>
</div>

<div class="main">
    <h1>Product Management</h1>
    <p>Add and manage your bakery products.</p>

    <?php if ($message !== ""): ?>
        <p style="background:#e2f7e8;padding:12px;margin:15px 0;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <section class="welcome">
        <h2>Add New Product</h2>

        <form method="POST" action="products.php">
            <label>Product Name</label><br>
            <input type="text" name="product_name"
                   placeholder="e.g. Chocolate Cake"
                   maxlength="100" required><br><br>

            <label>Category</label><br>
            <input type="text" name="category"
                   placeholder="e.g. Cake"
                   maxlength="50"><br><br>

            <label>Price (₹)</label><br>
            <input type="number" name="price"
                   min="0" step="0.01"
                   placeholder="e.g. 250"
                   required><br><br>

            <label>Description</label><br>
            <textarea name="description"
                      placeholder="Product details"></textarea><br><br>

            <button type="submit" name="add_product">
                Add Product
            </button>
        </form>
    </section>

    <section class="alerts">
        <h2>All Products</h2>

        <div style="overflow-x:auto;">
            <table border="1" cellpadding="10"
                   cellspacing="0" width="100%">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>

                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= (int)$row["product_id"] ?></td>
                            <td><?= htmlspecialchars($row["product_name"]) ?></td>
                            <td><?= htmlspecialchars($row["category"] ?? "") ?></td>
                            <td>₹<?= number_format((float)$row["price"], 2) ?></td>
                            <td><?= htmlspecialchars($row["description"] ?? "") ?></td>
                            <td>
                                <form method="POST"
                                      action="products.php"
                                      onsubmit="return confirm('Delete this product?');">
                                    <input type="hidden" name="product_id"
                                           value="<?= (int)$row["product_id"] ?>">
                                    <button type="submit"
                                            name="delete_product">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No products added yet.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </section>
</div>

</body>
</html>