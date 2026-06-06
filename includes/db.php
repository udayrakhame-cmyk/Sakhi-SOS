<?php
// includes/db.php

$host = 'localhost';
$db   = 'sakhisos';
$user = 'postgres';
$pass = 'root'; // Update this if your postgres user has a password
$port = '5432';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For production, log the error rather than displaying it
     die("Database connection failed: " . $e->getMessage() . ". Please ensure PostgreSQL is running and credentials are correct in includes/db.php.");
}
