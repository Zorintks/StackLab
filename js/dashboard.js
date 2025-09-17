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
document.getElementById("logoutBtn").addEventListener("click", async e => {
  e.preventDefault();
  await fetch("backend/logout.php");
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
const clientTable = document.getElementById("clientTable");
const addClientBtn = document.getElementById("addClientBtn");

async function fetchClients() {
  const res = await fetch("backend/clients.php");
  const clients = await res.json();
  renderClients(clients);
  document.getElementById("total-clients").textContent = clients.length;
}

function renderClients(clients) {
  clientTable.innerHTML = "";
  clients.forEach(client => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${client.name}</td>
      <td>${client.type || '-'}</td>
      <td>R$ ${parseFloat(client.value || 0).toFixed(2)}</td>
      <td>${client.deadline || '-'}</td>
      <td><button class="deleteBtn">Excluir</button></td>
    `;
    row.querySelector(".deleteBtn").addEventListener("click", async () => {
      await fetch("backend/clients.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: client.id })
      });
      showToast("Cliente removido!");
      fetchClients();
    });
    clientTable.appendChild(row);
  });
}

addClientBtn.addEventListener("click", async () => {
  const name = document.getElementById("clientName").value.trim();
  const type = document.getElementById("projectType").value.trim();
  const value = document.getElementById("projectValue").value.trim();
  const deadline = document.getElementById("projectDeadline").value.trim();

  if (!name || !type || !value || !deadline) {
    showToast("Preencha todos os campos!");
    return;
  }

  await fetch("backend/clients.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, type, value, deadline })
  });

  showToast("Cliente adicionado com sucesso!");
  document.getElementById("clientName").value = "";
  document.getElementById("projectType").value = "";
  document.getElementById("projectValue").value = "";
  document.getElementById("projectDeadline").value = "";

  fetchClients();
});

// ================= Projetos =================
const projectList = document.getElementById("projectList");
document.getElementById("projectForm")?.addEventListener("submit", async e => {
  e.preventDefault();
  const title = e.target.title.value.trim();
  const desc = e.target.desc.value.trim();
  if (!title) return showToast("Preencha o título do projeto");

  await fetch("backend/projects.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, description: desc })
  });
  showToast("Projeto criado!");
  e.target.reset();
  fetchProjects();
});

async function fetchProjects() {
  const res = await fetch("backend/projects.php");
  const projects = await res.json();
  projectList.innerHTML = "";
  projects.forEach(p => {
    const li = document.createElement("li");
    li.textContent = `${p.title} (${p.status})`;
    projectList.appendChild(li);
  });
}

// ================= Finanças =================
document.getElementById("financeForm")?.addEventListener("submit", async e => {
  e.preventDefault();
  const title = e.target.title.value.trim();
  const value = parseFloat(e.target.value.value);
  const type = e.target.type.value;
  if (!title || isNaN(value)) return showToast("Preencha todos os campos");

  await fetch("backend/finances.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, value, type })
  });

  showToast("Registro financeiro adicionado!");
  e.target.reset();
  fetchFinances();
});

async function fetchFinances() {
  const res = await fetch("backend/finances.php");
  const finances = await res.json();
  let income = 0, expense = 0;
  finances.forEach(f => f.type === "entrada" ? income += parseFloat(f.value) : expense += parseFloat(f.value));
  document.getElementById("total-income").textContent = `R$ ${income.toFixed(2)}`;
  document.getElementById("total-expenses").textContent = `R$ ${expense.toFixed(2)}`;
  document.getElementById("balance").textContent = `R$ ${(income - expense).toFixed(2)}`;
}

// ================= Alterar Senha =================
document.getElementById("passwordForm")?.addEventListener("submit", async e => {
  e.preventDefault();
  const oldPass = e.target.old.value.trim();
  const newPass = e.target.new.value.trim();

  const res = await fetch("backend/change_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ old: oldPass, new: newPass })
  });
  const data = await res.json();
  showToast(data.message);
  e.target.reset();
});

// ================= Inicialização =================
fetchClients();
fetchProjects();
fetchFinances();
