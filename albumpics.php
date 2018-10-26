<?PHP
include_once 'config.php';
$type = $_GET['p'];
$ids = $_GET['id'];

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
    <head>
<style>
.swiper-container {
        width: 100%;
        height: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    .swiper-slide {
        background-size: cover;
        background-position: center;
    }
</style>
</head>
<body>
        
    <!-- ============ Content box =============== -->
    <div class="container detailsPage my-5">
    <div class="row my-5">
                    <div class="col-md-4 bt-order"></div>
                    <div class="col-md-4">
                            <div class="section-head">
                                <?php
                                  $album = $_GET['album'];
                                  echo'<h2> '.$album.' </h2>';
                                ?>
                                
                            </div>
                        </div>
                    <div class="col-md-4 bt-order"></div>
                </div>
                <div class="row">
                <div class="container mx-auto img-thumbnail">
                <div class="swiper-container gallery-top">
                <div class="swiper-wrapper">
        <?php
            $pic_sql = "SELECT * FROM `photos` WHERE `album_name` LIKE '%".$album."%'";
            $pic_result = $conn->query($pic_sql);
            while($pic_row = $pic_result->fetch_assoc()){
                echo'
                <div class="swiper-slide" style="background-image:url(\'uploads/'.$pic_row['images'].'\')"></div>
                ';
            }
        ?>
                                </div>
                                <!-- Add Arrows -->
                                <div class="swiper-button-next swiper-button-white"></div>
                                <div class="swiper-button-prev swiper-button-white"></div>
                            </div>
                            <div class="swiper-container gallery-thumbs" style="height:150px">
                                <div class="swiper-wrapper">
                                <?php
                                $pic_sql = "SELECT * FROM `photos` WHERE `album_name` LIKE '%".$album."%'";
                                $pic_result = $conn->query($pic_sql);
                                while($pic_row = $pic_result->fetch_assoc()){
                                    echo'
                                    <div class="swiper-slide" style="background-image:url(\'uploads/'.$pic_row['images'].'\')"></div>
                                    ';
                                }
                            ?>
                                </div>
                            </div>
                </div>
            </div>     

        
    </div>
        <!-- ====== Footer ======== --> 
      
        <?php
        include_once('footer.php');
        ?>
         <!-- Initialize Swiper -->
         <script>
                var galleryTop = new Swiper('.gallery-top', {
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    spaceBetween: 10,
                    loop:true,
                    loopedSlides: 5, //looped slides should be the same     
                });
                var galleryThumbs = new Swiper('.gallery-thumbs', {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    touchRatio: 0.2,
                    loop:true,
                    loopedSlides: 5, //looped slides should be the same
                    slideToClickedSlide: true
                });
                galleryTop.params.control = galleryThumbs;
                galleryThumbs.params.control = galleryTop;
                
        </script>        
        </body>
        </html>