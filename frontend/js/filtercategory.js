

const categorySearch = document.getElementById("categorySearch");
const categoryDropdown = document.getElementById("categoryDropdown");

categorySearch.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let filtered = categoryList.filter(item =>
        item.category_name.toLowerCase().includes(value)
    );

    let html = "";

    filtered.forEach(item=>{
        html += `<div class="dropdown-item" data-id="${item.category_id}">
                    ${item.category_name}
                </div>`;
    });

    categoryDropdown.innerHTML = html;
    categoryDropdown.style.display = "block";

});


categoryDropdown.addEventListener("click", function(e){

    if(e.target.classList.contains("dropdown-item")){

        categorySearch.value = e.target.innerText;
        document.getElementById("selectedCategoryID").value = e.target.dataset.id;

        categoryDropdown.style.display = "none";
    }

});


document.addEventListener("click", function(e){

    if(!categorySearch.contains(e.target) && !categoryDropdown.contains(e.target)){
        categoryDropdown.style.display = "none";
    }

});