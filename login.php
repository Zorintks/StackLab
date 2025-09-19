<?php
session_start();
include "conexao.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = MD5(?) LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['logado'] = true;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php"); // redireciona pro dashboard
        exit;
    } else {
        echo "❌ E-mail ou senha incorretos!";
    }
}
?>
