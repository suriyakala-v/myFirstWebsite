<?php
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
}
if(empty($_GET['p_id'])){
    header('Location: manageProduct.php');
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
$queryrun = $conn->prepare("SELECT * FROM category_details");
$queryrun->execute();//returns 1 or 0
$queryrun->setFetchMode(PDO::FETCH_OBJ);


if(isset($_GET['p_id'])){
    $check=$_GET['p_id'];
    echo $check;
    $que= $conn->prepare("SELECT * FROM product_details inner join category_details on product_details.categoryid=category_details.categoryid  where productid=$check");
    $que->execute();//returns 1 or 0
    $que->setFetchMode(PDO::FETCH_ASSOC);
    $update = $que->fetch();
}

//add
if(isset($_POST['btn_add'])){
    if(empty($_POST['txt_product_name']) || empty($_POST['txt_product_price']) || $_POST['sel_category']=="" || empty($_POST['product_descp']) || $_FILES["product_img"]["size"]==0 ){
        echo "Please fill all details";
    }
    else{
        $prd_name=$_POST['txt_product_name'];
        $prd_price=$_POST['txt_product_price'];
        $prd_cate=$_POST['sel_category'];
        $prd_desc=$_POST['product_descp'];
        $prd_img=$_FILES['product_img']['name'];
        $queryrun = $conn->prepare("INSERT INTO  product_details(productname,productprice,categoryid,productdescription,productimg,createddate)VALUES('$prd_name','$prd_price','$prd_cate','$prd_desc','$prd_img',23)");
        $queryrun->execute();
        echo "ADDED";
        $dest1 = "../img/".$_FILES['product_img']['name'];
        move_uploaded_file($_FILES["product_img"]['tmp_name'],$dest1);
    }
}
//update
if(isset($_POST['btn_update'])){
    if(empty($_POST['txt_product_name']) || empty($_POST['txt_product_price']) || $_POST['sel_category']=="" || empty($_POST['product_descp'])){
        echo "Please fill all details";
    }
    else{
        $prd_name=$_POST['txt_product_name'];
        $prd_price=$_POST['txt_product_price'];
        $prd_cate=$_POST['sel_category'];
        $prd_desc=$_POST['product_descp'];
        $update_que = "UPDATE product_details SET productname='$prd_name',productprice='$prd_price',categoryid='$prd_cate',productdescription='$prd_desc'  where productid=$check";
        $conn->exec($update_que);
        echo "UPDATED";
    }
}


//back
if(isset($_POST['btn_back'])){
    header('Location: manageProduct.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>product form</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
    <div class="addProduct">
        <?php if($check==-1){
        echo "<h1>ADD NEW PRODUCT</h1><br>";
        }
       else{
        echo "<h1>EDIT PRODUCT</h1><br>";
       }?>
        <form method="post" enctype="multipart/form-data">
            <div class="name">
                <label for="product name">Product Name: </label>
                <input type="text" id="txt_product_name" name="txt_product_name" <?php if($check>0){ ?>
                    value="<?php echo $update['productname']; ?>"
                <?php } ?>>
            </div>
            <br><br>
            Product Price:<input type="number" id="txt_product_price" name="txt_product_price" placeholder="Enter..."  <?php if($check>0){ ?>
                    value="<?php echo $update['productprice']; ?>"
                <?php } ?>><br><br>
            <div class="category">
                <label for="category">category: </label>
                <select id="sel_category" name="sel_category">
                    <option default><?php if($check>0){ echo $update['categoryname']; } ?></option>
                    <?php
                    $query = $conn->prepare("SELECT * FROM category_details");
                    $query->execute();//returns 1 or 0
                    $query->setFetchMode(PDO::FETCH_OBJ);
                    $result = $queryrun->fetch();
                    foreach($query as $cate){
                    echo "
                    <option value='$cate->categoryid'>".$cate->categoryname."</option>
                    ";
                }
                ?>
                </select>
            </div>
            <div class="image">
                <br><br>
                <?php if($check==-1){ ?>
                    Select image:<input type="file" name="product_img" id="product_img" accept="image/*">
                <?php } ?> 
            </div>
            <br><br>
            <div class="Description">
                Product Description:
                <br><textarea id ='product_descp' name="product_descp" rows="3" cols="20"><?php if($check>0){ ?>
                    <?php echo $update['productdescription']?>
                <?php } ?></textarea><br><br>
            </div>
            <div class="sub">
                <?php if($check>0){ ?>
                <input type="submit" value="UPDATE" id="btn_update"  name="btn_update">
                <?php } else
                 {?> <input type="submit" value="ADD" id="btn_add"  name="btn_add">
                <?php  }  ?>
                <input type="submit" value="BACK" id="btn_back"  name="btn_back">
            </div>
        </form>
    </div>
    <!-- <div class="editProduct">
        <h1>EDIT PRODUCT</h1><br>
        <form method="post">
            <div class="name">
                <label for="product name">Product Name: </label>
                <input type="text" id="txt_product_name" name="txt_product_name" value="<?php echo $update['productname']?>"><br><br>
                <div class="nameErr1"></div>
            </div>
            <div class="category">
                <label for="category">category: </label>
                <select id="sel_category" name="sel_category">
                    <option disabled selected>Please select category</option>
                    <php
                    $query = $conn->prepare("SELECT * FROM category_details");
                    $query->execute();//returns 1 or 0
                    $query->setFetchMode(PDO::FETCH_OBJ);
                    $result = $queryrun->fetch();
                    foreach($query as $cate){
                    echo "
                    <option value='$cate->categoryid'>".$cate->categoryname."</option>
                    ";
                }
                </select>
            </div>
            <br>Product Price:<input type="number" id="txt_product_price" name="txt_product_price" value="<?php echo $update['productprice']?>"><br><br>
            <div class="Description">
            <br>Product Description:
                <br><textarea id ='product_descp' name="product_descp" rows="3" cols="20"><php echo $update['productdescription']></textarea><br><br>
            <div class="sub">
                <input type="submit" value="UPDATE" id="btn_update"  name="btn_update">
                <input type="submit" value="BACK" id="btn_back"  name="btn_back">
            </div>
        </form>
    </div> -->
</body>
</html>