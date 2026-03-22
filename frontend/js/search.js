let searchQuery = "";

document.getElementById("searchInput").addEventListener("input", function () {

    searchQuery = this.value.toLowerCase();

    if (typeof currentPage !== "undefined") {
        currentPage = 1;
    }

    if (typeof displaySuppliers === "function") {
        displaySuppliers();
    } else if (typeof displayUsers === "function") {
        displayUsers();
    }

});