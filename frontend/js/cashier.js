

const grid = document.getElementById("productGrid");
const modal = document.getElementById("productModal");

let cart = [];
let selectedProduct = null;


function formatDate(dateStr) {
  if (!dateStr) return "N/A";

  const date = new Date(dateStr);

  // Format: MM/DD/YYYY (or change if you want)
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit"
  });
}


/* LOAD PRODUCTS */

function loadProducts() {

fetch("http://localhost/POS-GAS/api/products/get_products.php")
  .then(res => res.text()) // 👈 TEMP CHANGE
  .then(data => {
    console.log(data); // 👈 SEE REAL ERROR HERE
  });

  fetch("http://localhost/POS-GAS/api/products/get_products.php")
    .then(res => res.json())
    .then(data => {

      if (data.status !== "success") {
        console.error(data.message);
        return;
      }

      const products = data.data;

      grid.innerHTML = "";

      products.forEach(product => {

        let card = document.createElement("div");
        card.classList.add("card");

        card.innerHTML = `
          <div class="img">
            ${product.image 
              ? ` <img 
        src="/POS-GAS/frontend/assets/uploads/products/${product.image || 'default.jpg'}" 
        class="product-image">` 
              : ""}
          </div>

          <div class="details">
            <h3>${product.name}</h3>
            <p>${product.product_code}</p>

            <p>Remaining: ${product.stock}</p>
            <p class="expiry">Expires at ${formatDate(product.expiry_date)}</p>

            <h4>₱ ${product.selling_price}</h4>
          </div>
        `;

        card.onclick = () => openModal({
          name: product.name,
          dose: product.product_code, // since no dose column
          stock: product.stock,
          expiry: formatDate(product.expiry_date),
          price: product.selling_price
        });

        grid.appendChild(card);
      });

    })
    .catch(err => {
      console.error(err);
      alert("Failed to load products");
    });
}

loadProducts();


/* OPEN MODAL */

function openModal(product){

selectedProduct=product;

document.getElementById("modalName").innerText=product.name;
document.getElementById("modalDose").innerText=product.dose;
document.getElementById("modalStock").innerText=product.stock;
document.getElementById("modalExpiry").innerText=product.expiry;
document.getElementById("modalPrice").innerText=product.price;

modal.style.display="flex";

}


/* CLOSE MODAL */

document.getElementById("closeModal").onclick=()=>{

modal.style.display="none";

};


/* ADD TO CART */

document.getElementById("addToCart").onclick=()=>{

let qty=parseInt(document.getElementById("modalQty").value);

let total=qty*selectedProduct.price;

cart.push({
name:selectedProduct.name,
qty:qty,
price:total
});

updateCart();

modal.style.display="none";

};


/* UPDATE CART */

function updateCart(){

const cartDiv=document.getElementById("cartItems");
cartDiv.innerHTML="";

let totalAmount=0;

cart.forEach(item=>{

let row=document.createElement("div");
row.classList.add("cart-row");

row.innerHTML=`

<span>${item.name}</span>
<span>${item.qty}</span>
<span>₱ ${item.price}</span>

`;

cartDiv.appendChild(row);

totalAmount+=item.price;

});

document.getElementById("totalAmount").innerText=totalAmount;

}


/* CHECKOUT HANDLERS */

const okBtn = document.querySelector('.ok-btn');
const checkoutModal = document.getElementById('checkoutModal');
const closeCheckout = document.getElementById('closeCheckout');
const cancelCheckout = document.getElementById('cancelCheckout');
const checkoutTotal = document.getElementById('checkoutTotal');
const amountPaid = document.getElementById('amountPaid');
const changeDisplay = document.getElementById('changeDisplay');
let hiddenItems = document.getElementById('hiddenItems');
let hiddenTotal = document.getElementById('hiddenTotal');
let hiddenChange = document.getElementById('hiddenChange');
const checkoutForm = document.getElementById('checkoutForm');

function openCheckout(){
	// show current total
	const total = parseFloat(document.getElementById('totalAmount').innerText) || 0;
	checkoutTotal.innerText = total;
	amountPaid.value = '';
	changeDisplay.innerText = '0';
	checkoutModal.style.display = 'flex';
}

if(okBtn){
	okBtn.addEventListener('click', (e)=>{
		// If the clicked OK is the product modal OK, ignore (add-btn handles that). We open checkout only when clicking the right-panel OK button.
		// Determine if cart has items
		if(cart.length===0){
			alert('Cart is empty');
			return;
		}
		openCheckout();
	});
}

if(closeCheckout) closeCheckout.onclick = ()=> checkoutModal.style.display='none';
if(cancelCheckout) cancelCheckout.onclick = ()=> checkoutModal.style.display='none';

amountPaid && amountPaid.addEventListener('input', ()=>{
	const paid = parseFloat(amountPaid.value) || 0;
	const total = parseFloat(document.getElementById('totalAmount').innerText) || 0;
	const change = Math.max(0, paid - total);
	changeDisplay.innerText = change.toFixed(2);
});

// before submitting, populate hidden fields
checkoutForm && checkoutForm.addEventListener('submit', (e)=>{
	const total = parseFloat(document.getElementById('totalAmount').innerText) || 0;
	const paid = parseFloat(amountPaid.value) || 0;
	if(paid < total){
		e.preventDefault();
		alert('Amount paid is less than total');
		return;
	}
	const payload = JSON.stringify(cart);
	console.log('Submitting checkout', { items: cart, total, paid, change: (paid-total) });

	// ensure hidden inputs exist (in case elements were not found earlier)
	if(!hiddenItems){
		const inp = document.createElement('input');
		inp.type = 'hidden'; inp.name = 'items'; inp.id = 'hiddenItems';
		checkoutForm.appendChild(inp);
		hiddenItems = inp;
	}
	if(!hiddenTotal){
		const inp = document.createElement('input');
		inp.type = 'hidden'; inp.name = 'total'; inp.id = 'hiddenTotal';
		checkoutForm.appendChild(inp);
		hiddenTotal = inp;
	}
	if(!hiddenChange){
		const inp = document.createElement('input');
		inp.type = 'hidden'; inp.name = 'change'; inp.id = 'hiddenChange';
		checkoutForm.appendChild(inp);
		hiddenChange = inp;
	}

	hiddenItems.value = payload;
	hiddenTotal.value = total;
	hiddenChange.value = (paid - total).toFixed(2);
	// allow form to submit
});


const employeeMenu = document.getElementById("employeeMenu");
const dropdown = document.getElementById("employeeDropdown");

// Toggle dropdown
employeeMenu.addEventListener("click", function (e) {
  e.stopPropagation();
  dropdown.style.display =
    dropdown.style.display === "flex" ? "none" : "flex";
});

// Close when clicking outside
document.addEventListener("click", function () {
  dropdown.style.display = "none";
});

// Actions
function goToAccount() {
  window.location.href = "account-settings.php"; // change if needed
}

function logout() {
  window.location.href = "../session.php";
}