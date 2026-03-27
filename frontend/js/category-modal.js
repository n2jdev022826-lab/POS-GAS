// ================= OPEN / CLOSE =================
function addCategory() {
  document.getElementById("addCategoryModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

function closeCategoryModal() {
  document.getElementById("addCategoryModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditCategoryModal() {
  document.getElementById("editCategoryModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

// ================= ADD =================
document
  .getElementById("addCategoryForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/categories/create.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          showAlert("success", "Success", data.message);
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert("error", "Failed", data.message);
        }
      })
      .catch(() => {
        showAlert("error", "Error", "Create failed");
      });
  });

// ================= EDIT (FILL MODAL) =================
function editCategory(code) {
  const category = categories.find((c) => c.category_code === code);
  if (!category) return;

  document.getElementById("edit_category_code").value = category.category_code;
  document.getElementById("edit_category_name").value = category.category_name;
  document.getElementById("edit_category_description").value =
    category.description;

  document.getElementById("editCategoryModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

// ================= UPDATE =================
document
  .getElementById("editCategoryForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/categories/update.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          showAlert("success", "Updated", data.message);
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert("error", "Failed", data.message);
        }
      })
      .catch(() => {
        showAlert("error", "Error", "Update failed");
      });
  });

// ================= DELETE =================
function deleteCategory(code) {
  showAlert(
    "confirm",
    "Delete Category",
    "Are you sure you want to delete this category?",
    "Delete",
    function (confirmed) {
      if (!confirmed) return;

      const formData = new FormData();
      formData.append("category_code", code);

      fetch("http://localhost/POS-GAS/api/categories/delete.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            showAlert("success", "Deleted", data.message);
            setTimeout(() => location.reload(), 1000);
          } else {
            showAlert("error", "Failed", data.message);
          }
        });
    },
  );
}
