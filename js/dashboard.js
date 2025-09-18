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
  window.location.href = "index.html";
});

// ================= Toast =================
const toast = document.getElementById("toast");
function showToast(message) {
  toast.textContent = message;
  toast.classList.add("show");
  setTimeout(() => toast.classList.remove("show"), 3000);
}

const clientTable = document.getElementById("clientTable");
const clientForm = document.getElementById("clientForm");


// ================= Toast =================
function showToast(msg){
    toast.textContent = msg;
    toast.classList.add("show");
    setTimeout(()=>toast.classList.remove("show"),3000);
}

// ================= Buscar clientes =================
async function fetchClients(){
    try {
        const res = await fetch("backend/clients.php");
        const clients = await res.json();
        renderClients(clients);
        document.getElementById("total-clients").textContent = clients.length;
    } catch(err){
        console.error(err);
        showToast("Erro ao buscar clientes");
    }
}

// ================= Renderizar tabela =================
function renderClients(clients){
    clientTable.innerHTML = "";
    clients.forEach(client => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${client.name}</td>
            <td>${client.type || '-'}</td>
            <td>R$ ${parseFloat(client.value).toFixed(2)}</td>
            <td>${client.deadline || '-'}</td>
            <td><button class="deleteBtn">Excluir</button></td>
        `;
        row.querySelector(".deleteBtn").addEventListener("click", async () => {
            await fetch("backend/clients.php", {
                method: "DELETE",
                headers: { "Content-Type":"application/json" },
                body: JSON.stringify({ id: client.id })
            });
            showToast("Cliente removido!");
            fetchClients();
        });
        clientTable.appendChild(row);
    });
}

// ================= Adicionar cliente =================
clientForm.addEventListener("submit", async e=>{
    e.preventDefault();

    const name = document.getElementById("clientName").value.trim();
    const type = document.getElementById("projectType").value.trim();
    const value = document.getElementById("projectValue").value.trim();
    const deadline = document.getElementById("projectDeadline").value.trim();

    if(!name || !type || !value || !deadline){
        showToast("Preencha todos os campos!");
        return;
    }

    try {
        await fetch("backend/clients.php",{
            method:"POST",
            headers: { "Content-Type":"application/json" },
            body: JSON.stringify({ name,type,value,deadline })
        });

        showToast("Cliente adicionado!");
        clientForm.reset();
        fetchClients();
    } catch(err){
        console.error(err);
        showToast("Erro ao adicionar cliente");
    }
});

// ================= Inicialização =================
fetchClients();
