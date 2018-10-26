<?PHP
include_once 'config.php';
$type = $_GET['p'];
$ids = $_GET['id'];

$sql = "SELECT * FROM `offers` WHERE `id` LIKE '".$ids."'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc())
$title ="شركة نهر الخير :: ".$row['offers_title'];
$desc = $row['offers_brief'];
$key_w= $row['offers_keywrds'];
include_once('header.php');
?>

<!DOCTYPE html>
<html>
<body>
       
    <!-- ============ Content box =============== -->
    <?php
       $sql = "SELECT * FROM `offers` WHERE `id` LIKE '".$ids."'";
        $result = $conn->query($sql);
        if($row = $result->fetch_assoc())        
        echo '
    <div class="container-fluid coverpage py-3" style="background:url(\'uploads/'.$row['offers_pic'].'\')">
        <h2 class="my-3">'.$row['offers_title'].'</h2>
    </div>
    <div class="container detailsPage my-5">
        
        <div class="row">
            <div class="col-md-8">
                    <p class="text-justify"> '.$row['offers_details'].' </p>
            </div>
            <div class="col-md-4 text-center">
                <img src="uploads/'.$row['offers_pic'].'" class="detailsImge">
            </div>
        </div>
    
        ';

        ?>
    </div>    
        <!-- ====== Footer ======== --> 
        <?php
        include_once('footer.php');
        ?>
    
        </body>
        </html>