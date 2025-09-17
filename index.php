<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

// Obter clientes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM clients");
    $clients = [];
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
    echo json_encode($clients);
}

// Adicionar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("INSERT INTO clients (name, project_type, value, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $data['name'], $data['project_type'], $data['value'], $data['deadline']);
    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","msg"=>"Cliente adicionado!"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>$stmt->error]);
    }
}

// Deletar cliente
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    if ($stmt->execute()) {
        echo json_encode(["status"=>"success","msg"=>"Cliente removido!"]);
    } else {
        echo json_encode(["status"=>"error","msg"=>$stmt->error]);
    }
}
?>
