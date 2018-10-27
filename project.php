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
        <?php
        $sql = "SELECT * FROM `projects` WHERE `id` LIKE '".$id."'";
        $result = $conn->query($sql);
        if($row = $result->fetch_assoc())
        echo'
        <div class="container-fluid coverpage py-3" style="background:url(\'uploads/'.$row['project_pic'].'\')">
        <h2 class="my-3">'.$row['project_title'].'</h2>
        </div>
  
        <div class="container detailsPage my-5">
        <div class="row">
            <div class="col-md-8">
                    <p class="text-justify">'.$row['project_details'].'</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="uploads/'.$row['project_pic'].'" class="detailsImge">
                <div class="row my-3">';
                $offers_sql = "SELECT * FROM `offers` WHERE `project` LIKE '%".$row['project_title']."%' ORDER BY `id` DESC";
                $offers_result = $conn->query($offers_sql);
                while($offers_row = $offers_result->fetch_assoc()){

                    echo '<a href="offer.php?id='.$offers_row['id'].'" class="btn btn-success m-2">'.$offers_row['offers_title'].'</a>';
                }

                echo '</div>
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