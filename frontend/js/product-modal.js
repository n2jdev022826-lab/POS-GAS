// OPEN MODAL
function addProduct() {
  const modal = document.getElementById("addProductModal");

  modal.style.display = "flex";
  document.body.classList.add("modal-open");
}

// CLOSE MODAL
function closeProductModal() {
  const modal = document.getElementById("addProductModal");

  modal.style.display = "none";
  document.body.classList.remove("modal-open");
}

// ADD NEW PRODUCT

document
  .getElementById("addProductForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("/POS-GAS/api/products/create", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        alert(data.message);

        if (data.status === "success") {
          location.reload();
        }
      });
  });

// EDIT PRODUCT

function editProduct(id) {
  const product = products.find((p) => p.product_id === id);

  document.getElementById("edit_product_id").value = product.product_id;
  document.getElementById("edit_product_name").value = product.product_name;
  document.getElementById("edit_generic_name").value = product.generic_name;
  document.getElementById("edit_category").value = product.category;
  document.getElementById("edit_supplier").value = product.supplier;
  document.getElementById("edit_purchase_price").value = product.purchase_price;
  document.getElementById("edit_selling_price").value = product.selling_price;
  document.getElementById("edit_stock_quantity").value = product.stock_quantity;
  document.getElementById("edit_expiry_date").value = product.expiry_date;

  document.getElementById("editProductModal").style.display = "flex";
}
