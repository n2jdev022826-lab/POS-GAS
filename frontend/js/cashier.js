const grid = document.getElementById("productGrid");
const modal = document.getElementById("productModal");
const categoryBar = document.querySelector(".category-bar");

let cart = [];
let selectedProduct = null;
let allProducts = [];
let activeCategory = "all";

/* FORMAT DATE */
function formatDate(dateStr) {
  if (!dateStr) return "N/A";
  const date = new Date(dateStr);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  });
}

/* FORMAT NUMBER AS PHP CURRENCY */
function formatPHP(amount) {
  return new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(amount);
}

/* LOAD PRODUCTS */
function loadProducts() {
  fetch("http://localhost/POS-GAS/api/products/get_products.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.status !== "success") {
        console.error(data.message);
        return;
      }
      allProducts = data.data;
      renderProducts();
    })
    .catch((err) => {
      console.error(err);
      alert("Failed to load products");
    });
}

/* RENDER PRODUCTS (WITH FILTER) */
function renderProducts() {
  grid.innerHTML = "";

  let filteredProducts = allProducts;
  if (activeCategory !== "all") {
    filteredProducts = allProducts.filter(
      (product) => product.category_id == activeCategory
    );
  }

  filteredProducts.forEach((product) => {
    let card = document.createElement("div");
    card.classList.add("card");

    card.innerHTML = `
      <div class="img">
        ${
          product.image
            ? `<img src="/POS-GAS/frontend/assets/uploads/products/${product.image}" class="product-image">`
            : ""
        }
      </div>
      <div class="details">
        <h3>${product.name}</h3>
        <p class="pcode">${product.product_code}</p>
        <p>Remaining: ${product.stock}</p>
        <p class="expiry">Expires at ${formatDate(product.expiry_date)}</p>
        <h4>${formatPHP(product.selling_price)}</h4>
      </div>
    `;

    card.onclick = () =>
      openModal({
        name: product.name,
        product_code: product.product_code,
        stock: product.stock,
        expiry: formatDate(product.expiry_date),
        price: product.selling_price,
        image: product.image,
      });

    grid.appendChild(card);
  });
}

/* LOAD CATEGORIES */
function loadCategories() {
  fetch("http://localhost/POS-GAS/api/categories/get_all.php")
    .then((res) => res.json())
    .then((categories) => {
      categoryBar.innerHTML = "";

      const allBtn = document.createElement("button");
      allBtn.innerText = "ALL";
      allBtn.classList.add("category-btn", "active");
      allBtn.onclick = () => {
        activeCategory = "all";
        setActiveButton(allBtn);
        renderProducts();
      };
      categoryBar.appendChild(allBtn);

      categories.forEach((cat) => {
        const btn = document.createElement("button");
        btn.innerText = cat.category_name;
        btn.classList.add("category-btn");
        btn.onclick = () => {
          activeCategory = cat.category_id;
          setActiveButton(btn);
          renderProducts();
        };
        categoryBar.appendChild(btn);
      });
    })
    .catch((err) => console.error("Failed to load categories:", err));
}

/* ACTIVE BUTTON UI */
function setActiveButton(activeBtn) {
  document.querySelectorAll(".category-btn").forEach((btn) => {
    btn.classList.remove("active");
  });
  activeBtn.classList.add("active");
}

/* OPEN MODAL */
function openModal(product) {
  selectedProduct = product;

  document.getElementById("modalName").innerText = product.name;
  document.getElementById("modalCode").innerText = product.product_code;
  document.getElementById("modalStock").innerText = product.stock;
  document.getElementById("modalExpiry").innerText = product.expiry;
  document.getElementById("modalPrice").innerText = formatPHP(product.price);

  const imgContainer = document.querySelector("#productModal .img");
  if (product.image) {
    imgContainer.innerHTML = `<img src="/POS-GAS/frontend/assets/uploads/products/${product.image}" class="modal-image">`;
  } else {
    imgContainer.innerHTML = "";
  }

  modal.style.display = "flex";
}

/* CLOSE MODAL */
document.getElementById("closeModal").onclick = () => {
  modal.style.display = "none";
};

