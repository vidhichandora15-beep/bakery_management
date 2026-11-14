<?php

// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database="bakery_db";

// Connect to MySQL
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("MySQL Connection Failed: " . mysqli_connect_error());
}


// Create Products table
$sql1 = "CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    description TEXT
)";

// Create Production table
$sql2 = "CREATE TABLE IF NOT EXISTS production (
    production_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    batch_number VARCHAR(50) UNIQUE NOT NULL,
    production_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    quantity_produced INT NOT NULL,
    FOREIGN KEY (product_id)
    REFERENCES products(product_id)
)";

// Create Stock table
$sql3 = "CREATE TABLE IF NOT EXISTS stock (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    production_id INT UNIQUE NOT NULL,
    quantity_available INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (production_id)
    REFERENCES production(production_id)
)";

// Execute queries
if (mysqli_query($conn, $sql1) &&
    mysqli_query($conn, $sql2) &&
    mysqli_query($conn, $sql3)) {

    echo "All 3 tables created successfully!<br>";
} else {
    echo "Table Error: " . mysqli_error($conn);
}

echo "Bakery Database Setup Completed!";

mysqli_close($conn);

?>