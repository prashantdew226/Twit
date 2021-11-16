<div class="wrapper">
<div  class="container-fluid container_border">
  <div class="row">
    <div class="col-sm-3" >
      <div class="com">
        <?php displayProfile(); ?>
        </div>
    </div>
      <div class="col-sm-6">
          <h2>Search Result</h2>
          <?php
          if(array_key_exists("q",$_GET)&&($_GET["q"]!="")){
            displayTweets("search");
          }else{  ?>
            <script>
              alert("you have empty search");
            </script>
        <?php }?>


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
