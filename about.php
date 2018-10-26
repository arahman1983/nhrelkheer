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
        <?php
        $sql = "SELECT * FROM `about` ORDER BY `id` DESC";
        $result = $conn->query($sql);
        if($row = $result->fetch_assoc())
        echo '
        <h2 class="my-3">'.$row['title'].'</h2>
        
        <div class="row">
            <div class="col-md-8">
                    <p class="text-justify"> '.$row['details'].' </p>
            </div>
            <div class="col-md-4 text-center">
                <img src=uploads/'.$row['pic'].'" class="detailsImge">
            </div>
        </div>
    
        ';

        ?>
    </div>    
        <!-- ====== Footer ======== --> 
        <?php
        include_once('footer.php');
        ?>
        
        
        
        
        
        
        
        
        
        
        
        
            <script src="js/jquery-3.3.1.slim.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/bootstrap-4-navbar.js"></script>
            <script>
            $('.carousel').carousel({
          interval: 2000
        })
        </script>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.1';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
         <!-- Swiper JS -->
         <script src="dist/js/swiper.min.js"></script>
        
         <!-- Initialize Swiper -->
         <script>
         var swiper = new Swiper('.swiper-container', {
             pagination: '.swiper-pagination',
             paginationClickable: true,
             slidesPerView: 3,
             spaceBetween: 50,
             loop: true,
             breakpoints: {
                 1024: {
                     slidesPerView: 3,
                     spaceBetween: 40
                 },
                 768: {
                     slidesPerView: 2,
                     spaceBetween: 30
                 },
                 640: {
                     slidesPerView: 1,
                     spaceBetween: 20
                 },
                 320: {
                     slidesPerView: 1,
                     spaceBetween: 10
                 }
             }
         });
         </script>
        
        </body>
        </html>