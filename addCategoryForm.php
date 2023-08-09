<?php
session_start();
//if not login then redirect to login page
if(empty($_SESSION['userName'])){
    header('Location: adminLoginPage.html');
}
if(empty($_GET['c_id'])){
    header('Location: manageCategory.php');
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
$check=-1;
if(isset($_GET['c_id'])){
    $check=$_GET['c_id'];
    $que= $conn->prepare("SELECT * FROM category_details  where categoryid=$check");
    $que->execute();//returns 1 or 0
    $que->setFetchMode(PDO::FETCH_ASSOC);
    $update = $que->fetch();
}

//add
if(isset($_POST['btn_add'])){
    if(empty($_POST['txt_category_name']) || empty($_POST['category_descp'])){
        echo "Please fill all details";
    }
    else{
        $cate_name=$_POST['txt_category_name'];
        $cate_desc=$_POST['category_descp'];
        $queryrun = $conn->prepare("INSERT INTO  category_details(categoryname,categorydescription)VALUES('$cate_name','$cate_desc')");
        $queryrun->execute();//returns 1 or 0
        echo "ADDED";
    }
}

//update
if(isset($_POST['btn_update'])){
//showing exist details on form
$id=$_GET['c_id'];
$que= $conn->prepare("SELECT * FROM category_details  where categoryid=$id");
$que->execute();//returns 1 or 0
$que->setFetchMode(PDO::FETCH_ASSOC);
$update = $que->fetch();
    if(empty($_POST['txt_category_name']) || empty($_POST['category_descp'])){
        echo "Please fill all details";
    }
    else{
        $cate_name=$_POST['txt_category_name'];
        $cate_desc=$_POST['category_descp'];
        $update_que = "UPDATE category_details SET categoryname='$cate_name',categorydescription='$cate_desc'  where categoryid=$id";
        $conn->exec($update_que);
        echo "UPDATED";
    }
}

//back
if(isset($_POST['btn_back'])){
    header('Location:manageProduct.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>category form</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
<?php  if($check==-1) { ?>
    <div class="addCategory">
        <h1>ADD NEW CATEGORY </h1><br>
        <form method="post">
            <div class="name">
                <label for="category name">category  Name: </label>
                <input type="text" id="txt_category_name" name="txt_category_name" placeholder="Enter..."><br><br>
                <div class="nameErr1"></div>
            </div>
            <div class="Description">
                category  Description:
                <br><textarea id ='category_descp' name="category_descp" rows="3" cols="20"></textarea><br><br>
            <div class="sub">
                <input type="submit" value="ADD" id="btn_add"  name="btn_add">
                <input type="submit" value="BACK" id="btn_back"  name="btn_back">
            </div>
        </form>
        </div>
<?php } ?>

<?php  if($check>0) {  ?>
        <div class="addCategory">
        <h1>UPDATE CATEGORY </h1><br>
        <form method="post">
            <div class="name">
                <label for="category name">category  Name: </label>
                <input type="text" id="txt_category_name" name="txt_category_name" value="<?php echo $update['categoryname']?>"><br><br>
                <div class="nameErr1"></div>
            </div>
            <div class="Description">
                category  Description:
                <br><textarea id ='category_descp' name="category_descp" rows="3" cols="20"><?php echo $update['categorydescription']?></textarea><br><br>
            <div class="sub">
                <input type="submit" value="UPDATE" id="btn_update"  name="btn_update">
                <input type="submit" value="BACK" id="btn_back"  name="btn_back">
            </div>
        </form>
        </div>
<?php } ?>
</body>
</html>