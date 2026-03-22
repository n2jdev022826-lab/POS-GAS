
// PAGINATION STATE
let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// MAIN DISPLAY FUNCTION
function displaySuppliers() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    // FILTER (uses searchQuery from search.js)
    const filteredSuppliers = suppliers.filter(supplier => {
        return (
            (supplier.supplier_name && supplier.supplier_name.toLowerCase().includes(searchQuery)) ||
            (supplier.contact_name && supplier.contact_name.toLowerCase().includes(searchQuery)) ||
            (supplier.phone && supplier.phone.toLowerCase().includes(searchQuery))
        );
    });

    // PAGINATION
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedSuppliers = filteredSuppliers.slice(start, end);

    // RENDER TABLE
    paginatedSuppliers.forEach(supplier => {

        const row = `
<tr>
<td>${supplier.supplier_code}</td>
<td>${supplier.supplier_name}</td>
<td>${supplier.contact_name || ""}</td>
<td>${supplier.phone || ""}</td>
<td>${supplier.email || ""}</td>
<td>${supplier.address || ""}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editSupplier('${supplier.supplier_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteSupplier('${supplier.supplier_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
`;

        tableBody.innerHTML += row;
    });

    // PAGE INFO
    const totalPages = Math.max(1, Math.ceil(filteredSuppliers.length / rowsPerPage));
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
}

// NEXT PAGE
function nextPage() {
    const filteredSuppliers = suppliers.filter(supplier => {
        return (
            (supplier.supplier_name && supplier.supplier_name.toLowerCase().includes(searchQuery)) ||
            (supplier.contact_name && supplier.contact_name.toLowerCase().includes(searchQuery)) ||
            (supplier.phone && supplier.phone.toLowerCase().includes(searchQuery))
        );
    });

    const totalPages = Math.ceil(filteredSuppliers.length / rowsPerPage);

    if (currentPage < totalPages) {
        currentPage++;
        displaySuppliers();
    }
}

// PREV PAGE
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displaySuppliers();
    }
}

// ROWS PER PAGE CHANGE
document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    displaySuppliers();
});

// BUTTON EVENTS
document.querySelector(".pagination button:first-child").addEventListener("click", prevPage);
document.querySelector(".pagination button:last-child").addEventListener("click", nextPage);

// INITIAL LOAD
displaySuppliers();