// ================= Toast =================
const toast = document.getElementById("toast");
function showToast(message) {
  toast.textContent = message;
  toast.classList.add("show");
  setTimeout(() => toast.classList.remove("show"), 3000);
}

// ================= Atualizar Visão Geral =================
function updateOverview() {
  const totalClients = clientTable.querySelectorAll("tr").length;
  document.getElementById("total-clients").textContent = totalClients;
  // Aqui você pode adicionar updates para projetos e receitas se quiser
}

// ================= Clientes =================
const addClientBtn = document.getElementById("addClientBtn");
const clientTable = document.getElementById("clientTable");

addClientBtn.addEventListener("click", () => {
  const name = document.getElementById("clientName").value.trim();
  const type = document.getElementById("projectType").value.trim();
  const value = document.getElementById("projectValue").value.trim();
  const deadline = document.getElementById("projectDeadline").value.trim();

  if (!name || !type || !value || !deadline) {
    showToast("Preencha todos os campos!");
    return;
  }

  const row = document.createElement("tr");
  row.innerHTML = `
    <td>${name}</td>
    <td>${type}</td>
    <td>R$ ${parseFloat(value).toFixed(2)}</td>
    <td>${deadline}</td>
    <td><button class="deleteBtn">Excluir</button></td>
  `;

  // Botão excluir
  row.querySelector(".deleteBtn").addEventListener("click", () => {
    row.remove();
    showToast("Cliente removido!");
    updateOverview(); // atualiza visão geral após remoção
  });

  clientTable.appendChild(row);

  // Limpar campos
  document.getElementById("clientName").value = "";
  document.getElementById("projectType").value = "";
  document.getElementById("projectValue").value = "";
  document.getElementById("projectDeadline").value = "";

  showToast("Cliente adicionado com sucesso!");
  updateOverview(); // atualiza visão geral após adição
});
document.querySelectorAll(".menu a").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    const target = e.target.getAttribute("data-section");
    if (!target) return;

    document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
    document.getElementById(target).classList.add("active");
    document.getElementById("section-title").textContent = e.target.textContent;
  });
});

// Exemplo de envio de formulário (Clientes)
document.getElementById("clientForm").addEventListener("submit", e => {
  e.preventDefault();
  alert("Cliente cadastrado (simulação)!");
});

// Alterar senha
document.getElementById("passwordForm").addEventListener("submit", e => {
  e.preventDefault();
  alert("Senha alterada (simulação)!");
});


// Seção de Clientes


function showToast(message) {
  toast.textContent = message;
  toast.classList.add("show");
  setTimeout(() => {
    toast.classList.remove("show");
  }, 3000);
}

addClientBtn.addEventListener("click", () => {
  const name = document.getElementById("clientName").value;
  const type = document.getElementById("projectType").value;
  const value = document.getElementById("projectValue").value;
  const deadline = document.getElementById("projectDeadline").value;

  if (!name || !type || !value || !deadline) {
    showToast("Preencha todos os campos!");
    return;
  }

  const row = document.createElement("tr");
  row.innerHTML = `
    <td>${name}</td>
    <td>${type}</td>
    <td>R$ ${parseFloat(value).toFixed(2)}</td>
    <td>${deadline}</td>
    <td><button class="deleteBtn">Excluir</button></td>
  `;

  // Botão excluir
  row.querySelector(".deleteBtn").addEventListener("click", () => {
    row.remove();
    showToast("Cliente removido!");
  });

  clientTable.appendChild(row);

  // Limpar campos
  document.getElementById("clientName").value = "";
  document.getElementById("projectType").value = "";
  document.getElementById("projectValue").value = "";
  document.getElementById("projectDeadline").value = "";

  showToast("Cliente adicionado com sucesso!");
});
