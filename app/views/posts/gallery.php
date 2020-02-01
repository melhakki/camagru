<?php require APPROOT . '/views/inc/header.php'; ?>
<div>
    <h1 class="text-center">Posts</h1>
</div>
<?php foreach($data['posts'] as $post) : ?>
<div class="container">
    <div class="card card-body mb-3">
        <div class="row mx-auto">
            <img src="<?php echo $post->image; ?>" height="500" width="500" class="img-thumbnail">
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
    </div>
</div>
<?php endforeach ; ?>
<div id="result"></div>

<script src="<?php echo URLROOT; ?>/js/pagination.js"></script>
 
<?php require APPROOT . '/views/inc/footer.php'; ?>

