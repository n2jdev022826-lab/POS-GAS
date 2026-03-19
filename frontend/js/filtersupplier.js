

const searchBox = document.getElementById("searchBox");
const result = document.getElementById("dropdownList");

searchBox.addEventListener("keyup", function(){

    let value = this.value.toLowerCase();

    let filtered = dataList.filter(item =>
        item.supplier_name.toLowerCase().includes(value)
    );

    let html = "";

    filtered.forEach(item=>{
        html += `<div class="dropdown-item" data-id="${item.supplier_id}">
                    ${item.supplier_name}
                </div>`;
    });

    result.innerHTML = html;
    result.style.display = "block";

});

result.addEventListener("click", function(e){

    if(e.target.classList.contains("dropdown-item")){

        searchBox.value = e.target.innerText;
        document.getElementById("selectedID").value = e.target.dataset.id;

        result.style.display = "none";
    }

});

document.addEventListener("click", function(e){

    if(!searchBox.contains(e.target) && !result.contains(e.target)){
        result.style.display = "none";
    }

});