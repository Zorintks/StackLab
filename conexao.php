<?php
$host = "caboose.proxy.rlwy.net"; // Host do Railway
$port = "43520"; // Porta do Railway
$user = "root"; 
$pass = "TMMtamfneumrKgJspXvkrYyjXsmsQbYt";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
