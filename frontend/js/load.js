
// =======================
// LOAD CATEGORIES
// =======================
function loadCategories() {
    fetch("http://localhost/POS-GAS/api/categories/get_all.php")
        .then(res => res.json())
        .then(data => {

            const categorySelect = document.querySelector('[name="category_id"]');
            const editCategorySelect = document.getElementById('edit_category');

            categorySelect.innerHTML = '<option value="">Select Category</option>';
            editCategorySelect.innerHTML = '<option value="">Select Category</option>';

            data.forEach(cat => {
                const option1 = `<option value="${cat.category_id}">${cat.category_name}</option>`;
                const option2 = `<option value="${cat.category_id}">${cat.category_name}</option>`;

                categorySelect.innerHTML += option1;
                editCategorySelect.innerHTML += option2;
            });

        });
}

// =======================
// LOAD SUPPLIERS
// =======================
function loadSuppliers() {
    fetch("http://localhost/POS-GAS/api/suppliers/get_all.php")
        .then(res => res.json())
        .then(data => {

            const supplierSelect = document.querySelector('[name="supplier_id"]');
            const editSupplierSelect = document.getElementById('edit_supplier');

            supplierSelect.innerHTML = '<option value="">Select Supplier</option>';
            editSupplierSelect.innerHTML = '<option value="">Select Supplier</option>';

            data.forEach(sup => {
                const option1 = `<option value="${sup.supplier_id}">${sup.supplier_name}</option>`;
                const option2 = `<option value="${sup.supplier_id}">${sup.supplier_name}</option>`;

                supplierSelect.innerHTML += option1;
                editSupplierSelect.innerHTML += option2;
            });

        });
}
