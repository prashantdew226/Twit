<div class="wrapper">
<div  class="container-fluid container_border">
  <div class="row">
    <div class="col-sm-3" >
      <div class="com">
        <?php displayProfile(); ?>
        </div>
    </div>
      <div class="col-sm-6">
          <?php if(array_key_exists("userid",$_GET)){ ?>


                <?php displayTweets($_GET["userid"]); ?>

          <?php }else { ?>
                <h2>Active Users</h2>
                <?php displayUsers(); ?>


          <?php } ?>

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
