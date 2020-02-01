<?php require APPROOT . '/views/inc/header.php'; ?>

<?php   $post = $data['post'];
        $user = $data['post_user'];?>
<div class="container">
<div class="card card-body mb-3">
    <div>
        <?php if(@is_array(getimagesize($user->picture))) {
            echo "<img id='profile_pic' src=" . $user->picture ."> ";}
        echo $user->username; ?>
    </div>
    <div class="row mx-auto">
        <img src="<?php echo $post->image; ?>" height="700" width="700" class="img-thumbnail">
    </div>
    <div class="row mx-auto">
        <form action="<?php echo URLROOT;?>/posts/like" method="post">
            <div class="col">
                <button name="like" value="<?php echo $post->id ?>" class="btn btn-primary">Like(<?php echo $post->nb_likes ?>)</button>
            </div>
        </form>
        <form action="<?php echo URLROOT;?>/posts/comment" method="post">
            <div class="col">
                <button name="post" value="<?php echo $post->id ?>" class="btn btn-success">Comment(<?php echo $post->nb_comments ?>)</button>
            </div>
        </form>
    </div>
    <br>
    <div class="row mx-auto">
        
        <form action="<?php echo URLROOT;?>/posts/sendComment" id="comment" method="post">
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea class="form-control <?php echo (!empty($data['comment_err'])) ? 'is-invalid' : ''; ?>" rows="3" cols="100" name="comment" placeholder="comment ..."></textarea>
            <span class="invalid-feedback"><?php echo $data['comment_err']; ?></span>
        </div>
            <div class="col">
                <button name="post" value="<?php echo $post->id ?>" class="btn btn-success">Send</button>
            </div>
        </form>
    </div>
    <br>
    <?php foreach($data['comments'] as $comment) : 
        $userModel = $this->model('User');
        $comment_user =  $userModel->getUserById($comment->user_id);
        ?>
        <div class="card card-body mb-3">
        <div class="col">
            <?php if(@is_array(getimagesize($comment_user->picture))) {
            echo "<img id='profile_pic' src=" . $comment_user->picture .">  ";}
            echo $comment_user->username; ?>
        </div>
            <div class="col">
                <p style="text-align: center"><?php echo $comment->comment ?></p>
            </div>
        </div>
    <?php endforeach ; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
