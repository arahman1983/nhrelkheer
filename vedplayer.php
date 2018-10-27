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

<head>
    <script type="application/javascript" src="https://unpkg.com/react@16.0.0/umd/react.production.min.js"></script>
    <script type="application/javascript" src="https://unpkg.com/react-dom@16.0.0/umd/react-dom.production.min.js"></script>
    <script type="application/javascript" src="https://unpkg.com/babel-standalone@6.26.0/babel.js"></script>
</head>
<body>

    <div id="vplayer" class="my-3"></div>





<?php
    include_once('footer.php');
?>



    <script type="text/babel">

    <?PHP
    $ved_sql = "SELECT * FROM `videos_library` ORDER BY `vid` ASC";
    $ved_res = $conn->query($ved_sql);
    echo'let vedLibrary = [';
    while($ved_row = $ved_res->fetch_assoc()){
        echo'
        {vid: '.$ved_row['vid'].', vUrl:"'.$ved_row['vUrl'].'", vTitle:"'.$ved_row['vTitle'].'", vImage:"uploads/'.$ved_row['vImage'].'"},
        ';
    }
    echo'];';
    ?>


class VPApp extends React.Component{
    state = {
        vid : vedLibrary[0].vid,
        vTitle : vedLibrary[0].vTitle,
        vUrl : vedLibrary[0].vUrl,
        vImage : vedLibrary[0].Image
    }
    changeVideo = (optionId) => {
    this.setState(()=>{
        return(
            this.state = {
            vid : vedLibrary[optionId].vid,
            vTitle : vedLibrary[optionId].vTitle,
            vUrl : vedLibrary[optionId].vUrl,
            vImage : vedLibrary[optionId].Image 
            }
        )
    })
      
    }
    render(){
        return(
            <div class="container">
            <Vplayer vUrl = {this.state.vUrl} vTitle = {this.state.vTitle} />
            <Valbums onChangeVideo ={this.changeVideo} />
            </div>
        )
    }
}

const Vplayer = (props) =>(
            <div className = "row">
                    <div class="row my-5">
                    <div class="col-md-4 bt-order"></div>
                    <div class="col-md-4">
                            <div class="section-head">
                                <h2> {props.vTitle}</h2>
                            </div>
                        </div>
                    <div class="col-md-4 bt-order"></div>
                </div>
                <div className = "col-md-8 mx-auto text-center">
                        <iframe className="vedioplayer"
                            src={props.vUrl}
                            frameborder="0" allow="autoplay; encrypted-media" 
                            allowfullscreen>
                        </iframe>
                </div>
            </div>  
        );

const Valbums = (props) =>(
    
        <div className="container">
                <div className="row">
        {
            vedLibrary.map((anObjectMapped, index) => {
            return (
            <a key={anObjectMapped.vid}
            id="vedsrc"
             onClick ={(e) => {props.onChangeVideo(index)}}
              className="col-md-3 col-sm-6 my-3">
                <div key={anObjectMapped.vTitle} className="card text-center">
                        <img src={anObjectMapped.vImage} alt={anObjectMapped.vImage} />
                    <h5>{anObjectMapped.vTitle}</h5>
                </div>
            </a>
            );
            })
        }
    </div>
    </div>
    );

const appDiv = document.getElementById('vplayer');
ReactDOM.render(<VPApp />, appDiv );
</script>
</body>