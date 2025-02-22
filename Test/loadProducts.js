document.addEventListener("DOMContentLoaded", () => {
    fetch("buyer.php")
        .then(response => response.json())
        .then(products => {
            if (!Array.isArray(products)) {
                console.error("Invalid product data:", products);
                return;
            }

            const productList = document.getElementById("product-list");
            productList.innerHTML = "";

            products.forEach(product => {
                const productDiv = document.createElement("div");
                productDiv.classList.add("product");
                productDiv.dataset.id = product.id;

                productDiv.innerHTML = `
                    <img src="uploads/${product.image}" alt="${product.product_name}" />
                    <h3>${product.product_name}</h3>
                    <p>${product.description}</p>
                    <p><strong>Price:</strong> $${product.price}</p>
                    <button class="wishlist-btn" onclick="addToWishlist(${product.id}, '${product.product_name}', ${product.price}, this)">Add to Wishlist</button>
                `;

                productList.appendChild(productDiv);
            });
        })
        .catch(error => console.error("Error loading products:", error));
});
