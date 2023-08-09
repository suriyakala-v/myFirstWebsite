<?php
// Start the session
session_start();
if(!empty($_SESSION['sesArr'])){
    header('Location:cart.php');
}
// if(empty($_SESSION['sesArr'])){
//     header('Location:productListingPage.php');
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart page</title>
    <link rel="stylesheet" href="assets/css/order.css">
</head>
<body>
    <div class="topnav">
        <h1>ORDER PLACED SUCCESSFULLY ! </h1>
    </div>
        <div class="clicks">
        <a href="productListingPage.php"><button>OK </button></a>
    </div>
</body>
</html>