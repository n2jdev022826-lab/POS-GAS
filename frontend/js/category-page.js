// PAGINATION
let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// DISPLAY
function displayCategories() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    const filtered = categories.filter(c =>
        (c.category_name && c.category_name.toLowerCase().includes(searchQuery))
    );

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);

    paginated.forEach(cat => {

        const row = `
<tr>
<td>${cat.category_code}</td>
<td>${cat.category_name}</td>
<td>${cat.description}</td>
<td>${cat.created_at}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editCategory('${cat.category_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteCategory('${cat.category_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
`;

        tableBody.innerHTML += row;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / rowsPerPage));
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
}

// NEXT
function nextPage() {
    const totalPages = Math.ceil(categories.length / rowsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayCategories();
    }
}

// PREV
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayCategories();
    }
}

// ROW CHANGE
document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    displayCategories();
});

// BUTTONS
document.querySelector(".pagination button:first-child").addEventListener("click", prevPage);
document.querySelector(".pagination button:last-child").addEventListener("click", nextPage);

// INIT
displayCategories();