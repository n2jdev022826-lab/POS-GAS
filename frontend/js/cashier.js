const products = [
{
name:"Amoxicillin",
dose:"500 mg",
price:30,
stock:210,
expiry:"10/28/2027"
},
{
name:"Ibuprofen",
dose:"200 mg",
price:50,
stock:210,
expiry:"10/28/2027"
},
{
name:"Paracetamol",
dose:"500 mg",
price:20,
stock:210,
expiry:"10/28/2027"
},
{
name:"Ciprofloxacin",
dose:"500 mg",
price:25,
stock:210,
expiry:"10/28/2027"
}
];

const grid = document.getElementById("productGrid");
const modal = document.getElementById("productModal");

let cart = [];
let selectedProduct = null;


/* LOAD PRODUCTS */

function loadProducts(){

grid.innerHTML="";

products.forEach(product=>{

let card=document.createElement("div");
card.classList.add("card");

card.innerHTML=`

<div class="img"></div>

<div class="details">
<h3>${product.name}</h3>
<p>${product.dose}</p>

<p>Remaining: ${product.stock}</p>
<p class="expiry">Expires at ${product.expiry}</p>

<h4>₱ ${product.price}</h4>
</div>
`;

card.onclick=()=>openModal(product);

grid.appendChild(card);

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