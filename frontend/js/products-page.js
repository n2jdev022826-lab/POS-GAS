let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

function displayProducts() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    const filtered = products.filter(p =>
        (p.product_code && p.product_code.toLowerCase().includes(searchQuery)) ||
        (p.name && p.name.toLowerCase().includes(searchQuery)) ||
        (p.category && p.category.toLowerCase().includes(searchQuery))
    );

    const start = (currentPage - 1) * rowsPerPage;
    const paginated = filtered.slice(start, start + rowsPerPage);

    paginated.forEach(product => {

 const row = `
<tr>
<td>
    <img src="/POS-GAS/frontend/assets/uploads/products/${product.image || 'default.png'}" 
         class="user-img">
</td>

<td>${product.product_code}</td>
<td>${product.name}</td>
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
</td>
</tr>
`;

        tableBody.innerHTML += row;
    });

    const totalPages = Math.max(1, Math.ceil(filtered.length / rowsPerPage));
    document.getElementById("pageInfo").innerText =
        `Page ${currentPage} of ${totalPages}`;
}

displayProducts();