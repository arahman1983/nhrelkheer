<?PHP
include_once 'config.php';
$id = $_GET['id'];

$sql = "SELECT * FROM `sectors` WHERE `id` LIKE '".$id."'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc())
$title ="شركة نهر الخير ".$row['sector_title'];
$desc = $row['sector_brief'];
$key_w= $row['sector_keywrds'];
include_once('header.php');
?>

<!DOCTYPE html>
<html>
<body>
      
    <!-- ============ Content box =============== -->
    <div class="container detailsPage my-5">
        <?PHP
        $sql = "SELECT * FROM `sectors` WHERE `id` LIKE '".$id."'";
        $result = $conn->query($sql);
        if($row = $result->fetch_assoc())
        echo'
        <h2 class="my-3">'.$row['sector_title'].'</h2>
        <div class="row">
            <div class="col-md-8">
                    <p class="text-justify">'.$row['sector_details'].'</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="uploads/'.$row['sector_pic'].'" class="detailsImge">
            </div>
        </div>
        ';
        ?>
        
        
    </div>
        <!-- ====== Footer ======== --> 
      <?php include_once('footer.php'); ?>  
        </body>
        </html>