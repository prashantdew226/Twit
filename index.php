<?php
    include("functions.php");
    include("views/header.php");
    //main content part of each page

    if(isset($_GET) && array_key_exists('page',$_GET)&& $_GET["page"]=="timeline"){
        include("views/timeline.php");
    }else if(isset($_GET) && array_key_exists('page',$_GET)&& $_GET["page"]=="yourtweets"){
        include("views/yourtweets.php");
    }else if(isset($_GET) && array_key_exists('page',$_GET)&& $_GET["page"]=="search"){
        include("views/search.php");
    }else if(isset($_GET) && array_key_exists('page',$_GET)&& $_GET["page"]=="publicprofiles"){
        include("views/publicProfiles.php");
    }else if(isset($_GET) && array_key_exists('page',$_GET)&& $_GET["page"]=="userinfo"){
        include("views/userInfo.php");
    }else{
        include("views/home.php");
    }

    include("views/footer.php");

 ?>
