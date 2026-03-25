
        let currentPage = 1;
        let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

        // ================= DISPLAY =================
        function displayCustomers() {

            const tableBody = document.getElementById("tableBody");
            tableBody.innerHTML = "";

            // ✅ SEARCH
            const search = document.getElementById("searchInput").value.toLowerCase();

            const filteredCustomers = customers.filter(customer =>
                (customer.customer_code && customer.customer_code.toLowerCase().includes(search)) ||
                (customer.customer_name && customer.customer_name.toLowerCase().includes(search)) ||
                (customer.phone && customer.phone.toLowerCase().includes(search)) ||
                (customer.email && customer.email.toLowerCase().includes(search))
            );

            // ✅ PAGINATION
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedCustomers = filteredCustomers.slice(start, end);

            paginatedCustomers.forEach(customer => {

                const row = `
<tr>
<td>${customer.customer_code}</td>
<td>${customer.customer_name}</td>
<td>${customer.phone}</td>
<td>${customer.address}</td>
<td>${customer.email}</td>
<td>${customer.created_at}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick='openEditModal(${JSON.stringify(customer)})'>
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteCustomer('${customer.customer_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
        `;

                tableBody.innerHTML += row;
            });

            const totalPages = Math.max(1, Math.ceil(filteredCustomers.length / rowsPerPage));
            document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
        }

        // ================= PAGINATION =================
        function nextPage() {
            const search = document.getElementById("searchInput").value.toLowerCase();

            const filteredCustomers = customers.filter(customer =>
                (customer.customer_name && customer.customer_name.toLowerCase().includes(search))
            );

            const totalPages = Math.ceil(filteredCustomers.length / rowsPerPage);

            if (currentPage < totalPages) {
                currentPage++;
                displayCustomers();
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayCustomers();
            }
        }

        document.getElementById("rowsPerPage").addEventListener("change", function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            displayCustomers();
        });

        // ================= SEARCH (DEBOUNCE) =================
        let searchTimeout;

        document.getElementById("searchInput").addEventListener("input", function() {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                currentPage = 1;
                displayCustomers();
            }, 300);
        });

        // ================= INIT =================
        displayCustomers();