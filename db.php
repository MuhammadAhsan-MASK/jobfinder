<?php
session_start();  // Start the session at the top

// Database connection parameters
$host = 'localhost';  // Use localhost for XAMPP/MySQL server
$dbname = 'jobfinder';  // The name of your database
$username = 'root';  // Default MySQL user for XAMPP
$password = '';  // Default MySQL password for XAMPP is empty
$port = '3307';  // Set the MySQL port to 3307

try {
    // Create PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if the connection fails
    echo "Connection failed: " . $e->getMessage();
}
?>
