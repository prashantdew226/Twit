<?php
      //it is used for signing and tweets

      include("functions.php");
      if($_GET["action"]=="loginSignup"){

            $error = "";
            if($_POST["email"] == NULL){

              $error = "An email address is required.";

            }else if($_POST["password"] == NULL ){
              $error = "An password field is required.";

            }else if (filter_var($_POST["email"] , FILTER_VALIDATE_EMAIL)==false) {

              $error = "please enter a valid email address.";

            }
            if($error != ""){
              echo $error;
              exit();
            }
             if($_POST["loginActive"]=="0"){

                    $query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($link, $_POST["email"])."' LIMIT 1";

                    $result = mysqli_query($link,$query);
                    if(mysqli_num_rows($result)>0){
                          $error = "that email address is already taken";
                    }else{
                          $query = "INSERT INTO `users` (`email`,`password`) VALUES('".mysqli_real_escape_string($link, $_POST["email"])."','".mysqli_real_escape_string($link, $_POST["email"])."')";
                          if(mysqli_query($link,$query)){
                            $_SESSION["id"]=mysqli_insert_id($link);
                              $query = "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST["password"])."' WHERE id='".mysqli_insert_id($link)."' LIMIT 1";
                              mysqli_query($link,$query);


                              $infoquery = "INSERT INTO `user_info` (`user_id`) VALUES('".mysqli_real_escape_string($link, $_SESSION["id"])."')";
                              mysqli_query($link,$infoquery);

                              echo 1;


                          }else{
                            $error = "Couldn't create user - please try again later . ";
                          }
                    }
            }else{

                    $query = "SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($link, $_POST["email"])."' LIMIT 1";

                    $result = mysqli_query($link,$query);
                    //only one row associated with it
                    $row = mysqli_fetch_assoc($result);
                          if($row["password"] == md5(md5($row["id"]).$_POST["password"])){
                                echo 1;

                                $_SESSION["id"] = $row["id"];
                          }else{
                                $error = "couldn't find that username/password please try again." ;
                          }
            }
            if($error != ""){
                echo $error;
                exit();
            }
      }


      if($_GET["action"]=="toggleFollow"){

            $query = "SELECT * FROM `isFollowing` WHERE follower='".mysqli_real_escape_string($link, $_SESSION['id'])."' AND isFollowing='".mysqli_real_escape_string($link, $_POST["userId"])."' LIMIT 1";
            $result = mysqli_query($link,$query);
            if(mysqli_num_rows($result)>0){

                  $row = mysqli_fetch_assoc($result);
                  mysqli_query($link, "DELETE FROM isFollowing WHERE id='".mysqli_real_escape_string($link,$row["id"])."' LIMIT 1");
                  echo "1";
            }else{

                  mysqli_query($link,"INSERT INTO `isFollowing` (`follower`,`isFollowing`) VALUES('".mysqli_real_escape_string($link,$_SESSION["id"])."','".mysqli_real_escape_string($link,$_POST["userId"])."')");

                  echo "2";
            }
      }

      if($_GET["action"]=="postTweet"){
                if(!$_POST["tweetContent"]){
                      echo "Your tweet is empty!";
                }else if(strlen($_POST["tweetContent"])>140){
                      echo "Your tweet is too long!";
                }else{
                  $curr =date('Y-m-d H:i:s');
                  $query ="INSERT INTO `tweets`( `tweet`, `userid`, `datetime`) VALUES ('".mysqli_real_escape_string($link,$_POST["tweetContent"])."','".mysqli_real_escape_string($link,$_SESSION["id"])."','".$curr."')";
                  mysqli_query($link,$query);
                  mentiontag($_POST["tweetContent"],mysqli_insert_id($link));
                    echo "1";
                }

      }

      if($_GET["action"]=="ChangeProfile"){

            $query = "SELECT * FROM `user_info` where user_id='".mysqli_real_escape_string($link,$_SESSION["id"])."' LIMIT 1";
            $result = mysqli_query($link,$query);
            $row = mysqli_fetch_assoc($result);
            if($_POST["dob"]==""){
              $InfoQuery = "UPDATE `user_info` SET name='".mysqli_real_escape_string($link,$_POST["Name"])."',description='".mysqli_real_escape_string($link,$_POST["description"])."', dob='".mysqli_real_escape_string($link,$row["dob"])."'"."WHERE user_id ='".mysqli_real_escape_string($link,$_SESSION["id"])."' " ;

            }else{
              $InfoQuery = "UPDATE `user_info` SET name='".mysqli_real_escape_string($link,$_POST["Name"])."',description='".mysqli_real_escape_string($link,$_POST["description"])."', dob='".mysqli_real_escape_string($link,$_POST["dob"])."'"."WHERE user_id ='".mysqli_real_escape_string($link,$_SESSION["id"])."' " ;
            }
            mysqli_query($link,$InfoQuery);
            echo "1";
      }

      if($_GET["action"]=="comment1"){
            //echo $_POST["tweetid"];

            $commentQuery = "SELECT * FROM `comments` where tweet_id =".mysqli_real_escape_string($link,$_POST["tweetid"]);
            $commentresult = mysqli_query($link,$commentQuery);
            $comment = mysqli_num_rows($commentresult);

            if(mysqli_num_rows($commentresult)>0){
              $comment = mysqli_num_rows($commentresult);

              while($commentrow = mysqli_fetch_assoc($commentresult)){
                  $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link, $commentrow["commenter_user_id"])." LIMIT 1";
                  $userQueryResult = mysqli_query($link ,$userQuery);
                  $user = mysqli_fetch_assoc($userQueryResult);

                  echo '<div class="comment_user"><p><a href="?page=publicprofiles&userid='.$user["id"].'">'.$user["email"].'</a></p><hr>';
                  echo '<p>'.$commentrow["comment"].'</p>';
                  echo '</div>';
              }


            }else{
                  echo "no comments to show" ;
            }

      }

      if($_GET["action"]=="postComment"){

        if(array_key_exists("id",$_SESSION)){
              if(!$_POST["commentContent"]){
                    echo "Your comment is empty!";
              }else if(strlen($_POST["commentContent"])>70){
                    echo "Your Comment is too long!";
              }else{
              //    echo $_POST["commentContent"]." ".$_POST["tweetid"]." ".$_POST["userid"]." ";
                  $query ="INSERT INTO `comments`( `tweet_id`, `commenter_user_id`, `comment`,`tweet_user_id`) VALUES ('".mysqli_real_escape_string($link,$_POST["tweetid"])."','".mysqli_real_escape_string($link,$_SESSION["id"])."','".mysqli_real_escape_string($link,$_POST["commentContent"])."','".mysqli_real_escape_string($link,$_POST["userid"])."')";
                  mysqli_query($link,$query);
                  echo "1";
              }
          }else{
              echo "you are no logged in";
          }
      }
      if($_GET["action"]=="getUserEmail"){

        if(array_key_exists("id",$_SESSION)){
                  $userQuery = "SELECT * FROM `users` WHERE id = ".mysqli_real_escape_string($link,$_SESSION["id"] )." LIMIT 1";
                  $userQueryResult = mysqli_query($link ,$userQuery);
                  $user = mysqli_fetch_assoc($userQueryResult);

                  $myObj = (object)array("result"=>"1","commenter_email"=>$user["email"]);
                  $myJSON = json_encode($myObj);
                  echo $myJSON;
          }else{
              echo "you are no logged in";
          }
      }

 ?>
