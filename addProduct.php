<?php
//code for sessions for the test of the website
session_start();
?>

<!DOCTYPE html>

<html>
<head>
    <title>Add a product</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
</head>
<body class="add">
    <form method="post" id="product_form" action="create">

        <div class="navbar">
            <h1> Add a Product</h1>
            <div class="inner-navbar">
                <button type="submit">Save</button>
                <button><a href="./">Cancel</a></button>
            </div>
        </div>

        <div class="add-product">
            <div id="warning" style="color: red; display: none;"></div>

            <label for="sku">SKU:</label>
            <input type="text" name="sku" id="sku" >

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" >

            <label for="price">Price($):</label>
            <input type="text" name="price" id="price" >

            <label for="type">Product type:</label>
            <select id="productType" name="typeId" required>
                <option value="">Select a type</option>
                <option value="book">Book</option>
                <option value="dvd">DVD</option>
                <option value="furniture">Furniture</option>
            </select>

            <!-- product specific fields -->
            <div id="book-fields" class="hidden">
                <h5>Please, provide weight</h5>
                <label for="weight">Weight(KG):</label>
                <input type="text" id="weight" name="weight">
            </div>

            <div id="dvd-fields" class="hidden">
                <h5>Please, provide size</h5>
                <label for="size">Size(MB):</label>
                <input type="text" id="size" name="size">
            </div>

            <div id="furniture-fields" class="hidden">
                <h5>Please, provide dimensions</h5>
                <label for="height">Height(CM):</label>
                <input type="text" id="height" name="height">

                <label for="width">Width(CM):</label>
                <input type="text" id="width" name="width">

                <label for="length">Length(CM):</label>
                <input type="text" id="length" name="length">
            </div>
        </div>
    </form>
    <footer class="add-footer">
        <script src="./assets/js/AddProduct.js"></script>
        <h3> Scandiweb Test assignment</h3>
    </footer>
</body>
</html>
