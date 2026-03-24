
// PAGINATION STATE
let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// DISPLAY USERS
function displayUsers() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    // FILTER USERS (uses searchQuery from search.js)
    const filteredUsers = users.filter(user => {
        return (
            (user.user_code && user.user_code.toLowerCase().includes(searchQuery)) ||
            (user.fname && user.fname.toLowerCase().includes(searchQuery)) ||
            (user.lname && user.lname.toLowerCase().includes(searchQuery)) ||
            (user.username && user.username.toLowerCase().includes(searchQuery)) ||
            (user.role && user.role.toLowerCase().includes(searchQuery)) ||
            (user.email && user.email.toLowerCase().includes(searchQuery))
        );
    });

    // PAGINATION
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedUsers = filteredUsers.slice(start, end);

    // RENDER TABLE
    paginatedUsers.forEach(user => {

        const row = `
<tr>
<td>
    <img 
        src="/POS-GAS/frontend/assets/uploads/users/${user.image || 'default.jpg'}" 
        class="user-img">
</td>

<td>${user.user_code}</td>
<td>${user.fname} ${user.middlename || ""} ${user.lname}</td>
<td>${user.sex || ""}</td>
<td>${user.username}</td>
<td>${user.role}</td>
<td>${user.address || ""}</td>
<td>${user.phone || ""}</td>
<td>${user.email || ""}</td>
<td>${user.date_of_birth || ""}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editUser('${user.user_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteUser('${user.user_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>

`;

        tableBody.innerHTML += row;
    });

    // PAGE INFO
    const totalPages = Math.max(1, Math.ceil(filteredUsers.length / rowsPerPage));
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
}

// NEXT PAGE
function nextPage() {
    const filteredUsers = users.filter(user => {
        return (
            (user.user_code && user.user_code.toLowerCase().includes(searchQuery)) ||
            (user.fname && user.fname.toLowerCase().includes(searchQuery)) ||
            (user.lname && user.lname.toLowerCase().includes(searchQuery)) ||
            (user.username && user.username.toLowerCase().includes(searchQuery)) ||
            (user.role && user.role.toLowerCase().includes(searchQuery)) ||
            (user.email && user.email.toLowerCase().includes(searchQuery))
        );
    });

    const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);

    if (currentPage < totalPages) {
        currentPage++;
        displayUsers();
    }
}

// PREV PAGE
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayUsers();
    }
}

// ROWS PER PAGE
document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    displayUsers();
});

// BUTTON EVENTS
document.querySelector(".pagination button:first-child").addEventListener("click", prevPage);
document.querySelector(".pagination button:last-child").addEventListener("click", nextPage);

// INITIAL LOAD
displayUsers();