<?php
// Start the session
session_start();
    if(empty($_SESSION['sesArr'])){
        echo "Your CART is waiting :)";
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
$count="";
$queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where createddate=23");
$queryrun->execute();//returns 1 or 0
$queryrun->setFetchMode(PDO::FETCH_OBJ);
//search button
if(isset($_POST['btn_search'])){
    if(isset($_POST['sel_category']) && isset($_POST['txt_filter']) ){
        $filter=$_POST['txt_filter'];
        $user_category=$_POST['sel_category'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where category_details.categoryname='$user_category' and productname like '$filter%' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);//..PDO::FETCH_ASSOC OBJ-arryofObj
        $queryrun = $queryrun->fetchall();//fetchall or select *
    }
    elseif(empty($_POST['txt_filter']) && isset($_POST['sel_category'])){
        $user_category=$_POST['sel_category'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where categoryname='$user_category' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);
        $count=$queryrun->rowcount();
    }
    elseif(empty($_POST['sel_category']) && isset($_POST['txt_filter'])){
        $filter=$_POST['txt_filter'];
        $queryrun = $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid where productname like '$filter%' and createddate=23");
        $queryrun->execute();
        $queryrun->setFetchMode(PDO::FETCH_OBJ);
        $count=$queryrun->rowcount();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product list page</title>
    <link rel="stylesheet" href="assets/css/plp.css">
</head>
<body>
    <div class="topnav">
        <img src="./assets/img/logo.PNG" alt="logo.PNG" />
        <a href="cart.php"><input type="button" name="MY CART" value="MY CART" > </a>
        <!-- <a href="cart.php"><button>MY CART </button></a> -->
    </div>
    <H1> < WELCOME > </H1>
    <h2>PRODUCT LISTING PAGE</h2>
    <div class="filterSection">
        <form method="post">
            Product Name:<input type="text" name=txt_filter>
            <select name='sel_category'>
            <option disabled selected value='Choose category'>Choose category</option>
            <?php
            $query = $conn->prepare("SELECT * FROM category_details");
            $query->execute();//returns 1 or 0
            $query->setFetchMode(PDO::FETCH_OBJ);
            foreach($query as $cate){
                echo "
                <option value='$cate->categoryname'>".$cate->categoryname."</option>
                ";
            }
            ?>
            </select>
            <input type="submit" value="SEARCH" name="btn_search" id="btn_search">
            <input type="submit" value="SHOW ALL" name="btn_all" id="btn_all"><br><br>
        </form>
    </div>
    <div class="listProducts">
        <table>
            <tr>
                <th>SI NO</th>
                <th>PRODUCT IMAGE</th>
                <th>PRODUCT NAME</th>
                <th>PRODUCT CATEGORY</th>
                <th>PRICE</th>
                <th>ACTION</th>
            </tr>
            <?php
            if($count==0){
                echo"<style>table{display:none}</style>";
                echo "Data not found";
           }else{
            foreach($queryrun as $row){
            echo "<tr>
            <td>".$row->productid."</td>
            <td><img src='../img/$row->productimg' width='80' height='80'/></td>
            <td>".$row->productname."</td>
            <td>".$row->categoryname."</td>
            <td>".$row->productprice."</td>
            <td> <a href='productDetailPage.php?productid=$row->productid'><input type='button' value='VIEW' name='btn_view' id='btn_view'></a>
            <br></td>
            </tr>";
            }
           }
            ?>
        </table>
    </div>
    <!-- <img src="./assets/img/tv.PNG" alt="" width="80" height="80"/> -->
</body>
</html>