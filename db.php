<?php
session_start();

$host = 'localhost';
$db   = 'stacklab';
$user = 'root';
$pass = ''; // sua senha do MySQL
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit(json_encode(['status'=>500,'message'=>'Erro ao conectar com o banco']));
}
?>
