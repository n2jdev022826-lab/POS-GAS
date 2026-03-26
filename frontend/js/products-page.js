let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value) || 10;

let searchQuery = "";
let currentFilter = "all";

// ================= SEARCH =================
document.getElementById("searchInput").addEventListener("input", function () {
    searchQuery = this.value.toLowerCase();
    currentPage = 1;
    displayProducts();
});

// ================= FILTER =================
function filterProducts(type) {
    currentFilter = type;
    currentPage = 1;

    document.querySelectorAll(".stat-card").forEach(card => {
        card.classList.remove("active");
    });

    if (event && event.currentTarget) {
        event.currentTarget.classList.add("active");
    }

    displayProducts();
}

// ================= PAGINATION =================
function changeLimit() {
    rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);
    currentPage = 1;
    displayProducts();
}

function nextPage() {
    currentPage++;
    displayProducts();
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayProducts();
    }
}

// ================= DISPLAY =================
function displayProducts() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    if (!products || products.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="12">No products found</td></tr>`;
        return;
    }

    const today = new Date();

    const filtered = products.filter(p => {

        const matchesSearch =
            (p.product_code && p.product_code.toLowerCase().includes(searchQuery)) ||
            (p.name && p.name.toLowerCase().includes(searchQuery)) ||
            (p.category && p.category.toLowerCase().includes(searchQuery));

        const qtyLeft = parseInt(p.quantity_left || 0);
        const expiry = p.expiry_date ? new Date(p.expiry_date) : null;

        let matchesFilter = true;

        if (currentFilter === "lowstock") {
            matchesFilter = qtyLeft < 10;
        }

        if (currentFilter === "expiring") {
            if (expiry) {
                const diffDays = (expiry - today) / (1000 * 60 * 60 * 24);
                matchesFilter = diffDays <= 7;
            } else {
                matchesFilter = false;
            }
        }

        return matchesSearch && matchesFilter;
    });

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);

    paginated.forEach(product => {

        const row = `
<tr>
<td>
    <img src="/POS-GAS/frontend/assets/uploads/products/${product.image || 'default.png'}" 
         class="user-img">
</td>

<td>${product.product_code || ''}</td>
<td>${product.name || ''}</td>
<td>${product.category || '—'}</td>
<td>${product.supplier || '—'}</td>
<td>${product.created_at || ''}</td>
<td>${product.expiry_date || ''}</td>
<td>${product.original_price || 0}</td>
<td>${product.selling_price || 0}</td>
<td>${product.quantity || 0}</td>
<td>${product.quantity_left || 0}</td>
<td>${(product.original_price * (product.quantity || 0)).toFixed(2)}</td>
<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editProduct('${product.product_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteProduct('${product.product_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
`;

        tableBody.innerHTML += row;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / rowsPerPage));

    if (currentPage > totalPages) currentPage = totalPages;

    document.getElementById("pageInfo").innerText =
        `Page ${currentPage} of ${totalPages}`;
}

// ================= STATS =================
function updateStats() {

    const today = new Date();

    let expiring = 0;
    let lowstock = 0;

    products.forEach(p => {

        const qtyLeft = parseInt(p.quantity_left || 0);
        const expiry = p.expiry_date ? new Date(p.expiry_date) : null;

        if (qtyLeft < 10) lowstock++;

        if (expiry) {
            const diffDays = (expiry - today) / (1000 * 60 * 60 * 24);
            if (diffDays <= 7) expiring++;
        }
    });

    document.getElementById("expiringCount").innerText = expiring;
    document.getElementById("lowStockCount").innerText = lowstock;
    document.getElementById("totalProducts").innerText = products.length;
}

// ================= INIT =================
updateStats();
displayProducts();