/* ADD TO CART */
document.getElementById("addToCart").onclick = () => {
  let qty = parseInt(document.getElementById("modalQty").value);
  let total = qty * selectedProduct.price;

  cart.push({
    name: selectedProduct.name,
    qty: qty,
    price: total,
  });

  updateCart();
  modal.style.display = "none";
};

/* UPDATE CART */
function updateCart() {
  const cartDiv = document.getElementById("cartItems");
  cartDiv.innerHTML = "";

  let totalAmount = 0;

  cart.forEach((item) => {
    let row = document.createElement("tr");
    row.innerHTML = `
      <td>${item.name}</td>
      <td>${item.qty}</td>
      <td>${formatPHP(item.price)}</td>
    `;
    cartDiv.appendChild(row);
    totalAmount += item.price;
  });

  document.getElementById("totalAmount").innerText = formatPHP(totalAmount);
}

/* CHECKOUT MODAL LOGIC */
const checkoutModal = document.getElementById("checkoutModal");
const checkoutTotalInput = document.getElementById("checkoutTotal");
const discountSelect = document.getElementById("discountSelect");
const amountPaid = document.getElementById("amountPaid");
const changeDisplay = document.getElementById("changeDisplay");
const hiddenItems = document.getElementById("hiddenItems");
const hiddenTotal = document.getElementById("hiddenTotal");
const hiddenChange = document.getElementById("hiddenChange");
const checkoutForm = document.getElementById("checkoutForm");
const okBtn = document.querySelector(".ok-btn");
const cancelCheckout = document.getElementById("cancelCheckout");
const closeCheckout = document.getElementById("closeCheckout");

function openCheckout() {
  let total = cart.reduce((sum, item) => sum + item.price, 0);
  checkoutTotalInput.value = formatPHP(total);
  amountPaid.value = "";
  changeDisplay.value = formatPHP(0);
  discountSelect.value = "0";
  checkoutModal.style.display = "flex";
}

function updateCheckout() {
  let total = cart.reduce((sum, item) => sum + item.price, 0);
  let discount = parseFloat(discountSelect.value) || 0;
  let discountedTotal = total - (total * discount) / 100;

  checkoutTotalInput.value = formatPHP(discountedTotal);

  let paid = parseFloat(amountPaid.value) || 0;
  let change = Math.max(0, paid - discountedTotal);
  changeDisplay.value = formatPHP(change);
}

if (okBtn) {
  okBtn.addEventListener("click", () => {
    if (cart.length === 0) {
      alert("Cart is empty");
      return;
    }
    openCheckout();
  });
}

discountSelect.addEventListener("change", updateCheckout);
amountPaid.addEventListener("input", updateCheckout);
cancelCheckout.addEventListener(
  "click",
  () => (checkoutModal.style.display = "none")
);
closeCheckout.addEventListener(
  "click",
  () => (checkoutModal.style.display = "none")
);

checkoutForm.addEventListener("submit", (e) => {
  let total = cart.reduce((sum, item) => sum + item.price, 0);
  let discount = parseFloat(discountSelect.value) || 0;
  let discountedTotal = total - (total * discount) / 100;
  let paid = parseFloat(amountPaid.value) || 0;

  if (paid < discountedTotal) {
    e.preventDefault();
    alert("Amount paid is less than total");
    return;
  }

  hiddenItems.value = JSON.stringify(cart);
  hiddenTotal.value = discountedTotal.toFixed(2);
  hiddenChange.value = (paid - discountedTotal).toFixed(2);
});

/* DROPDOWN */
const employeeMenu = document.getElementById("employeeMenu");
const dropdown = document.getElementById("employeeDropdown");
employeeMenu.addEventListener("click", (e) => {
  e.stopPropagation();
  dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
});
document.addEventListener("click", () => {
  dropdown.style.display = "none";
});

/* NAVIGATION */
function goToAccount() {
  window.location.href = "account-settings.php";
}
function logout() {
  window.location.href = "../session.php";
}

/* INIT */
loadCategories();
loadProducts();