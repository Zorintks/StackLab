<?php
header('Content-Type: application/json');

// Configurações do banco de dados
$host = "localhost";
$user = "SEU_USUARIO"; // troque pelo seu usuário do MySQL
$pass = "SUA_SENHA";   // troque pela sua senha do MySQL
$db   = "stacklab";

// Conectar ao MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Erro de conexão com o banco: " . $conn->connect_error]));
}

// Captura o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Função para limpar dados
function sanitize($data) {
    return htmlspecialchars(strip_tags($data));
}

// ================= GET =================
// Retorna todos os clientes
if ($method === 'GET') {
    $res = $conn->query("SELECT * FROM clients ORDER BY id DESC");
    $clients = [];
    while($row = $res->fetch_assoc()) {
        $clients[] = $row;
    }
    echo json_encode($clients);
}

// ================= POST =================
// Adiciona um cliente
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Valida campos obrigatórios
    if (empty($data['name']) || empty($data['type']) || empty($data['value']) || empty($data['deadline'])) {
        http_response_code(400);
        echo json_encode(["error" => "Todos os campos são obrigatórios"]);
        exit;
    }

    $name = sanitize($data['name']);
    $type = sanitize($data['type']);
    $value = floatval($data['value']);
    $deadline = sanitize($data['deadline']);

    $stmt = $conn->prepare("INSERT INTO clients (name, type, value, deadline) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $type, $value, $deadline);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao adicionar cliente"]);
    }
    $stmt->close();
}

// ================= DELETE =================
// Remove um cliente
elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID do cliente é obrigatório"]);
        exit;
    }

    $id = intval($data['id']);
    $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao remover cliente"]);
    }
    $stmt->close();
}

// ================= MÉTODO NÃO SUPORTADO =================
else {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"]);
}

// Fechar conexão
$conn->close();
?>
