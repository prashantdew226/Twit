<div class="wrapper">

<div  class="container-fluid container_border" >
  <div class="row">
    <div class="col-sm-3 " >
      <div class="com">
        <?php displayProfile(); ?>
      </div>
    </div>
      <div class="col-sm-6">
          <h2>Recents tweets</h2>
          <?php displayTweets("public");?>

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
