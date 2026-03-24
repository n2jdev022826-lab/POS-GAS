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