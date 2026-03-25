// =======================
// LOAD CATEGORIES
// =======================
function loadCategories(selectId = null, selectedValue = null) {
    fetch("http://localhost/POS-GAS/api/categories/get_all.php")
        .then(res => res.json())
        .then(data => {

            const categorySelect = selectId
                ? document.getElementById(selectId)
                : document.querySelector('[name="category_id"]');

            const editCategorySelect = !selectId
                ? document.getElementById('edit_category')
                : null;

            // RESET
            categorySelect.innerHTML = '<option value="">Select Category</option>';
            if (editCategorySelect) {
                editCategorySelect.innerHTML = '<option value="">Select Category</option>';
            }

            data.forEach(cat => {

                const selected = (selectedValue && selectedValue == cat.category_id) ? 'selected' : '';

                const option = `<option value="${cat.category_id}" ${selected}>
                                    ${cat.category_name}
                                </option>`;

                categorySelect.innerHTML += option;

                if (editCategorySelect) {
                    editCategorySelect.innerHTML += `
                        <option value="${cat.category_id}">
                            ${cat.category_name}
                        </option>`;
                }

            });

        });
}

// =======================
// LOAD SUPPLIERS
// =======================
function loadSuppliers(selectId = null, selectedValue = null) {
    fetch("http://localhost/POS-GAS/api/suppliers/get_all.php")
        .then(res => res.json())
        .then(data => {

            const supplierSelect = selectId
                ? document.getElementById(selectId)
                : document.querySelector('[name="supplier_id"]');

            const editSupplierSelect = !selectId
                ? document.getElementById('edit_supplier')
                : null;

            // RESET
            supplierSelect.innerHTML = '<option value="">Select Supplier</option>';
            if (editSupplierSelect) {
                editSupplierSelect.innerHTML = '<option value="">Select Supplier</option>';
            }

            data.forEach(sup => {

                const selected = (selectedValue && selectedValue == sup.supplier_id) ? 'selected' : '';

                const option = `<option value="${sup.supplier_id}" ${selected}>
                                    ${sup.supplier_name}
                                </option>`;

                supplierSelect.innerHTML += option;

                if (editSupplierSelect) {
                    editSupplierSelect.innerHTML += `
                        <option value="${sup.supplier_id}">
                            ${sup.supplier_name}
                        </option>`;
                }

            });

        });
}