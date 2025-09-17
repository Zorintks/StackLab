<?php
// hash_passwords.php  — execute UMA VEZ
$host = 'localhost';
$db = 'stacklab';
$user = 'root';
$pass = '';

$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Conn error");

$sql = "SELECT id, password FROM users";
$res = $conn->query($sql);

while($row = $res->fetch_assoc()){
    $id = $row['id'];
    $plain = $row['password'];

    // somente se já não for hash (verifica comprimento do bcrypt)
    if (strpos($plain, '$2y$') !== 0) {
        $hash = password_hash($plain, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        $stmt->execute();
        echo "Atualizado id $id\n";
    } else {
        echo "Já hash id $id\n";
    }
}
$conn->close();
