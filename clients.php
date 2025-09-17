<?php
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status'=>403,'message'=>'Acesso negado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch($method){
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM clients ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'POST':
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        if(!$name || !$email){
            echo json_encode(['status'=>400,'message'=>'Preencha todos os campos']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO clients (name,email) VALUES (?,?)");
        $stmt->execute([$name,$email]);
        echo json_encode(['status'=>200,'message'=>'Cliente adicionado']);
        break;

    case 'DELETE':
        $id = $data['id'] ?? 0;
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['status'=>200,'message'=>'Cliente removido']);
        break;
}
?>
