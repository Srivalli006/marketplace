$(document).ready(function () {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || {};

    function updateWishlist() {
        let wishlistContainer = $("#wishlist-items");
        wishlistContainer.html("");

        if (Object.keys(wishlist).length === 0) {
            wishlistContainer.append("<li>No items in wishlist</li>");
        } else {
            for (let id in wishlist) {
                let item = wishlist[id];
                wishlistContainer.append(`
                    <li>
                        ${item.name} - $${item.price.toFixed(2)}
                        <button onclick="removeFromWishlist('${id}')">Remove</button>
                    </li>
                `);
            }
        }
    }

    window.addToWishlist = function(id, name, price, button) {
        if (!wishlist[id]) {
            wishlist[id] = { name, price };
            button.classList.add("selected");
            button.innerText = "Added";
        } else {
            delete wishlist[id];
            button.classList.remove("selected");
            button.innerText = "Add to Wishlist";
        }
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        updateWishlist();
    };

    window.removeFromWishlist = function(id) {
        delete wishlist[id];
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        updateWishlist();
    };

    $("#proceed-to-checkout").click(function () {
        if (Object.keys(wishlist).length === 0) {
            alert("Your wishlist is empty.");
        } else {
            localStorage.setItem("checkoutItems", JSON.stringify(wishlist));
            window.location.href = "checkout.html";
        }
    });

    updateWishlist();
});
