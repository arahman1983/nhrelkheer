<!DOCTYPE html>
<html>
<body>
        <!-- ====== Footer ======== --> 
    <footer>
        <div class="container-fluid footer1">
            <div class="container">
                <div class="row py-3">
                    <div class="col-md-4">
                        <h5 class="py-3">اتصل بنا</h5>
                        <?php
                        $branch_query = "SELECT * FROM `branches` ORDER BY `id` ASC LIMIT 0,1";
                        $branch_result = $conn->query($branch_query);
                        if($branch_row = $branch_result->fetch_assoc()){
                            echo'
                            <i class="fas fa-map-marker-alt"></i> <span> '.$branch_row['address'].' </span><br>   
                            <p class="new-dir">
                            ';
                            $phone_query = "SELECT * FROM `phones` WHERE `branch` Like '%".$branch_row['branch_name']."%' ";
                            $phone_result = $conn -> query($phone_query);
                            while($phone_row = $phone_result->fetch_assoc()){
                                echo'
                                <a href="tel:'.$phone_row['phone_no'].'"> '.$phone_row['phone_no'].' '.$phone_row['phone_type'].'</a><br>
                                ';
                            }
                            $emails_query = "SELECT * FROM `co_emails` WHERE `branch` Like '%".$branch_row['branch_name']."%' ";
                            $emails_result = $conn->query($emails_query);
                            while($emails_row = $emails_result->fetch_assoc()){
                                echo'
                                <a href="mailto:'.$emails_row['email'].'">'.$emails_row['email'].' <i class="fas fa-at"></i></a>
                                ';
                            }
                            echo '</p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                   <!-- InstaWidget -->
<a href="https://instawidget.net/v/user/nahrelkhair" id="link-424a20ad5548041c871de25827f60fe0c95c55eb2d3f5fdc44eec8b4ee930053">@nahrelkhair</a>
<script src="https://instawidget.net/js/instawidget.js?u=424a20ad5548041c871de25827f60fe0c95c55eb2d3f5fdc44eec8b4ee930053&width=100%"></script>
                    </div>
                    <div class="col-md-4">
                    <h5 class="py-3">تابعونا على</h5>
                    <div class="social text-center">
                    <?PHP
                    $social_query = "SELECT * FROM `social` ORDER BY `id` ASC ";
                    $social_result = $conn -> query($social_query);
                    while($social_links = $social_result->fetch_assoc()){
                        echo'<a href="'.$social_links['social_links'].'">'.$social_links['social_type'].'</a>';
                    }
                    ?>
                </div> 

                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid footer2 py-3">
            جميع الحقوق محفوظة لشركة نهر الخير 2018
            </div>
    </footer>


    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-4-navbar.js"></script>

    
    <!-- Swiper JS -->
    <script src="dist/js/swiper.min.js"></script>

</body>
</html>