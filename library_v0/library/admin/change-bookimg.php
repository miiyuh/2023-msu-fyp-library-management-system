<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['update']))
{

$book_id=intval($_GET['book_id']);
$book_img=$_FILES["bookpic"]["name"];
//currentimage
$cimage=$_POST['curremtimage'];
$cpath="book_img"."/".$cimage;
// get the image extension
$extension = substr($book_img,strlen($book_img)-4,strlen($book_img));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
//rename the image file
$imgnewname=md5($book_img.time()).$extension;
// Code for move image into directory

if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{
    move_uploaded_file($_FILES["bookpic"]["tmp_name"],"book_img/".$imgnewname);
$sql="update  books set book_image=:imgnewname where id=:book_id";
$query = $dbh->prepare($sql);
$query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
$query->bindParam(':book_id',$book_id,PDO::PARAM_STR);
$query->execute();
unlink($cpath);
echo "<script>alert('Book image updated successfully');</script>";
echo "<script>window.location.href='manage-books.php'</script>";

}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Edit Book</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add Book</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md12 col-sm-12 col-xs-12">
<div class="panel panel-info">
<div class="panel-heading">
Book Info
</div>
<div class="panel-body">
<form role="form" method="post" enctype="multipart/form-data">
<?php 
$book_id=intval($_GET['book_id']);
$sql = "SELECT books.book_name,books.id as book_id,books.book_image from  books  where books.id=:book_id";
$query = $dbh -> prepare($sql);
$query->bindParam(':book_id',$book_id,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<input type="hidden" name="curremtimage" value="<?php echo htmlentities($result->book_image);?>">
<div class="col-md-6">
<div class="form-group">
<label>Book Image</label>
<img src="book_img/<?php echo htmlentities($result->book_image);?>" width="100">
</div></div>

<div class="col-md-6">
<div class="form-group">
<label>Book Name<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="book_name" value="<?php echo htmlentities($result->book_name);?>" readonly />
</div></div>

<div class="col-md-6">  
 <div class="form-group">
 <label>Book Picture<span style="color:red;">*</span></label>
 <input class="form-control" type="file" name="bookpic" autocomplete="off"   required="required" />
 </div>
    </div>
 <?php }} ?><div class="col-md-12">
<button type="submit" name="update" class="btn btn-info">Update </button></div>

                                    </form>
                            </div>
                        </div>
                            </div>

        </div>
   
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
