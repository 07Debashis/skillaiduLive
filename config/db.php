<?php
/**
 * Database connection (PDO)
 * Edit credentials below to match your XAMPP MySQL setup.
 */
$DB_HOST = 'localhost';
$DB_NAME = 'skillaid_skilledu';
$DB_USER = 'skillaid_admin_skilledu';
$DB_PASS = 'HWecT8yfJt?LHkgf';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (\PDOException $e) {
    error_log($e->getMessage());
    if (!isset($_GET['asset'])) {
        die("Connection failed");
    }
    exit();
}



/*catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}*/
