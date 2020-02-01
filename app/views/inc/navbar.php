<nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-3">
    <div class="container">
  <a class="navbar-brand" href="<?php echo URLROOT; ?>/posts/gallery"><?php echo SITENAME; ?></a>
  <button class="navbar-toggler" id="btn" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="true" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div id="navbarsExampleDefault" class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
        <a class="nav-link" href="<?php echo URLROOT; ?>/camera/index"><i class="material-icons">photo_camera</i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto" style="float:right">
    <?php if (isset($_SESSION['user_id'])){ ?>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/modify">
            <?php  if(@is_array(getimagesize($_SESSION['profile_pic']))) {
                echo "<img id='profile_pic' src=" . $_SESSION['profile_pic'] .">";}?>
                <i class="material-icons">settings_applications</i>
            </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
        </li>
    <?php } else {  ?>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/register">Register</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
        </li>
    <?php } ?>
    </ul>

  </div>
</div>
</nav>