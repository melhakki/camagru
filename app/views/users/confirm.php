<?php require APPROOT . '/views/inc/header.php'; ?>



<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <?php flash('register_success'); ?>
            <b> Your account has been activated</b>
            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-success btn-block">Log in</a>
        </div>
   </div>
</div> 


<?php require APPROOT . '/views/inc/footer.php'; ?>