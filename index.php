<?php
//code for sessions for the test of the website
session_start();
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product List</title>
  <link rel="stylesheet" type="text/css" href="./index.css">
</head>

<body>
  <div class="navbar">
    <h1>Product list</h1>
    <div class="inner-navbar">
      <button><a href="./addProduct.php">ADD</a></button>
      <form id="deleteForm" method="POST" action="delete">
        <button type="submit" id="massDeleteButton">MASS DELETE</button>
      </form>
    </div>
  </div>
  <div class="product-list">
    <!-- Products will be displayed here dynamically using JavaScript -->
  </div>
  <footer>
    <h3>Scandiweb Test Assignment</h3>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery library -->
  <script src="./assets/js/index.js"></script>
</body>

</html>
