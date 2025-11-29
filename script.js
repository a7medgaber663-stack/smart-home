document.addEventListener("DOMContentLoaded", function () {
  const filterBtns = document.querySelectorAll(".filter-btn");
  const searchInput = document.getElementById('searchInput');
  const products = document.querySelectorAll(".product-card");

  let currentCategory = "all";

  function filterProducts() {
    const searchText = searchInput.value.toLowerCase();

    products.forEach(product => {
      const matchesCategory = currentCategory === "all" || product.classList.contains(currentCategory);
      const matchesText = product.textContent.toLowerCase().includes(searchText);

      product.style.display = (matchesCategory && matchesText) ? "block" : "none";
    });
  }

  filterBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      currentCategory = btn.getAttribute("data-category");
      filterProducts();
    });
  });

  searchInput.addEventListener("input", filterProducts);
});

function toggleMenu() {
  document.getElementById("sidebar").classList.toggle("active");
}

document.addEventListener("click", function(event) {
  const sidebar = document.getElementById("sidebar");
  const menuIcon = document.querySelector(".menu-icon");

  if (sidebar.classList.contains("active") &&
      !sidebar.contains(event.target) &&
      !menuIcon.contains(event.target)) {
    sidebar.classList.remove("active");
  }
});
