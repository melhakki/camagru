<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5 text-center">
        <h2>Create An Account</h2>
        <form action="<?php echo URLROOT;?>/users/register" method = "post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name='username' class="form-control form-control-lg <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name='email' class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name='password' class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"  value="<?php echo $data['password']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="comfirm_password">Comfirm Password:</label>
                <input type="password" name='comfirm_password' class="form-control form-control-lg <?php echo (!empty($data['comfirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['comfirm_password']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['comfirm_password_err']; ?></span>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Register" class="btn btn-success btn-block">     
                </div>
                <div class="col">
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Have an account? Login</a>
                </div>
            </div>
        </form>
        </div>
    </div>
</div> 


<?php require APPROOT . '/views/inc/footer.php'; ?>
