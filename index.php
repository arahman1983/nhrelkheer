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
       
    <!-- ====== Slide ======== -->
    <div class="slide">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <?php
            $slide_sql = "SELECT * FROM `slide` ORDER BY `id` DESC LIMIT 0,4";
            $slide_result = $conn->query($slide_sql);
            ?>
                    <ol class="carousel-indicators">
                    <?php
                    while($slide_row = $slide_result->fetch_assoc()){
                    echo '<li data-target="#carouselExampleIndicators" data-slide-to="0" class="indicatorss "></li>';
                    }
                    ?>
                    </ol>
                    <div class="carousel-inner">
                    <?php
                    while($slide_row = $slide_result->fetch_assoc()){
                    echo '
                    <div class="carousel-item ">
                        <img class="d-block w-100" src="uploads/'.$slide_row['pic'].'" alt="'.$slide_row['pic'].'">
                        ';
                        if(!empty($slide_row['brief'])){
                            echo'
                            <div class="carousel-caption d-none d-md-block">
                                    <p>'.$slide_row['brief'].'</p>
                                    <a href="'.$slide_row['slink'].'" class="sli-btn">المزيد</a>    
                                </div>
                            ';
                        }
                        echo'
                    </div>';
                    }
                    ?>  
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
    </div>
    <!-- ====== Sectors ======== --> 
    <div class="container my-5">
        <div class="row">
            <?php
            $sector_sql = "SELECT * FROM `sectors` ORDER BY `id` ASC";
            $sector_result = $conn->query($sector_sql);
            while($sector_row = $sector_result->fetch_assoc()){
                echo'
                <a href="sector.php?id='.$sector_row['id'].'" class="col-md-3 col-sm-6 sectorDiv">
                <img src="uploads/'.$sector_row['sector_icon'].'" class="img-responsive mx-auto">
                <h3>'.$sector_row['sector_title'].'</h3>
                </a>
                ';
            }
            ?>
        </div>
    </div>
    <!-- ====== Projects Section ======== --> 
    <div class="row pro-section">
        <div class="container py-3">
            <!--sweeper here-->
            <div class="swiper-container row">
                    <div class="swiper-wrapper">
              <?php
              $project_sql = "SELECT * FROM `projects` ORDER BY `id` DESC";
              $project_result = $conn->query($project_sql);
              while($project_row = $project_result->fetch_assoc()){
                  echo'
                  <div class="swiper-slide">
                         <a href="project.php?p=projects&id='.$project_row['id'].'">
                            <img src="uploads/'.$project_row['project_pic'].'" class="img-thumbnail"><h5 class="text-center">'.$project_row['project_title'].'</h5>
                         </a>
                    </div>
                  ';
              }
              ?>
                    </div>
                    <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
