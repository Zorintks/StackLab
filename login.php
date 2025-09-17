<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['status'=>400,'message'=>'Preencha todos os campos']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role='admin' LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && $user['password'] === $password) { // Aqui você pode usar password_hash
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_name'] = $user['name'];
    echo json_encode(['status'=>200,'message'=>'Login efetuado com sucesso']);
} else {
    echo json_encode(['status'=>401,'message'=>'Email ou senha incorretos']);
}
?>
