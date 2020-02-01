<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
        <?php flash('register_success'); ?>
        <h2>Login</h2>
        <form action="<?php echo URLROOT;?>/users/login" method = "post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name='username' class="form-control form-control-lg <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name='password' class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                <a href="<?php echo URLROOT; ?>/users/forgot" class="text-warning">forgot password</a>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Login" class="btn btn-success btn-block">     
                </div>
                <div class="col">
                    <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block">No account? Register</a>
                </div>
            </div>
        </form>
        </div>
    </div>
</div> 


<?php require APPROOT . '/views/inc/footer.php'; ?>