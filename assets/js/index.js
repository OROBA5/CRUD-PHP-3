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
        
        var image = document.createElement("img");
        image.className = "product-image";
        if (product.product_type_id === "book") {
          // Book image URL
          image.src = "./image/floppy-disk-solid.svg";
        } else if (product.product_type_id === "dvd") {
          // DVD image URL
          image.src = "./image/chair-solid.svg"
        } else if (product.product_type_id === "furniture") {
          // Furniture image URL
          image.src = "./image/book-solid.svg";
        }
    
        var skuSpan = document.createElement("span");
        skuSpan.textContent = "SKU: " + product.sku;
    
        var nameSpan = document.createElement("span");
        nameSpan.textContent = "Name: " + product.name;
    
        var priceSpan = document.createElement("span");
        priceSpan.textContent = "Price: " + product.price + " $ ";
    
        var detailsSpan = document.createElement("span");
        if (product.product_type_id === "dvd") {
          detailsSpan.textContent = "Size: " + product.size + " MB ";
        } else if (product.product_type_id === "furniture") {
          detailsSpan.textContent = `Dimensions: ${product.height} x ${product.width} x ${product.length} CM `;
        } else if (product.product_type_id === "dvd") {
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
      
          var selectedProducts = Array.from(document.querySelectorAll('input[name="selectedProducts[]"]:checked')).map(function (checkbox) {
            return { id: checkbox.value, product_type: checkbox.dataset.productType };
          });
      
          var formData = JSON.stringify(selectedProducts);
      
          console.log("JSON data:", formData);
      
          var xhr = new XMLHttpRequest();
          xhr.open("POST", this.action, true);
          xhr.setRequestHeader("Content-Type", "application/json");
          xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
              if (xhr.status === 200) {
                console.log(xhr.responseText); // Successful response
                // After successful deletion, fetch and refresh the product list
                fetchProducts();
              } else {
                console.error(xhr.responseText);
              }
            }
          };
          xhr.send(formData);
        });
      
        // Fetch and display the product list when the page loads
        fetchProducts();
      });
      