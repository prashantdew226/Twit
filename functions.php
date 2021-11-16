<?php
        session_start();
        $link = mysqli_connect("localhost","root","","twitter");
        if(mysqli_connect_error()){
             die("you are a dumbass");
        }


            if(array_key_exists("function",$_GET)&&$_GET["function"]=="logout")
            {
              //if($_GET["function"] == "logout")

              unset($SESSION);

               session_destroy();
               echo "<script>window.location = 'http://192.168.64.2/twit';</script>";

            }

          function  mentiontag($mention,$tweet){
                global $link;
                $word = " ";
                $pos = strpos($mention,"@");
                if($pos!=false){
                    $word = "";
                    for($i=($pos+1);($i<strlen($mention)&&($mention[$i]!=" "));$i++){
                        $word.=$mention[$i];
                    }
                    //$query = "SELECT * FROM `user_info` where name='".mysqli_real_escape_string($link,$word)."' LIMIT 1 ";
                  //$query = "SELECT * FROM `user_info` where user_id='".mysqli_real_escape_string($link,$_SESSION["id"])."' LIMIT 1";
                  $query = "SELECT * FROM `user_info` where name='$word' LIMIT 1 ";

                    $result = mysqli_query($link,$query);
                  //  print_r($result);
                    $row = mysqli_fetch_assoc($result);
                    if(mysqli_num_rows($result) > 0){

                          $query = "INSERT INTO `tweet_mentions` (`mention_user_id`,`tweet_id`) VALUES('".mysqli_real_escape_string($link,$row["user_id"])."','".mysqli_real_escape_string($link,$tweet)."')";
                          mysqli_query($link,$query);

                    }
                }


                $word = " ";

                $pos = strpos($mention,"#");
                if($pos!=false){
                    $word = "";
                    for($i=($pos+1);($i<strlen($mention)&&($mention[$i]!=" "));$i++){
                        $word.=$mention[$i];
                    }
                    $word = "#".$word;
                    $query = "INSERT INTO `tweet_tags` (`tweet_id`,`tag`) VALUES('".mysqli_real_escape_string($link,$tweet)."','".mysqli_real_escape_string($link,$word)."')";
                    $result = mysqli_query($link,$query);

                }

          }


            function time_since($since) {
                    $chunks = array(
                      array(60 * 60 * 24 * 365 , 'year'),
                      array(60 * 60 * 24 * 30 , 'month'),
                      array(60 * 60 * 24 * 7, 'week'),
                      array(60 * 60 * 24 , 'day'),
                      array(60 * 60 , 'hour'),
                      array(60 , 'min'),
                      array(1 , 'sec')
                  );

                    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                        $seconds = $chunks[$i][0];
                        $name = $chunks[$i][1];
                        if (($count = floor($since / $seconds)) != 0) {
                            break;
                        }
                    }

                  $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
                  return $print;
              }



        function displayTweets($type){
          global $link;
              if($type == "public"){
                    $whereClause = "";
              }else if($type == "isFollowing"){

                    $query = "SELECT * FROM `isFollowing` WHERE follower=".mysqli_real_escape_string($link, $_SESSION['id']);
                    $result = mysqli_query($link,$query);
                    $whereClause = '';
                    while($row = mysqli_fetch_assoc($result)){
                            if($whereClause == ""){
                                  $whereClause = "WHERE ";
                            }else{
                                  $whereClause .= " OR ";
                            }
                            $whereClause .= "userid= ".$row["isFollowing"];
                    }

              }else if($type == "yourTweets"){

                    $whereClause = "WHERE userid=".mysqli_real_escape_string($link ,$_SESSION["id"]);

               }else if($type == "search"){


                    echo "<p>Showing Result for '".mysqli_real_escape_string($link , $_GET["q"])."'</p>";
                    $whereClause = "WHERE tweet like '%".mysqli_real_escape_string($link , $_GET["q"])."%'";
               }else if(is_numeric($type)){
                   $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
                   $userQueryResult = mysqli_query($link ,$userQuery);
                   $user = mysqli_fetch_assoc($userQueryResult);
                   echo "<h2>".mysqli_real_escape_string($link,$user["email"])."'s Tweets</h2>";
                    $whereClause = 'WHERE userid='.mysqli_real_escape_string($link,$type);

               }

              $query = "SELECT * FROM `tweets` ".$whereClause." ORDER BY `datetime` DESC LIMIT 20";
              $result = mysqli_query($link , $query);
              if(mysqli_num_rows($result) == 0){
                  echo "There are no tweets to display ";
              }else{
                  while($row = mysqli_fetch_assoc($result)){

                        $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $row["userid"])." LIMIT 1";
                        $userQueryResult = mysqli_query($link ,$userQuery);
                        $user = mysqli_fetch_assoc($userQueryResult);

                        echo "<div class='tweet' ><p >


                        <a href='?page=publicprofiles&userid=".$user['id']."' class ='tweet_user_name'>".$user["email"]."</a> <span class='time'>".time_since(time()-strtotime($row["datetime"]))." ago</span>:</p>";
                        echo "<p>".$row["tweet"]."</p>";
                        echo "<p><a class='toggleFollow' data-userId='".$row["userid"]."' data-userId_follow='".$row["userid"]."' >";

                        $isFollowingQuery = "SELECT * FROM `isFollowing` WHERE follower=".mysqli_real_escape_string($link, $row["userid"]) ;
                        $isFollowingQueryResult = mysqli_query($link,$isFollowingQuery);
                        if(mysqli_num_rows($isFollowingQueryResult) > 0){
                            echo '<img src="follower1.png" height="22px" width="22px" />';
                        }else{
                            echo '<img src="follower3.png" height="22px" width="22px" />';
                        }

                        echo "</a>
                            <a class='comments'  data-target='#comment'  role='button'  data-userId='".$row["userid"]."' data-useremail='".$user["email"]."' data-tweetid='".$row["id"]."' data-tweet='".$row["tweet"]."' data-time='".time_since(time()-strtotime($row["datetime"]))."' ><img src='comment-black-oval-bubble-shape.png' height='22px' width='22px' /></a>
                        </p></div>";
                  }
              }

        }


        function displaySearch(){

              echo '<form class="form-inline" id="displaySearch">

              <div class="input-group mb-2 mr-sm-2">

              <input type="hidden" name="page" value="search">

                <input type="text" name="q" class="form-control bg-dark" style="color:white;" id="search" placeholder="Search">
              </div>

              <button type="submit" class="btn btn-primary mb-2">Search Tweets</button>
            </form>';

        }

        function displayTweetBox(){

            if(array_key_exists("id",$_SESSION)){
                echo '<div class="form" >
                <div  class="alert alert-success" id="tweetSucess">Your tweet was posted sucessfully.</div>
                <div id="tweetFail" class="alert alert-danger"></div>
                <div class="input-group mb-2 mr-sm-2">

                  <textarea type="text" class="form-control bg-dark" style="color:white;" id="tweetContent" placeholder="Whats Happening?" ></textarea>
                </div>

                <button id="postTweetButton" class="btn btn-primary mb-2">Post Tweets</button>
              </div>';
            }
        }
        function displayUsers(){
              GLOBAL $link;
              $query = "SELECT * FROM `users` LIMIT 10";
              $result = mysqli_query($link , $query);
              while($row = mysqli_fetch_assoc($result)){
                    echo "<p><a href='?page=publicprofiles&userid=".$row["id"]."'>".$row["email"]."</a></p>";

                   }


        }
        function showProfile(){
              GLOBAL $link;
              $query = "SELECT * FROM `user_info` where user_id='".mysqli_real_escape_string($link,$_SESSION["id"])."' LIMIT 1";
              $result = mysqli_query($link,$query);
              $row = mysqli_fetch_assoc($result);

              $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link,$_SESSION["id"] )." LIMIT 1";
              $userQueryResult = mysqli_query($link ,$userQuery);
              $user = mysqli_fetch_assoc($userQueryResult);

              $tweetQuery = "SELECT * FROM `tweets` WHERE userid=".mysqli_real_escape_string($link,$_SESSION["id"] )." ";
              $tweetQueryResult = mysqli_query($link,$tweetQuery);
              $tweetResult = mysqli_num_rows($tweetQueryResult);

              $isFollowingQuery = "SELECT * FROM `isFollowing` WHERE follower=".mysqli_real_escape_string($link, $_SESSION["id"]) ;
              $isFollowingQueryResult = mysqli_query($link,$isFollowingQuery);
              $countFollower= mysqli_num_rows($isFollowingQueryResult);

              $isFollowingQuery = "SELECT * FROM `isFollowing` WHERE isFollowing=".mysqli_real_escape_string($link, $_SESSION["id"]) ;
              $isFollowingQueryResult = mysqli_query($link,$isFollowingQuery);
              $countFollowing= mysqli_num_rows($isFollowingQueryResult);



              echo '<div class="alert alert-secondary bg-dark"  role="alert" id="">
              <div class="jumbotron bg-secondary" id="userinfo">
                    <h1 class="display-4 " style="color:white !important;">'.$row["name"].'


                    </h1>

                    <p class="lead" style="color:white !important;">'.$user["email"].'</p>
                    <p>
                    <button type="button" class="btn btn-primary">Tweets <span class="badge badge-light">'.$tweetResult.'</span></button>
                    <button type="button" class="btn btn-primary">Following <span class="badge badge-light">'.$countFollower.'</span></button>
                    <button type="button" class="btn btn-primary">Follower <span class="badge badge-light">'.$countFollowing.'</span></button>

                    </p>
                    <hr class="my-4">
                    <p style="color:white !important;">';
              if($row["description"]!=NULL){
                    echo  $row['description'].'</p>';
              }else{
                    echo "'Say something about you'";
              }

              echo '<p class="lead" style="color:white !important;">'.$row["dob"].'</p>
                  <p class="lead" id="editButton">
                    <a class="btn btn-success btn-lg"  href="#" role="button">Edit Profile</a>
                  </p>

                    </div>';
                echo '<div class="jumbotron" id="editProfile">

                      <label for="exampleInputEmail1">Email address</label>
                        <a  href="#" id="backtoprofile"  role="button"><span class="badge badge-info">Back</span></a>

                        <h1 class="display-4">'.$user["email"].'</h1>
                        <small id="emailHelp" class="form-text text-muted">We will never share your email with anyone else.</small>

                  <form>
                        <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="email" class="form-control" id="Name"  value='.$row["name"].'>
                        </div>
                        <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" ';
                        if($row["description"]==NULL){
                            echo 'placeholder="enter something about you"';
                        }else{
                            echo 'value="'.$row["description"].'"';
                        }
                    echo '"></div>
                    <div class="form-group">
                      <label for="dob">Date Of Birth</label>
                      <input type="date" class="form-control" id="dob" >
                      </div>';
                    echo '<p class="lead">
                          <a class="btn btn-primary btn-lg" href="#" id="change" role="button">Change</a>
                            </p>
                        </form>
                    </div>
                </div>';


        }


        function displayProfile(){
                global $link;
                if(array_key_exists("id",$_SESSION)){
                $query = "SELECT * FROM `user_info` where user_id='".mysqli_real_escape_string($link,$_SESSION["id"])."' LIMIT 1";
                $result = mysqli_query($link,$query);
                $row = mysqli_fetch_assoc($result);

                $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link,$_SESSION["id"] )." LIMIT 1";
                $userQueryResult = mysqli_query($link ,$userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);

                $tweetQuery = "SELECT * FROM `tweets` WHERE userid=".mysqli_real_escape_string($link,$_SESSION["id"] )." ";
                $tweetQueryResult = mysqli_query($link,$tweetQuery);
                $tweetResult = mysqli_num_rows($tweetQueryResult);

                $isFollowingQuery = "SELECT * FROM `isFollowing` WHERE follower=".mysqli_real_escape_string($link, $_SESSION["id"]) ;
                $isFollowingQueryResult = mysqli_query($link,$isFollowingQuery);
                $countFollower= mysqli_num_rows($isFollowingQueryResult);

                $isFollowingQuery = "SELECT * FROM `isFollowing` WHERE isFollowing=".mysqli_real_escape_string($link, $_SESSION["id"]) ;
                $isFollowingQueryResult = mysqli_query($link,$isFollowingQuery);
                $countFollowing= mysqli_num_rows($isFollowingQueryResult);

              /*  echo '<ul class="nav flex-column back bg-light" >
                        <li class="nav-item">
                          <a class="nav-link " href="#">'.mysqli_real_escape_string($link,$row["name"]).'</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" >'.mysqli_real_escape_string($link,$user["email"]).'</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" >Tweets <span class="badge badge-primary">'.$tweetResult.'</span></a>

                        </li>

                        <li class="nav-item">
                            <a class="nav-link" >following <span class="badge badge-primary">'.$countFollowing.'</span></a>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" >follower <span class="badge badge-primary">'.$countFollower.'</span></a>
                        </li>

                      </ul>';*/


                  echo '<div class="jumbotron bg-dark back " >
                      <h2 class="display-5">'.mysqli_real_escape_string($link,$row["name"]).'</h2>
                      <h4 class="display-7">'.$user["email"].'</h4>
                      <p class="lead">Tweets <span class="badge badge-primary">'.$tweetResult.'</span></p>
                      <p class="lead">Following <span class="badge badge-primary">'.$countFollowing.'</span></p>
                      <p class="lead">Follower <span class="badge badge-primary">'.$countFollower.'</span></p>


                    </div>';
                  }
        }
 ?>
