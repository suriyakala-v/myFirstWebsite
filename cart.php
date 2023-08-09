<?php
// Start the session
session_start();
if(empty($_SESSION['sesArr'])){
    echo "Nothing in cart";
    header('Location:productListingPage.php');
}

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

//remove from cart
if(isset($_GET['dlt_id'])){
    print_r($_SESSION['sesArr']);
    $dlt=$_GET['dlt_id'];
    $index=array_search($dlt,$_SESSION['sesArr']);
    unset($_SESSION['sesArr'][$index]);
    header('Location: cart.php');//if session empty redirect to home page
}
//order
if(isset($_POST['btn_order'])){
    session_unset();
    header('Location:order.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart page</title>
    <link rel="stylesheet" href="assets/css/pdp.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <script src="assets/js/library.js"></script>
    <script src="assets/js/price.js"></script>
</head>
<body>
    <div class="topnav">
       <img src="./assets/img/logo.PNG" alt="logo.PNG" />
       <a href="productListingPage.php"><button>HOME </button></a>
    </div>
    <H1>MY CART</H1>
    <!-- <main> -->
    <form method=post class="display">
        <div class="imgDiv">
            <?php
            $total=0;
            foreach($_SESSION['sesArr'] as $id){ // loop starts
                $queryrun = $conn->prepare("SELECT * FROM product_details  where productid=$id ");
                $queryrun->execute();//returns 1 or 0
                $queryrun->setFetchMode(PDO::FETCH_ASSOC);
                $result = $queryrun->fetch();
                echo "<img src='../img/$result[productimg]' width='100' height='100'/>";
                
                $total=$total+$result['productprice']; ?>
        </div>
        <div class="detailsDiv">
            <?php
            echo "<h4>PRODUCT NAME           =  $result[productname]</h4>";
            echo "<h4>PRODUCT DESCRIPTION    =  $result[productdescription]</h4>";
            echo "<h4>PRODUCT PRICE          =  Rs.$result[productprice]</h4>";
            ?>
        </div>
        <h4>Quantity: </h4><select name="quantity" id="quantity" class="quantity">
            <option value="<?php echo $result['productprice']*1 ?>">1</option>
            <option value="<?php echo $result['productprice']*2 ?>">2</option>
            <option value="<?php echo $result['productprice']*3 ?>">3</option>
        </select>
        <div class="clicks">
            <?php echo "<a href=cart.php?dlt_id=$result[productid]><input type='button' value='REMOVE' name='btn_remove' id='btn_remove'> </a>" ?>
        </div>
        <?php  } //loop ends ?>
        <h5>TOTAL PRICE</h5>
        <div class="totalPrice" id="totalPrice">
             <?php  echo $total; ?>
        </div>
        <input type="submit" value="PLACE ORDER" name="btn_order" id="btn_order">
    </form>
    <!-- </main> -->
</body>
</html>