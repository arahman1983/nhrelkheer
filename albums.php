<?PHP
include_once 'config.php';

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
        
    <!-- ============ Content box =============== -->
    <div class="container detailsPage my-5">
             <div class="row">
                 <?php
                    $sql_albums = "SELECT * FROM `photo_albums` ORDER BY `id` DESC";
                    $albums_result = $conn->query($sql_albums);
                    while($albums_row = $albums_result->fetch_assoc()){
                        echo'
                        <a href="albumpics.php?p=media&album='.$albums_row['album_title'].'" class="col-md-4 col-sm-6 my-3">
                        <div class="card text-center">
                            <img src="uploads/'.$albums_row['album_Imge'].'">
                            <h5>'.$albums_row['album_title'].'</h5>
                        </div>
                        </a>
                        ';
                    }
                 ?>
        </div>
    </div>
        <!-- ====== Footer ======== --> 
       <?php
       include_once('footer.php');
       ?>
        </body>
        </html>