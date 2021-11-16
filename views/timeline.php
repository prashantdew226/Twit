<div class="wrapper">
<div  class="container-fluid container_border">
  <div class="row">
    <div class="col-sm-3" >
      <div class="com">
        <?php displayProfile(); ?>
        </div>
    </div>
      <div class="col-sm-6">
          <h2>Recents tweets</h2>
          <?php
          if(array_key_exists("id",$_SESSION)){
              displayTweets("isFollowing");

          }else{
            echo "you are not logged in";
          }
          ?>

      </div>
      <div class="col-sm-3">
        <div class="rightdisplay">
            <?php displaySearch(); ?>
            <hr>
            <?php displayTweetBox(); ?>
          </div>

      </div>
  </div>

</div>
</div>
