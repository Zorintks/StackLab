<?php
session_start();
if (!isset($_SESSION['logado'])) {
    http_response_code(403);
    echo json_encode(["error" => "Não autorizado"]);
    exit;
}

header("Content-Type: application/json");
include "conexao.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $filtros = [];
    $params = [];

    if (!empty($_GET['nome'])) {
        $filtros[] = "nome LIKE ?";
        $params[] = "%".$_GET['nome']."%";
    }
    if (!empty($_GET['situacao'])) {
        $filtros[] = "situacao = ?";
        $params[] = $_GET['situacao'];
    }

    $sql = "SELECT * FROM clientes";
    if ($filtros) {
        $sql .= " WHERE " . implode(" AND ", $filtros);
    }

    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $sql = "INSERT INTO clientes (nome, tipo_projeto, valor, prazo, situacao) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $data['nome'], $data['tipo_projeto'], $data['valor'], $data['prazo'], $data['situacao']);
    $stmt->execute();
    echo json_encode(["status" => "ok", "id" => $conn->insert_id]);
}
?>
