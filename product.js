const urlParams = new URLSearchParams(window.location.search);

document.getElementById("productName").textContent = urlParams.get("name");
document.getElementById("productImage").src = urlParams.get("img");
document.getElementById("productDesc").textContent = urlParams.get("desc");
document.getElementById("productPrice").textContent = "السعر: " + urlParams.get("price") + " جنيه";
