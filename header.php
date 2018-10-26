<?PHP
include_once 'config.php';
require_once( dirname(__FILE__) . '/functions.php');

if (!empty($_GET['p'])) {
	$p = $_GET['p'];	
}else{
	$p="";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php the_title();?></title>
        <meta name="description" content="<?php desc();?>">
        <meta name="keywords" content="<?php key_w();?>">
        <link rel="icon" href="imgs/fav.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="dist/css/swiper.min.css">
        <link rel="stylesheet" href="css/nahrStyle.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


<body>
       

    <header>
    <div class="container-fluid top-bar" style="padding-top:0.5rem; padding-bottom: 0.5rem">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                <marquee direction="right" speed="normal" onmouseover="this.stop();" onmouseout="this.start();">
                    <?php
                    $query = "SELECT * FROM `news` ORDER BY `id` DESC LIMIT 0,5";
                    $mresult = $conn -> query($query);
                    while($mrow = $mresult->fetch_assoc()){
                        echo '<a href="details.php?p=news&id='.$mrow['id'].'">'.$mrow['title'].'</a>  ..';
                    }
                    ?>
                </marquee>
                </div>
                <div class="col-md-4 social">
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
    <div class="container-fluid logo-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-3"><img class="arlogo" src="imgs/logo.png"></div>
                <div class="col-md-6">
                <div class="col-md-10 mx-auto my-3" style="height:70px; overflow:hidden">
                <?PHP
                $advquery = "SELECT * FROM `top_adv` ORDER BY 'id' DESC Limit 0,1";
                $advresult = $conn->query($advquery);
                if($advrow = $advresult->fetch_assoc()){
                    echo'
                    <a href="'.$advrow['link'].'" target="_blank">
                    <img src="uploads/'.$advrow['pic'].'" alt="'.$advrow['title'].'" />
                    </a>  
                    ';
                }
                ?>
                </div>
            </div>
                <div class="col-md-3"><img class="enlogo" src="imgs/logoe.png"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid nav-bar">
        <div class="container">
        <nav class="navbar navbar-expand-lg">
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mx-auto">
                <?php
                if($p == 'home'){
                    echo '<li class="nav-item activel">';
                }else{
                    echo'<li class="nav-item">';
                }
                ?>
                  <a class="nav-link" href="index.php?p=home">الرئيسية</a>
                </li>
                <?php
                if($p == 'about'){
                    echo '<li class="nav-item activel">';
                }else{
                    echo'<li class="nav-item">';
                }
                ?>
                  <a class="nav-link" href="about.php?p=about">من نحن</a>
                </li>
                <?PHP
                if($p == 'sector'){
                    echo '<li class="nav-item dropdown activel">';
                }else{
                    echo'<li class="nav-item dropdown">';
                }
                ?>
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    قطاعات الشركة
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <?PHP
                        $sector_query = "SELECT * FROM `sectors` ORDER BY `id` ASC";
                        $sector_result = $conn -> query($sector_query);
                        while($sector_row = $sector_result->fetch_assoc()){
                            echo'
                            <a class="dropdown-item" href="sector.php?p=sector&id='.$sector_row['id'].'">
                            '.$sector_row['sector_title'].'</a>
                            <div class="dropdown-divider"></div>
                            ';
                        }
                      ?>
                  </div>
                </li>
                <?PHP
                if($p == 'projects'){
                    echo '<li class="nav-item dropdown activel">';
                }else{
                    echo'<li class="nav-item dropdown">';
                }
                ?>
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          المشاريع
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?PHP
                        $projects_query = "SELECT * FROM `projects` ORDER BY `id` DESC";
                        $projects_results = $conn->query($projects_query);
                        while ($projects_row = $projects_results->fetch_assoc()){
                            echo'<a class="dropdown-item" href="project.php?p=projects&id='.$projects_row['id'].'">
                            '.$projects_row['project_title'].'</a>
                            <div class="dropdown-divider"></div>';
                        }
                        ?>
                    </div>
                      </li>
                      <?php
                        if($p == 'study'){
                            echo '<li class="nav-item activel">';
                        }else{
                            echo'<li class="nav-item">';
                        }
                        ?>
                            <a class="nav-link" href="sub.php?p=study">دراسات الجدوى</a>
                     </li>
                     <?PHP
                        if($p == 'media'){
                            echo '<li class="nav-item dropdown activel">';
                        }else{
                            echo'<li class="nav-item dropdown">';
                        }
                        ?>
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              ميديا
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="albums.php?p=media">مكتبة الصور</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="vedplayer.php?p=media">مكتبة الفيديو</a>
                              </div>
                          </li>
                          <?php
                            if($p == 'contact'){
                                echo '<li class="nav-item activel">';
                            }else{
                                echo'<li class="nav-item">';
                            }
                            ?>
                                <a class="nav-link" href="conact.php?p=contact">تواصل معنا</a>
                         </li>

                         <?php
                            if($p == 'blog'){
                                echo '<li class="nav-item activel">';
                            }else{
                                echo'<li class="nav-item">';
                            }
                            ?>
                                <a class="nav-link" href="sub.php?p=blog">معلومة زراعية</a>
                         </li>
              </ul>
            </div>
          </nav>
        </div>
    </div>
    </header>

</body>
</html>