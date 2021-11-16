<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Twitter</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <a class="navbar-brand" href="http://192.168.64.2/twit">Twitter</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                  <li class="nav-item">
                      <a class="nav-link " href="?page=timeline">Your Timeline</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="?page=yourtweets">Your tweets</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="?page=publicprofiles">Public Profiles</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="?page=userinfo">User Info</a>
                  </li>
            </ul>
            <div class="form-inline my-2 my-lg-0">


              <?php
               if(array_key_exists("id",$_SESSION)){
                 $query = "SELECT * FROM `users` where id=".mysqli_real_escape_string($link,$_SESSION["id"]);
                 $result = mysqli_query($link,$query);
                 $row  = mysqli_fetch_assoc($result);

                 ?>
                 <img src="man-user.png" height="22px" width="22px">
                  <a class="nav-link " style="color:white"><?php echo $row["email"]; ?> </a>
                  <a class="btn btn-outline-success my-2 my-sm-0" href="?function=logout" >Log Out</a>

              <?php }else{  ?>
                    <button class="btn btn-outline-success my-2 my-sm-0"  data-toggle="modal" data-target="#exampleModal">Login/Singup</button>

              <?php  } ?>


            </div>
        </div>
    </nav>

<div id="gap"></div>
