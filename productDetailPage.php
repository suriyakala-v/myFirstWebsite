<?php
// Start the session
session_start();

//db info
$servername = "192.168.11.49";
$productname = "root";
$password = "vins35@123456";
//db connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=surya", $productname,$password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//settting attributes
    //echo "Connected successfully";
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
//redirect to plp
if(empty($_GET['productid'])){
    header('Location:productListingPage.php');
}
//getting productid from plp page
$id=$_GET['productid'];
$queryrun = $conn->prepare("SELECT * FROM product_details  where productid=$id ");
$queryrun->execute();//returns 1 or 0
$queryrun->setFetchMode(PDO::FETCH_ASSOC);
$result = $queryrun->fetch();

//back button
if(isset($_POST['btn_back'])){
    header('Location:productListingPage.php');
}

//add to cart
$added="";

if(isset($_POST['btn_cart'])){
    if(empty($_SESSION['sesArr'])){
        $_SESSION['sesArr'][]= $id;
        $added="Successfully ADDED";
    }
    elseif(in_array($id, $_SESSION['sesArr'])){
        $added="Already in cart";
        header('Location:cart.php');
    }
    else{
        $_SESSION['sesArr'][]= $id;
        $added="Successfully ADDED";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product detail page</title>
    <link rel="stylesheet" href="assets/css/pdp.css">
</head>
<body>
    <H1>PRODUCT DETAIL PAGE</H1>
    <main>
        <div class="imgDiv">
            <?php echo "<img src='../img/$result[productimg]' width='400' height='400'/>"?>
        </div>
        <div class="detailsDiv">
            <?php
            echo "<h2>PRODUCT NAME           =  $result[productname]</h2>";
            echo "<h2>PRODUCT DESCRIPTION    =  $result[productdescription]</h2>";
            echo "<h2>PRODUCT PRICE          =  Rs.$result[productprice]</h2>";
            ?>
        </div>
    </main>
    <div class="clicks" >
        <form method=post>
           <input type="submit" value="ADD TO CART" name="btn_cart" id="btn_cart">
           <input type="submit" value="BACK" name="btn_back" id="btn_back">
        </form>
    </div>
    <div class="added">
        <?php  echo "<H3>$added </H3>"?>
    </div>
</body>
</html>