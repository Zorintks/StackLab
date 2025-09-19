<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - StackLab</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <header>
    <h1>Dashboard de Clientes</h1>
    <a href="logout.php">Sair</a>
  </header>

  <!-- Filtros -->
  <div class="filtros">
    <input type="text" id="nome" placeholder="Buscar por nome">
    <select id="situacao">
      <option value="">Todas as situações</option>
      <option value="em espera">Em espera</option>
      <option value="em projeto">Em projeto</option>
      <option value="concluido">Concluído</option>
    </select>
    <button onclick="carregarClientes()">Filtrar</button>
  </div>

  <!-- Tabela -->
  <table border="1" id="tabelaClientes">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Tipo de Projeto</th>
        <th>Valor</th>
        <th>Prazo</th>
        <th>Situação</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <!-- Formulário -->
  <h2>Novo Cliente</h2>
  <form id="formCliente">
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="text" name="tipo_projeto" placeholder="Tipo de Projeto">
    <input type="number" step="0.01" name="valor" placeholder="Valor">
    <input type="date" name="prazo">
    <select name="situacao">
      <option value="em espera">Em espera</option>
      <option value="em projeto">Em projeto</option>
      <option value="concluido">Concluído</option>
    </select>
    <button type="submit">Cadastrar</button>
  </form>

<script>
const API_URL = "clientes.php";

async function carregarClientes() {
  const nome = document.getElementById("nome").value;
  const situacao = document.getElementById("situacao").value;
  let url = `${API_URL}?nome=${nome}&situacao=${situacao}`;

  let res = await fetch(url);
  let clientes = await res.json();

  let tbody = document.querySelector("#tabelaClientes tbody");
  tbody.innerHTML = "";
  clientes.forEach(c => {
    tbody.innerHTML += `
      <tr>
        <td>${c.nome}</td>
        <td>${c.tipo_projeto}</td>
        <td>R$ ${c.valor}</td>
        <td>${c.prazo}</td>
        <td>${c.situacao}</td>
      </tr>`;
  });
}

document.getElementById("formCliente").addEventListener("submit", async (e) => {
  e.preventDefault();
  let form = new FormData(e.target);
  let data = Object.fromEntries(form.entries());

  await fetch(API_URL, {
    method: "POST",
    body: JSON.stringify(data)
  });
  e.target.reset();
  carregarClientes();
});

carregarClientes();
</script>
</body>
</html>
