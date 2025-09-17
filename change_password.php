<?php
require 'db.php';
if(!isset($_SESSION['admin_id'])){
    echo json_encode(['status'=>403,'message'=>'Acesso negado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$old = $data['old'] ?? '';
$new = $data['new'] ?? '';

$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$user = $stmt->fetch();

if($user['password'] !== $old){
    echo json_encode(['status'=>400,'message'=>'Senha antiga incorreta']);
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$new, $_SESSION['admin_id']]);
echo json_encode(['status'=>200,'message'=>'Senha alterada com sucesso']);
