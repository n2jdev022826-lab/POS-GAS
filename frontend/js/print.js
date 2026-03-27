// ===== PRINT FIX FOR PAGINATION =====

let previousRowsPerPage = null;
let previousPage = null;

window.addEventListener("beforeprint", () => {
  previousRowsPerPage = rowsPerPage;
  previousPage = currentPage;

  // show ALL rows
  rowsPerPage = tableData.length;
  currentPage = 1;

  renderTable();

  // insert print date
  const dateElement = document.getElementById("printDate");
  if (dateElement) {
    dateElement.innerText = "Printed: " + new Date().toLocaleString();
  }
});

window.addEventListener("afterprint", () => {
  rowsPerPage = previousRowsPerPage;
  currentPage = previousPage;

  renderTable();
});

function printReceipt() {
  window.print();
}
