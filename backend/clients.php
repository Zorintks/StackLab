<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Configurações do banco
$host = "localhost";
$user = "root";
$pass = "@Dmsmbrasil2134&@";
$db   = "stacklab";

// Conexão
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Erro de conexão: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

// Função para sanitizar
function sanitize($data){
    return htmlspecialchars(strip_tags($data));
}

// ================= GET =================
if ($method === 'GET') {
    $res = $conn->query("SELECT * FROM clients ORDER BY id DESC");
    $clients = [];
    while($row = $res->fetch_assoc()) $clients[] = $row;
    echo json_encode($clients);
}

// ================= POST =================
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if(empty($data['name']) || empty($data['type']) || empty($data['value']) || empty($data['deadline'])){
        http_response_code(400);
        echo json_encode(["error"=>"Todos os campos são obrigatórios"]);
        exit;
    }

    $name = sanitize($data['name']);
    $type = sanitize($data['type']);
    $value = floatval($data['value']);
    $deadline = sanitize($data['deadline']);

    $stmt = $conn->prepare("INSERT INTO clients (name, type, value, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $type, $value, $deadline);

    if($stmt->execute()){
        echo json_encode(["success"=>true, "id"=>$stmt->insert_id]);
    }else{
        http_response_code(500);
        echo json_encode(["error"=>"Erro ao adicionar cliente"]);
    }

    $stmt->close();
}

// ================= DELETE =================
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if(empty($data['id'])){
        http_response_code(400);
        echo json_encode(["error"=>"ID do cliente é obrigatório"]);
        exit;
    }

    $id = intval($data['id']);
    $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        echo json_encode(["success"=>true]);
    }else{
        http_response_code(500);
        echo json_encode(["error"=>"Erro ao remover cliente"]);
    }

    $stmt->close();
}

else {
    http_response_code(405);
    echo json_encode(["error"=>"Método não permitido"]);
}

$conn->close();
?>
