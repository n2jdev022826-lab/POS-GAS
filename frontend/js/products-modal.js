function addProduct() { document.getElementById("addProductModal").style.display = "flex"; } 
function closeProductModal() { document.getElementById("addProductModal").style.display = "none"; } 
function closeEditProductModal() { document.getElementById("editProductModal").style.display = "none"; }

document.addEventListener("DOMContentLoaded", function () {

    // ================= IMAGE PREVIEW =================
    const imageInput = document.getElementById("productImageInput");

    if (imageInput) {
        imageInput.addEventListener("change", function () {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById("productImagePreview").style.backgroundImage =
                        `url(${e.target.result})`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    const editImageInput = document.getElementById("editProductImageInput");

if (editImageInput) {
    editImageInput.addEventListener("change", function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById("editProductImagePreview").style.backgroundImage =
                    `url(${e.target.result})`;
            };
            reader.readAsDataURL(file);
        }
    });
}

    // ================= ADD PRODUCT =================
    const form = document.getElementById("addProductForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("http://localhost/POS-GAS/api/products/create.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showAlert("success", "Success", data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert("error", "Error", data.message);
                }
            });
        });
    }

    // ================= COMPUTE TOTAL =================
    const qty = document.querySelector('[name="quantity"]');
    const price = document.querySelector('[name="original_price"]');

    function computeTotal() {
        const q = parseFloat(qty.value) || 0;
        const p = parseFloat(price.value) || 0;

        document.querySelector('[name="total"]').value = q * p;
        document.querySelector('[name="quantity_left"]').value = q;
    }

    if (qty && price) {
        qty.addEventListener("input", computeTotal);
        price.addEventListener("input", computeTotal);
    }

    // ================= LOAD DROPDOWNS =================
    loadCategories();
    loadSuppliers();

});

function editProduct(productCode) {

    const product = products.find(p => p.product_code === productCode);
    if (!product) return;

    // FILL INPUTS
    document.getElementById("edit_product_code").value = product.product_code;
    document.getElementById("edit_name").value = product.name || "";
    document.getElementById("edit_original_price").value = product.original_price || "";
    document.getElementById("edit_selling_price").value = product.selling_price || "";
    document.getElementById("edit_expiry_date").value = product.expiry_date || "";
    document.getElementById("edit_date_received").value = product.date_received || "";
    document.getElementById("edit_quantity").value = product.quantity || "";
    document.getElementById("edit_quantity_left").value = product.quantity_left || "";
    document.getElementById("edit_total").value = product.total || "";

    // ✅ AUTO SELECT CATEGORY
    loadCategories("edit_category", product.category_id);

    // ✅ AUTO SELECT SUPPLIER
    loadSuppliers("edit_supplier", product.supplier_id);

    // IMAGE PREVIEW
    document.getElementById("editProductImagePreview").style.backgroundImage =
        `url(/POS-GAS/frontend/assets/uploads/products/${product.image || 'default.png'})`;

    // OPEN MODAL
    document.getElementById("editProductModal").style.display = "flex";
}

document.getElementById("editProductForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/products/update.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {
            showAlert("success", "Updated", data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert("error", "Error", data.message);
        }

    })
    .catch(err => {
        console.error(err);
        showAlert("error", "Error", "Update failed");
    });
});

function deleteProduct(productCode) {

    showAlert(
        "confirm",
        "Delete Product",
        "Are you sure you want to delete this product?",
        "Delete",
        function (confirmed) {

            if (!confirmed) return;

            fetch("http://localhost/POS-GAS/api/products/delete.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    product_code: productCode
                })
            })
            .then(res => res.json())
            .then(data => {

                if (data.status === "success") {
                    showAlert("success", "Deleted", data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert("error", "Error", data.message);
                }

            })
            .catch(err => {
                console.error(err);
                showAlert("error", "Error", "Delete failed");
            });

        }
    );
}