<!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>

    <!-- ====== offers ======== --> 
    <div class="container offers">
        <div class="row my-5">
            <div class="col-md-4 bt-order"></div>
            <div class="col-md-4">
                    <div class="section-head">
                        <h2> عروض نهر الخير </h2>
                    </div>
                </div>
            <div class="col-md-4 bt-order"></div>
        </div>
        <div class="row">
            <?php
            $project_sql = "SELECT * FROM `offers` ORDER BY `id` DESC";
            $project_result = $conn->query($project_sql);
            while($project_row=$project_result->fetch_assoc()){
                echo'
                <div class="col-md-6 py-2">
                <div class="row">
                <div class="col-8">
                    <h5>'.$project_row['offers_title'].'</h5>
                    <p class="text-justify"> 
                    '.$project_row['offers_brief'].'
                        <a href="offer.php?p=projects&id='.$project_row['íd'].'" class="btn btn-warning btn-sm">التفاصيل</a>
                    </p>  
                </div>
                <div class="col-4">
                    <img src="uploads/'.$project_row['offers_pic'].'">
                </div>
            </div>
            </div>    
                ';
            }
            ?>
            <div class="col-md-4 mx-auto my-5">
            <a href="offers.php?p=projects&project=all" class="btn btn-warning btn-lg btn-block text-center">كل العروض</a>
        </div>
        </div>
    </div>

    <!-- ====== News ======== --> 
    <div class="container my-3">
            <div class="row my-5">
                    <div class="col-md-4 bt-order"></div>
                    <div class="col-md-4">
                            <div class="section-head">
                                <h2> أخبار نهر الخير </h2>
                            </div>
                        </div>
                    <div class="col-md-4 bt-order"></div>
                </div>
            <div class="row">
                <?php
                $news_sql = "SELECT * FROM `news` ORDER BY `id` DESC";
                $news_result = $conn->query($news_sql);
                while($news_row = $news_result->fetch_assoc()){
                    echo'
                    <div class="col-md-4">
                    <h5 class="news-title"> <img src="imgs/icon.png"> '.$news_row['title'].'</h5>
                    <p class="text-justify">'.$news_row['brief'].' 
                        <a href="details.php?p=news&id='.$news_row['id'].'" class="btn btn-warning btn-sm my-3" style="float:left">التفاصيل</a>
                    </p>
                    </div>    
                    ';
                }
                ?>

                </div>
        </div>

    <!-- ====== last section ======== --> 
    <div class="container py-3">
            <div class="row">
                <div class="col-md-4 py-3">
                    <?php
                    $vid_sql = "SELECT * FROM `videos_library` WHERE `onHome` = 1 ORDER BY `vid` DESC";
                    $vid_result = $conn->query($vid_sql);
                    if($vid_row = $vid_result->fetch_assoc()){
                        echo'
                        <h5>'.$vid_row['vTitle'].'</h5>
                        <iframe class="vedioemb" src="'.$vid_row['vUrl'].'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>    
                        ';
                    }
                    ?>
                <a href="vedplayer.php?p=media" class="btn btn-success btn-block">المزيد من الميديا</a>
                </div>
                
                <div class="col-md-4 bg-green py-3">
                    <h5 class="text-center">شارك في قائمتنا يصلك كل جديد</h5>
                    <p class="text-justify my-2">
                            ليصلك الجديد حول عروضنا وأخبارنا من فضلك أضف رقم تليفونك أو بريدك الالكتروني 
                    </p>
                    <?PHP
                    if(isset($_POST['nletter'])){
                        $email = $_POST['mails'];
                        $phone = $_POST['phone'];
                        $whats = $_POST['whatsapp'];
                        $sql = "INSERT INTO `email_list`(`email`, `phone`, `whatsapp`) VALUES ('".$email."','".$phone."','".$whats."') ";
                        if ($conn->query($sql) === TRUE) {
                            echo "<p class='text-center py-2'>شكرُا لمتابعتك لنا</p>
                            ";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        
                    }
                    ?>
                    <form id="letter_form" method="POST" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <input type="email" name="mails" class="form-control" placeholder="البريد الاليكتروني">
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" class="form-control" placeholder="رقم الهاتف">
                            </div>
                            <div class="form-group">
                                    <input type="tel"  name="whatsapp" class="form-control" placeholder="رقم الواتس آب">
                                </div>
                            
                                <div class="form-group">
                            <input type="submit" name="nletter" class="btn btn-warning btn-block" value="أرسل استفسارك">
                                        </div>
                        </form>
                </div>

                <div class="col-md-4 py-3">
                    <h5 class="text-center">أرسل استفسارك هنا</h5>
                    <br>

                    <?php
                    if(isset($_POST['submit'])){
                        $email = $_POST['mails'];
                        $phone = $_POST['phone'];
                        $details = $_POST['details'];
                        
                        
                        $to = "nahrelkhair@gmail.com,nahrelkhair@yahoo.com";
                        $subject = "استفسار من الموقع";

                        $message = "
                        <html>
                        <head>
                        <title>استفسار من الموقع</title>
                        </head>
                        <body>
                        <p>".$_POST['details']."</p>

                        <small>Phone : ".$phone."</small><br>
                        <small>Email : ".$email."</small>
                        </body>
                        </html>
                        ";

                        // Always set content-type when sending HTML email
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                        // More headers
                        $headers .= $_POST['mails'] . "\r\n";

                        mail($to,$subject,$message,$headers);		
                        echo 'شكرا لمراسلتنا';
                        
                    }
                    ?>
                
                    <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="form-group">
                            <input type="email" name="mails" class="form-control" placeholder="البريد الاليكتروني">
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" class="form-control" placeholder="رقم الهاتف">
                        </div>
                        
                        <div class="form-group">
                        <textarea class="form-control"  name="details" placeholder="استفسارك هنا"></textarea>
                            </div>
                            <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-success btn-block" value="أرسل استفسارك">
                                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ====== Footer ======== --> 
  <?php include_once('footer.php');  ?>


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