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
                $branches_sql = "SELECT * FROM `branches` ORDER BY `id` ASC";
                $branches_result = $conn->query($branches_sql);
                while($branches_row = $branches_result->fetch_assoc()){
                    echo '
                    <div class="row my-3">
                    <div class="col-md-8">
                    <h2 class="my-3">'.$branches_row['branch_name'].'</h2>
                    <i class="fas fa-map-marker-alt"></i> <span>'.$branches_row['address'].' </span><br>
                    <p class="new-dir">'
                    ;    
                        $contact_sql = "SELECT * FROM `phones` WHERE `branch` LIKE '%".$branches_row['branch_name']."%'";
                        $contact_result = $conn->query($contact_sql);
                        while($contact_row = $contact_result->fetch_assoc){
                        echo'
                        <a href="tel:'.$contact_row['phone_no'].'"> '.$contact_row['phone_no'].' '.$contact_row['phone_type'].'></i></a><br>
                        ';
                        }
                        $email_sql = "SELECT * FROM `co_emails` WHERE `branch` LIKE '%".$branches_row['branch_name']."%'";
                        $email_result = $conn->query($email_sql);
                        while($email_row = $email_result->fetch_assoc){
                        echo'
                        <a href="mailto:'.$email_row['email'].'">'.$email_row['email'].' <i class="fas fa-at"></i></a><br>
                        ';
                        }
                        echo'
                        </p>
                        </div>';
                  
                        echo'<div class="col-md-4 img-thumbnail">
                                <iframe src="'.$branches_row['map_code'].'" frameborder="0" style="border:0; width:100%; height: 250px" allowfullscreen></iframe>
                        </div>
                    </div>
                    ';
                }
                ?>
            


            <div class="row my-3">
                
                    <div class="col-md-8 mx-auto">
                            <h2 class="my-3">أرسل ايميل</h2>
                            <?php
                                 if(isset($_POST['submit'])){
                                    $email = $_POST['mails'];
                                    $msTitle = $_POST['msTitle'];
                                    $details = $_POST['details'];
                                    
                                    
                                    $to = "nahrelkhair@gmail.com,nahrelkhair@yahoo.com";
                                    $subject = $_POST['msTitle'];
            
                                    $message = "
                                    <html>
                                    <head>
                                    <title>".$_POST['msTitle']."</title>
                                    </head>
                                    <body>
                                    <p>".$_POST['details']."</p>
            
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

                            <form class="my-3" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-group">
                                    <label> البريد الالكتروني</label>
                                    <input class="form-control" name="mails" type="email" placeholder="example@domain.ext" required>
                                </div>
                                <div class="form-group">
                                        <label> عنوان الرسالة</label>
                                        <input class="form-control" name="msTitle" type="text" placeholder="عنون الرسالة" required>
                                </div>
                                <div class="form-group">
                                        <label>نص الرسالة</label>
                                        <textarea class="form-control" name="details" placeholder="نص الرسالة" rows="5"></textarea>
                                </div>
                                
                                <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" value="أرسل الرسالة">
                                </div>
                            </form>
                            
                    </div>
                    
                </div>
    </div>
        <!-- ====== Footer ======== --> 
        <?php
        include_once('footer.php');
        ?>
        </body>
        </html>