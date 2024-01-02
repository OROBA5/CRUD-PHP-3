document.addEventListener("DOMContentLoaded", function () {
    // Function to display the product list
    function displayProductList(products) {
        var productListDiv = document.querySelector(".product-list");
        productListDiv.innerHTML = "";

        products.forEach((product) => {
            var productDiv = document.createElement("div");
            productDiv.classList.add("product-item");

            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.name = "selectedProducts[]";
            checkbox.value = product.product_id; // Use product_id as the checkbox value
            checkbox.className = "delete-checkbox";
            checkbox.dataset.productType = product.product_type; // Set product_type in dataset

            var image = document.createElement("img");
            image.className = "product-image";
            if (product.product_type === "book") {
                // Book image URL
                image.src = "../img/floppy-disk-solid.svg";
            } else if (product.product_type === "dvd") {
                // DVD image URL
                image.src = "../img/chair-solid.svg";
            } else if (product.product_type === "furniture") {
                // Furniture image URL
                image.src = "../img/book-solid.svg";
            }

            var skuSpan = document.createElement("span");
            skuSpan.textContent = "SKU: " + product.sku;

            var nameSpan = document.createElement("span");
            nameSpan.textContent = "Name: " + product.name;

            var priceSpan = document.createElement("span");
            priceSpan.textContent = "Price: " + product.price + " $ ";

            var detailsSpan = document.createElement("span");
            if (product.product_type === "dvd") {
                detailsSpan.textContent = "Size: " + product.size + " MB ";
            } else if (product.product_type === "furniture") {
                detailsSpan.textContent = `Dimensions: ${product.height} x ${product.width} x ${product.length} CM `;
            } else if (product.product_type === "book") {
                detailsSpan.textContent = "Weight: " + product.weight + " KG ";
            }

            productDiv.appendChild(checkbox);
            productDiv.appendChild(image);
            productDiv.appendChild(skuSpan);
            productDiv.appendChild(nameSpan);
            productDiv.appendChild(priceSpan);
            productDiv.appendChild(detailsSpan);

            productListDiv.appendChild(productDiv);
        });
    }

    // Function to fetch products from the API endpoint
    function fetchProducts() {
        fetch("http://localhost/Scandi-Test-3-attempt/backend/actions/api.php")
            .then((response) => response.json())
            .then((data) => {
                displayProductList(data);
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

    // Function to handle the "MASS DELETE"
    document.getElementById("deleteForm").addEventListener("submit", function (event) {
        event.preventDefault();

        var checkedCheckboxes = document.querySelectorAll('input[name="selectedProducts[]"]:checked');
        var deleteRequests = [];

        checkedCheckboxes.forEach(function (checkbox) {
            var deleteAction = "delete";
            var apiEndpoint = "http://localhost/Scandi-Test-3-attempt/backend/actions/api.php";

            var formData = {
                action: deleteAction,
                id: checkbox.value,
                product_type: checkbox.dataset.productType
            };

            console.log("Form data:", formData);

            var deleteRequest = fetch(apiEndpoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log("Server Response:", data);

                    // Refresh the product list after successful deletion
                    return fetchProducts();
                })
                .catch((error) => {
                    console.error("Error:", error);
                });

            deleteRequests.push(deleteRequest);
        });

        Promise.all(deleteRequests)
            .then(() => {
                window.location.reload(true);
            });
    });

    // Fetch and display the product list when the page loads
    fetchProducts();
});