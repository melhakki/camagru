<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container col">
  <div class="row  mx-auto">
      <div class="camera  mx-auto">
          <video id="video" class="img-thumbnail">Video stream not available.</video>
          <div>
          <center><button class="btn btn-outline-secondary" id="startbutton">Take picture</button></center>
          </div>
      </div>
      <div class="mx-auto">
      <br><br>
          Select a picture: <input type = "file" accept="image/*" onchange="uploadimg();" id="upload">
      </div>
      <div class="output  mx-auto">
        <canvas id="canvas" class="img-thumbnail">
            <img id="photo" name="photo" alt="The screen capture will appear in this box.">
        </canvas>
        <form method="post" action="<?php echo URLROOT;?>/camera/save">
          <div>
            <center><input class="btn btn-outline-secondary" type="submit" name="save" value="Save picture"></center>
          </div>
        </form>
      </div>
  </div>
  </div class= "row mx-auto">
  <div class="row">
            <div class="col-md-3 text-center">
                <img  style="width: 100px; height: 100px;" src="<?php echo URLROOT;?>/img/stickers/1.png" alt="">
                <br>
                <input type="radio" value="1" name="stickers" >
            </div>
            <div class="col-md-3 text-center">
                <img   style="width: 100px; height: 100px;" src="<?php echo URLROOT;?>/img/stickers/2.png" alt="">
                <br>
                <input type="radio" value="2" name="stickers">
            </div>
            <div class="col-md-3 text-center">
                <img style="width: 100px; height: 100px;"  src="<?php echo URLROOT;?>/img/stickers/3.png" alt="">
                <br>
                <input type="radio" value="3" name="stickers">
            </div>
            <div class="col-md-3 text-center">
                <img style="width: 100px; height: 100px;"  src="<?php echo URLROOT;?>/img/stickers/4.png" alt="">
                <br>
                <input type="radio" value="4" name="stickers">
            </div>
        </div>
  </div>
</div>

<br><hr><br>
  <div class="container">
    <div class="row mx-auto">
      <?php  if(!empty($data['my_posts'])) { foreach($data['my_posts'] as $post) : ?>
          <div class="mx-auto">
            <img src="<?php echo $post->image; ?>" style="width: 320px;" class="img-thumbnail"><br>
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
<script src="<?php echo URLROOT; ?>/js/camera.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
