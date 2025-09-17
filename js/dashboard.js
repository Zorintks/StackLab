// ================= Menu e Seções =================
document.querySelectorAll(".menu a").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    const target = link.getAttribute("data-section");
    if (!target) return;

    document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
    const section = document.getElementById(target);
    if (section) section.classList.add("active");

    document.getElementById("section-title").textContent = link.textContent;
  });
});

// ================= Logout =================
document.getElementById("logoutBtn").addEventListener("click", e => {
  e.preventDefault();
  alert("Você saiu do painel.");
  window.location.href = "login.html";
});

// ================= Toast =================
const toast = document.getElementById("toast");
function showToast(message) {
  toast.textContent = message;
  toast.classList.add("show");
  setTimeout(() => toast.classList.remove("show"), 3000);
}

// ================= Clientes =================
const addClientBtn = document.getElementById("addClientBtn");
const clientTable = document.getElementById("clientTable");

// Recupera clientes do localStorage
let clients = JSON.parse(localStorage.getItem("clients")) || [];

// Função para atualizar visão geral
function updateOverview() {
  document.getElementById("total-clients").textContent = clients.length;
}

// Função para renderizar tabela de clientes
function renderClients() {
  clientTable.innerHTML = "";
  clients.forEach((client, index) => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${client.name}</td>
      <td>${client.type}</td>
      <td>R$ ${parseFloat(client.value).toFixed(2)}</td>
      <td>${client.deadline}</td>
      <td><button class="deleteBtn">Excluir</button></td>
    `;
    row.querySelector(".deleteBtn").addEventListener("click", () => {
      clients.splice(index, 1);       // remove do array
      localStorage.setItem("clients", JSON.stringify(clients)); // atualiza storage
      showToast("Cliente removido!");
      renderClients();
      updateOverview();
    });
    clientTable.appendChild(row);
  });
}

// Inicializa tabela e visão geral ao carregar a página
renderClients();
updateOverview();

// Adiciona cliente
addClientBtn.addEventListener("click", () => {
  const name = document.getElementById("clientName").value.trim();
  const type = document.getElementById("projectType").value.trim();
  const value = document.getElementById("projectValue").value.trim();
  const deadline = document.getElementById("projectDeadline").value.trim();

  if (!name || !type || !value || !deadline) {
    showToast("Preencha todos os campos!");
    return;
  }

  // Adiciona ao array
  clients.push({ name, type, value, deadline });
  localStorage.setItem("clients", JSON.stringify(clients));

  // Atualiza UI
  renderClients();
  updateOverview();

  // Limpa campos
  document.getElementById("clientName").value = "";
  document.getElementById("projectType").value = "";
  document.getElementById("projectValue").value = "";
  document.getElementById("projectDeadline").value = "";

  showToast("Cliente adicionado com sucesso!");
});

// ================= Projetos (Placeholder) =================
document.getElementById("projectForm")?.addEventListener("submit", e => {
  e.preventDefault();
  showToast("Projeto criado (simulação)!");
});

// ================= Finanças (Placeholder) =================
document.getElementById("financeForm")?.addEventListener("submit", e => {
  e.preventDefault();
  showToast("Registro financeiro adicionado (simulação)!");
});

// ================= Alterar Senha (Placeholder) =================
document.getElementById("passwordForm")?.addEventListener("submit", e => {
  e.preventDefault();
  showToast("Senha alterada (simulação)!");
});
