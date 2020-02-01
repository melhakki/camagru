<?php
    class Posts extends Controller {
        private $postModel;
        private $userModel;

        public function __construct(){
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
        }

        public function gallery() {
            $posts = $this->postModel->getPosts(0, 5);
            $data = [
                'posts' => $posts
            ];
            $this->view('posts/gallery', $data);
        }

        public function pagination(){
            $from = $_POST['start'];
            $posts = $this->postModel->getPosts($from, 2);

            foreach($posts as $post){
                echo '<div class="container">
                    <div class="card card-body mb-3">
                        <div class="row mx-auto">
                            <img src="' . $post->image . '" height="500" width="500" class="img-thumbnail">
                        </div>
                        <div class="row mx-auto">
                            <form action="'.URLROOT.'/posts/like" method="post">
                                <div class="col">
                                    <button name="like" value="'. $post->id .'" class="btn btn-primary">Like('. $post->nb_likes .')</button>
                                </div>
                            </form>
                            <form action="'.URLROOT.'/posts/comment" method="post">
                                <div class="col">
                                    <button name="post" value="'. $post->id .'" class="btn btn-success">Comment('.$post->nb_comments .')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
            }

        }

        public function like(){
            if(!isLoggedIn())
                redirect('users/login');
            if(isset($_POST['like'])){
                $post_id = $_POST['like'];
                if($this->postModel->getPostById($post_id)){
                    $user_id = $_SESSION['user_id'];
                    if(!$this->postModel->alredyLike($post_id, $user_id))
                    {
                        $this->postModel->addLike($post_id, $user_id);
                        $nb_likes = $this->postModel->getPostById($post_id)->nb_likes + 1;
                        $this->postModel->updateLikeInPost($post_id, $nb_likes);
                    }else{
                        $this->postModel->disLike($post_id, $user_id);
                        $nb_likes = $this->postModel->getPostById($post_id)->nb_likes - 1;
                        $this->postModel->updateLikeInPost($post_id, $nb_likes);
                    }
                } else {
                    redirect('pages/error');
                }
            
                $post =  $this->postModel->getPostById($post_id);
                $comments = $this->postModel->getComments($post_id);
                $post_user =  $this->userModel->getUserById($post->user_id);
                $data = [
                    'post_user' => $post_user,
                    'post' => $post,
                    'comments' => $comments
                ];
                $this->view('posts/comment', $data);
            }else
                redirect('pages/error');
        }

        public function comment(){
            if(!isLoggedIn())
                redirect('users/login');

            if(isset($_POST['post']))
            {
                
                $post_id = $_POST['post'];
                if($this->postModel->getPostById($post_id)){
                
                    $post =  $this->postModel->getPostById($post_id);
                    $comments = $this->postModel->getComments($post_id);
                    $post_user =  $this->userModel->getUserById($post->user_id);
                    $data = [
                        'post_user' => $post_user,
                        'post' => $post,
                        'comments' => $comments
                    ];
                    $this->view('posts/comment', $data);
                } else
                    redirect('pages/error');
            }else
                redirect('pages/error');
        }

        public function sendComment(){
            if(!isLoggedIn())
                redirect('users/login');
            if(isset($_POST['comment'])){
                $post_id = $_POST['post'];
                $user_id = $_SESSION['user_id'];
                $user_email = $_SESSION["user_username"];

                if(!empty($_POST['comment'])){
                    $comment = $_POST['comment'];
                    unset($_POST['comment']);
                    $comment = htmlspecialchars($comment);
                    $this->postModel->addComment($user_id, $post_id, $comment);
                    $user = $this->userModel->getUserById($user_id);
                    if($user->notification)
                    {
                        $to = $user->email;
                        $subject = 'You have a comment';
                        $message = "$user_email commented your post";
                        send_mail($to, $subject, $message);
                    }

                    $nb_comments = $this->postModel->getPostById($post_id)->nb_comments + 1;
                    $this->postModel->updateCommentInPost($post_id, $nb_comments);
                }
                $post =  $this->postModel->getPostById($post_id);
                $comments = $this->postModel->getComments($post_id);
                $post_user =  $this->userModel->getUserById($post->user_id);
                $data = [
                    'post_user' => $post_user,
                    'post' => $post,
                    'comments' => $comments
                ];
                $this->view('posts/comment', $data);
            }else
                redirect('pages/error');
        }
    }