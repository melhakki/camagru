<div class="container">
    <div class="row mx-auto">
      <?php  if(isset($data['my_posts'])) { foreach($data['my_posts'] as $post) : ?>
          <div class="mx-auto">
            <img src="<?php echo $post->image; ?>" class="img-thumbnail"><br>
            <div class="row">
              <div class="col">
                <form method="post" action="<?php echo URLROOT;?>/camera/delete_pic">
                  <button name="delete" value="<?php echo $post->id ?>" class="btn btn-danger">Delete</button>
                </form>
              </div>
              <div class="col">
                <form method="post" action="<?php echo URLROOT;?>/camera/profile_pic">
                  <button name="profile" value="<?php echo $post->id ?>" class="btn btn-info">Profile picture</button>
                </form>
              </div>
            </div>
            <br>
          </div>
      <?php endforeach ; }?> 
    </div>
</div>