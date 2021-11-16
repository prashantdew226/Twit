<div class="wrapper">
<div  class="container-fluid container_border">
  <div class="row">
      <div class="col-sm-8">
          <h2>Your Profile</h2>
          <?php
          if(array_key_exists("id",$_SESSION)){
                showProfile();
          }else{
            echo "you are not logged in";
          }
          ?>

      </div>
      <div class="col-sm-4">
        <div class="rightdisplay">
            <?php displaySearch(); ?>
            <hr>
            <?php displayTweetBox(); ?>
          </div>
      </div>
  </div>

</div>

</div>
