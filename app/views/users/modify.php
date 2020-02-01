<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5 text-center">
        <h2>Modify Your Account</h2>
        <form action="<?php echo URLROOT;?>/users/modify" method = "post">
            <div class="form-group">
                <label for="username">NEW Username:</label>
                <input type="text" name='username' class="form-control form-control-lg <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="email">New Email:</label>
                <input type="text" name='email' class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name='password' class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="comfirm_password">Confirm New Password:</label>
                <input type="password" name='comfirm_password' class="form-control form-control-lg <?php echo (!empty($data['comfirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['comfirm_password']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['comfirm_password_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="notificatin">Notificatin(ON/OFF)</label>
                <input type="text" name='notificatin' class="form-control form-control-lg <?php echo (!empty($data['notificatin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['notificatin']; ?>">
                <span class="invalid-feedback"><?php echo $data['notificatin_err']; ?></span>
            </div>
            <br>
            <div class="form-group">
                <label for="opassword">Password: *</label>
                <input type="password" name='opassword' class="form-control form-control-lg <?php echo (!empty($data['opassword_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['opassword']; ?>" autocomplete="on">
                <span class="invalid-feedback"><?php echo $data['opassword_err']; ?></span>
            </div>
            <div>
                <input type="submit" value="Modify" class="btn btn-success btn-block">     
            </div>
        </form>
        </div>
    </div>
</div> 


<?php require APPROOT . '/views/inc/footer.php'; ?>
