let currentPage = 1;
let rowsPerPage = 10;

function displayPumps() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    const filtered = pumps.filter(p =>
        (p.pump_name || "").toLowerCase().includes(searchQuery) ||
        (p.fuel_name || "").toLowerCase().includes(searchQuery)
    );

    const start = (currentPage - 1) * rowsPerPage;
    const data = filtered.slice(start, start + rowsPerPage);

    data.forEach(p => {

        tableBody.innerHTML += `
<tr>
<td>${p.pump_code}</td>
<td>${p.pump_name}</td>
<td>${p.fuel_name}</td>
<td>${p.status}</td>
<td>${p.created_at}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editPump('${p.pump_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deletePump('${p.pump_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
`;
    });

    document.getElementById("pageInfo").innerText =
        `Page ${currentPage}`;
}

displayPumps();