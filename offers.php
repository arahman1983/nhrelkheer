<?PHP
include_once 'config.php';
$project = $_GET['project'] ;

$sql = "SELECT * FROM `about` ORDER BY `id` DESC";
$result = $conn->query($sql);
if($row = $result->fetch_assoc())
$title ="شركة نهر الخير ";
$desc = $row['brief'];
$key_w= $row['key_wrds'];
include_once('header.php');
?>
<!DOCTYPE html>
<html>
<body>
       <?php

        echo '<div class="container detailsPage my-5">
            <div class="row my-5">
                    <div class="col-md-4 bt-order"></div>
                    <div class="col-md-4 mx-auto">
                            <div class="section-head">
                            <h2>عروض شركة نهر الخير</h2>
                           </div>
                        </div>
                    <div class="col-md-4 bt-order"></div>
                </div>
        <div class="row">';

        if($project == 'All'){
            $sql = "SELECT * FROM `offers` ORDER BY `id` DESC";
        }else{
            $sql = "SELECT * FROM `offers` WHERE `project` LIKE '%".$project."%' ORDER BY `id` DESC";
        }
        $sub_result = $conn->query($sql);
        echo $sub_result->fetch_assoc();
        while($sub_row = $sub_result->fetch_assoc()){
            echo'
            <a href="offer.php?p=projects&id='.$sub_row['id'].'" class="col-md-4 col-sm-6 my-3">
            <div class="card text-center">
                <img src="uploads/'.$sub_row['offers_pic'].'">
                <h5>'.$sub_row['offers_title'].'</h5>
                <p class="text-justify m-2">
                '.$sub_row['offers_brief'].'
                </p>
            </div>
        </a>
            ';
        }
        echo'</div></div>';

        ?>
    <!-- ============ Content box =============== -->

        <!-- ====== Footer ======== --> 
       <?php
       include_once('footer.php');
       ?>
        </body>
        </html>