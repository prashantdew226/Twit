<footer class="footer navbar-dark bg-dark">
    <div class="container">
        <p style="color:white;">&copy;Twitter 2020</p>
    <div>
</footer>

<script  text="text/javascript" src="un.js"></script>

<!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <!--script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalTitle">Log In</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" id="loginAlert"></div>
        <form>
            <input type="hidden" id="loginActive" name="loginActive" value="1">
            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Password">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <a id="toggleLogin">Sign Up</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button"  id="loginSignupButton" class="btn btn-primary">Log In</button>
      </div>
    </div>
  </div>
</div>





<div class="modal fade" id="comment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  " role="document">
    <div class="modal-content bg_color_other ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
        <form>
            <div class="form-group" id="tweet_val"></div>
            <hr>
            <div class="form-group" id="tweetComments">

            </div>
            <hr>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Comment:</label>
            <textarea  class="form-control bg-dark" style='color:white;' id="commentContent" placeholder="write your comments"></textarea>
          </div>
        </form>
      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="postCommentButton" class="btn btn-primary">Comment</button>
      </div>
    </div>
  </div>
</div>
<script>
      $("#toggleLogin").click(function(){
            if($("#loginActive").val()=="1"){
                  $("#loginActive").val("0");
                  $("#loginModalTitle").html("Sign Up");
                  $("#loginSignupButton").html("Sign Up");
                  $("#toggleLogin").html("Log In");
            }else{
                  $("#loginActive").val("1");
                  $("#loginModalTitle").html("Log In");
                  $("#loginSignupButton").html("Log In");
                  $("#toggleLogin").html("Sign Up")
            }
      });
      $("#loginSignupButton").click(function(){
           $.ajax({
                  type: "POST",
                  url: "action.php?action=loginSignup",
                  data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&loginActive=" + $("#loginActive").val(),
                  success : function(result){

                          if(result=="1"){

                                window.location = "http://192.168.64.2/twit";
                          }else{
                                $("#loginAlert").html(result).show();
                          }
                  }

            })

      });


      $(".comments").click(function(){
          var id = $(this).attr("data-userId");
          var email = $(this).attr("data-useremail");
          var tweet = $(this).attr("data-tweet");
          var tweetid = $(this).attr("data-tweetid");
          var time = $(this).attr("data-time");
          $('#comment').on('show.bs.modal', function () {
                var modal = $(this)
                modal.find('.modal-title').text('Comments on ' + email);
                $("#tweet_val").html("<strong>"+email+"</strong> says: "+tweet);

          })

          $("#postCommentButton").attr("data-userId",id);
          $("#postCommentButton").attr("data-useremail",email);
          $("#postCommentButton").attr("data-tweet",tweet);
          $("#postCommentButton").attr("data-tweetid",tweetid);

          $('#comment').modal('toggle');



            $.ajax({
                type : "POST",
                url : "action.php?action=comment1",
                data : "tweetid=" + tweetid ,
                success : function(result){
                    $("#tweetComments").html(result);
                }
            });
          //  $('#comment').modal('toggle');

      });

      $("#postCommentButton").click(function(){
        var id = $(this).attr("data-userId");
        var email = $(this).attr("data-useremail");
        var tweet = $(this).attr("data-tweet");
        var tweetid = $(this).attr("data-tweetid");
        var time = $(this).attr("data-time");

        var commenter_email;
         $.ajax({
                  type : "POST" ,
                  url : "action.php?action=getUserEmail",
                  data :"",
                  success : function(conn){
                    //var data = '{"result":"1","commenter_email":"wer@wer.wer"}';
                    var obj =  JSON.parse(conn);
                    if(obj.result=='1'){
                      commenter_email = obj.commenter_email;
                    }


                  }
            });



        $.ajax({
                  type : "POST" ,
                  url : "action.php?action=postComment",
                  data : "commentContent=" + $("#commentContent").val()+"&tweetid="+tweetid+"&userid="+id,
                  success : function(result){
                      if(result=="1"){
                            if($("#tweetComments").html()=="no comments to show"){
                              $("#tweetComments").html('<div class="comment_user"><p><a href="?page=publicprofiles&userid='+id+'">'+email+'</a></p><hr>'+'<p>'+$("#commentContent").val()+'</p>'+'</div>');

                            }else{
                                $("#tweetComments").html($("#tweetComments").html()+'<div class="comment_user"><p><a href="?page=publicprofiles&userid='+id+'">'+commenter_email+'</a></p><hr>'+'<p>'+$("#commentContent").val()+'</p>'+'</div>');
                            }
                      }else{
                          alert(result);
                      }
                  }
            });


       });

      $(".toggleFollow").click(function(){
                var id = $(this).attr("data-userId");
                $.ajax({
                       type: "POST",
                       url: "action.php?action=toggleFollow",
                       data: "userId=" + id,
                       success : function(result){
                              if(result == "1"){
                                  $("a[data-userId_follow='"+id+"']").html('<img src="follower1.png" height="22px" width="22px" />');
                              }else{
                                  $("a[data-userId_follow='"+id+"']").html('<img src="follower3.png" height="22px" width="22px" />');
                              }
                       }

                 })

           });
           $("#postTweetButton").click(function(){
                 $.ajax({
                        type: "POST",
                        url: "action.php?action=postTweet",
                        data: "tweetContent=" + $("#tweetContent").val(),
                        success : function(result){
                               if(result == "1"){
                                 var afterThreeSeconds = function() {
                                  $("#tweetSucess").show();
                                  $("#tweetFail").hide();
                                }
                                window.setTimeout(afterThreeSeconds, 20000);
                                window.location = "http://192.168.64.2/twit";

                               }else if(result != ""){
                                  $("#tweetFail").html(result).show();
                                  $("#tweetSucess").hide();
                               }
                        }

                  })
               });


            $("#change").click(function(){
              $.ajax({
                     type: "POST",
                     url: "action.php?action=ChangeProfile",
                     data: "Name=" + $("#Name").val() +"&description=" + $("#description").val()+ "&dob=" + $("#dob").val(),
                     success : function(result){
                            if(result=="1"){
                              window.location = "http://192.168.64.2/twit/?page=userinfo";
                            }else{
                              alert("their is some error in updation");
                            }
                     }

               })
            });

            $("#editButton").click(function(){
                $("#userinfo").css("display","none");
                $("#editProfile").css("display","block");
            });


            $("#backtoprofile").click(function(){
                $("#userinfo").css("display","block");
                $("#editProfile").css("display","none");
            });

</script>
</body>
</html>
