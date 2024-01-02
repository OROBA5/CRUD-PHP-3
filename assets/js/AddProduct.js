document.addEventListener("DOMContentLoaded", function () {
    var productForm = document.getElementById("product_form");

    // Show/hide additional fields based on selected type
    document.getElementById("productType").addEventListener("change", function () {
        var selectedType = this.value;

        // Hide all dynamic fields
        document.getElementById("book-fields").classList.add("hidden");
        document.getElementById("dvd-fields").classList.add("hidden");
        document.getElementById("furniture-fields").classList.add("hidden");

        // Show fields based on the selected type
        if (selectedType === "book") {
            document.getElementById("book-fields").classList.remove("hidden");
        } else if (selectedType === "dvd") {
            document.getElementById("dvd-fields").classList.remove("hidden");
        } else if (selectedType === "furniture") {
            document.getElementById("furniture-fields").classList.remove("hidden");
        }
    });

    // Form submission event
    productForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        if (!(await validateForm())) {
            return; // If the form is not valid, do not submit
        }

        var productType = encodeURIComponent(document.getElementById("productType").value);
        var sku = encodeURIComponent(document.getElementById("sku").value);

        // Prepare form data
        var formData = {
            action: "create",
            sku: sku,
            name: encodeURIComponent(document.getElementById("name").value),
            price: encodeURIComponent(document.getElementById("price").value),
            product_type: productType,
            weight: document.getElementById("weight").value || null,
            size: document.getElementById("size").value || null,
            height: document.getElementById("height").value || null,
            width: document.getElementById("width").value || null,
            length: document.getElementById("length").value || null,
        };

        // Post the form data
        fetch("http://localhost/Scandi-Test-3-attempt/backend/actions/api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
        })
            .then(() => {
                window.location.href = "http://localhost/scandi-Test-3-attempt/";
            })
            .catch((error) => {
                console.error("Error in server response:", error);
            });
    });

    // Form validation function
async function validateForm() {
    var productType = document.getElementById("productType").value;
    var warningDiv = document.getElementById("warning");

    // Check if type is selected
    if (productType === "") {
        warningDiv.textContent = "Please select a type";
        warningDiv.style.display = "block";
        return false;
    }

    // Check SKU, Name, and Price fields
    var sku = document.getElementById("sku").value;
    var name = document.getElementById("name").value;
    var price = document.getElementById("price").value;

    if (sku === "" || name === "" || price === "") {
        warningDiv.textContent = "Please, provide the data of the indicated type";
        warningDiv.style.display = "block";
        return false;
    }

    // Check if price is a valid number
    if (isNaN(parseFloat(price))) {
        warningDiv.textContent = "Please, provide a valid price";
        warningDiv.style.display = "block";
        return false;
    }

    // Check if SKU is taken
    if (await isSkuTaken(sku)) {
        // SKU is already taken
        warningDiv.textContent = "SKU is already used for a different product";
        warningDiv.style.display = "block";
        return false;
    }

    // Check required fields based on type
    if (productType === "book") {
        var weight = document.getElementById("weight").value;
        if (weight === "" || isNaN(parseFloat(weight))) {
            warningDiv.textContent = "Please, provide a valid weight";
            warningDiv.style.display = "block";
            return false;
        }
    } else if (productType === "dvd") {
        var size = document.getElementById("size").value;
        if (size === "" || isNaN(parseFloat(size))) {
            warningDiv.textContent = "Please, provide a valid size";
            warningDiv.style.display = "block";
            return false;
        }
    } else if (productType === "furniture") {
        var height = document.getElementById("height").value;
        var width = document.getElementById("width").value;
        var length = document.getElementById("length").value;

        if (
            height === "" ||
            isNaN(parseFloat(height)) ||
            width === "" ||
            isNaN(parseFloat(width)) ||
            length === "" ||
            isNaN(parseFloat(length))
        ) {
            warningDiv.textContent = "Please, provide valid dimensions";
            warningDiv.style.display = "block";
            return false;
        }
    }

    // If all checks pass, return true
    warningDiv.style.display = "none";
    return true;
}


    // Function to check if SKU is already taken
    async function isSkuTaken(sku) {
        try {
            // Fetch product data
            var response = await fetch("http://localhost/Scandi-Test-3-attempt/backend/actions/api.php");
            var data = await response.json();

            // Check if SKU is taken
            return data.some((product) => product.sku === sku);
        } catch (error) {
            console.error("Error occurred while checking SKU:", error);
            return false;
        }
    }
});
