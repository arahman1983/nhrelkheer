<?PHP
include_once 'config.php';
$id = $_GET['id'];

$sql = "SELECT * FROM `projects` WHERE `id` LIKE '".$id."'";
$result = $conn->query($sql);
if($row = $result->fetch_assoc())
$title ="شركة نهر الخير ".$row['project_title'];
$desc = $row['project_brief'];
$key_w= $row['project_keywrds'];
include_once('header.php');
?>

<!DOCTYPE html>
<html>
<body>
        <!-- ============ Content box =============== -->
    <div class="container detailsPage my-5">
        <?php
        $sql = "SELECT * FROM `projects` WHERE `id` LIKE '".$id."'";
        $result = $conn->query($sql);
        if($row = $result->fetch_assoc())
        echo'
        <h2 class="my-3">'.$row['project_title'].'</h2>
        <div class="row">
            <div class="col-md-8">
                    <p class="text-justify">'.$row['project_details'].'</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="uploads/'.$row['project_pic'].'" class="detailsImge">
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