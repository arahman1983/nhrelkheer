<?PHP
include_once 'config.php';
@$type = $_GET['p'];

$sql = "SELECT * FROM `about` ORDER BY `id` DESC";
$result = $conn->query($sql);
if($row = $result->fetch_assoc())
$title ="شركة نهر الخير ".$row['title'];
$desc = $row['brief'];
$key_w= $row['key_wrds'];
include_once('header.php');
?>
<!DOCTYPE html>
<html>
<body>
       <?php
        
        $sub_sql = "SELECT * FROM `".$type."` ORDER BY `id` DESC ";
        $sub_result = $conn->query($sub_sql);
        if($sub_row = $sub_result->fetch_assoc()){
        echo '<div class="container-fluid coverpage py-3" style="background:url(\'uploads/'.$row['pic'].'\')">';
        }
            switch($type){
                case "study":
                echo '<h2 class="my-3"> دراسات الجدوى </h2>';
                break;
                case "blog":
                echo '<h2 class="my-3"> معلومة زراعية </h2>';
                break;
                default :
                echo 'لا يوجد قسم بهذا الاسم';
            }    
            echo'</div>
            <div class="row">';
        

        $sub_sql = "SELECT * FROM `".$type."` ORDER BY `id` DESC ";
        $sub_result = $conn->query($sub_sql);
        while($sub_row = $sub_result->fetch_assoc()){
            echo'
            <a href="details.php?p='.$type.'&id='.$sub_row['id'].'" class="col-md-4 col-sm-6 my-3">
            <div class="card text-center">
                <img src="uploads/'.$sub_row['pic'].'">
                <h5>'.$sub_row['title'].'</h5>
                <p class="text-justify m-2">
                '.$sub_row['breif'].'
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