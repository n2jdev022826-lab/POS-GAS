let searchQuery = "";

// LIVE SEARCH
document.getElementById("searchInput").addEventListener("input", function () {

    searchQuery = this.value.toLowerCase();

    displayUsers();

});