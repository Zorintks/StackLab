<?php
// ================= Configuração =================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$host = "localhost";      // seu host, normalmente localhost
$user = "stacklab_user";    // seu usuário MySQL
$pass = "@dmsmb2134@ 23";      // sua senha MySQL
$db   = "stacklab";

// ================= Conexão =================
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erro de conexão: " . $conn->connect_error]);
    exit;
}

// ================= Função sanitize =================
function sanitize($data){
    return htmlspecialchars(strip_tags($data));
}

// ================= Método =================
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    // ================= GET =================
    case 'GET':
        $res = $conn->query("SELECT * FROM clients ORDER BY id DESC");
        if(!$res){
            http_response_code(500);
            echo json_encode(["error"=>"Erro ao buscar clientes: ".$conn->error]);
            exit;
        }
        $clients = [];
        while($row = $res->fetch_assoc()) $clients[] = $row;
        echo json_encode($clients);
        break;

    // ================= POST =================
    case 'POST':
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
            echo json_encode(["error"=>"Erro ao adicionar cliente: ".$stmt->error]);
        }
        $stmt->close();
        break;

    // ================= DELETE =================
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if(empty($data['id'])){
            http_response_code(400);
            echo json_encode(["error"=>"ID do cliente é obrigatório"]);
            exit;
        }
        $id = intval($data['id']);
        $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }else{
            http_response_code(500);
            echo json_encode(["error"=>"Erro ao remover cliente: ".$stmt->error]);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["error"=>"Método não permitido"]);
        break;
}

$conn->close();
?>
