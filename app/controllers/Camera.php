<?php
    class Camera extends Controller {

        public function __construct()
        {
            $this->postModel = $this->Model("Post");
            $this->userModel = $this->Model("User");
        }

        public function index(){
            if(!isLoggedIn())
                redirect('users/login');
            $my_posts = $this->postModel->getMyPosts($_SESSION['user_id']);
            $data = [
                'my_posts' => $my_posts
            ];
            if (isset($_SESSION['user_id'])) {
                $this->view('pages/camera', $data);
            }
            else
                redirect('users/login');
        }

        public function take_pic(){
            if(!isLoggedIn())
                redirect('users/login');
            $_SESSION['img'] = $_POST['img'];
            $_SESSION['stk'] = $_POST['stk'];
            $_SESSION['from'] = $_POST['from'];
        }

        public function save(){
            if(!isLoggedIn())
                redirect('users/login');
            if(isset($_POST['save']) && isset($_SESSION['img'])){
                if($_SESSION['from'] == 'video')
                    redirect('camera/from_video');
                else if($_SESSION['from'] == 'upload')
                    redirect('camera/from_upload');
            } else
            redirect('camera/index');
        }

        public function from_upload(){
            if(!isLoggedIn())
                redirect('users/login');
            if(empty($_SESSION['img']) || empty($_SESSION['stk']) || is_array($_SESSION['img']))
                redirect('pages/error');
            $data = $_SESSION['img'];
            $stiker = $_SESSION['stk'];

            if(strpos($data, ';'))
            {
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                list(, $type) = explode('/', $type);
                try{
                    
                    if($data = base64_decode($data, true)) {
                        $id = $this->postModel->getLastId()->id + 1;
                            if ($type != 'png')
                                $type = 'jpg';
                            file_put_contents('../public/img/picture' . $id . '.' . $type, $data);
                            $dest = '../public/img/picture' . $id . '.' . $type;
                            if ($type == 'png')
                                $dest = @imagecreatefrompng($dest);
                            else
                                $dest = @imagecreatefromjpeg($dest);
                                
                            if (!$dest)
                                throw redirect('pages/error');
                            if($stiker == 1 || $stiker == 2 || $stiker == 3 || $stiker == 4){
                                $src  = '../public/img/stickers/'. $stiker .'.png';
                                
                                // die($src);
                                list($srcWidth, $srcHeight) = getimagesize($src);
                                $src = imagecreatefrompng($src);
                                
                                
                                    imagecopyresized($dest, $src, 300, 150, 0, 0, 400, 400, $srcWidth, $srcHeight);
                                    imagejpeg($dest , '../public/img/picture' . $id . '.' . $type, 100);
                            
                            }
                            $this->postModel->addPost($_SESSION['user_id'], '../public/img/picture'. $id .'.' . $type);
                    }
                    
                    unset($_SESSION['img']);
                    unset($_SESSION['stk']);
                    redirect('camera/index');
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }else 
                redirect('pages/error');
        }

        public function from_video(){
            if(!isLoggedIn())
                redirect('users/login');

            $imageData = $_SESSION['img'];
            $stiker = $_SESSION['stk'];
            if($stiker != 1 && $stiker != 2 && $stiker != 3 && $stiker != 4)
                redirect('camera/index');
            else
            {
                $filteredImage=substr($imageData, strpos($imageData, ",")+1);
                $unencodedImage=base64_decode($filteredImage);
                $id = $this->postModel->getLastId()->id + 1;
                file_put_contents('../public/img/picture' . $id . '.png', $unencodedImage);
                $src  = '../public/img/stickers/'. $stiker .'.png';
                $dest = '../public/img/picture' . $id . '.png';

                list($srcWidth, $srcHeight) = getimagesize($src);
                $src = imagecreatefrompng($src);
                $dest = imagecreatefrompng($dest);

                imagecopyresized($dest, $src, 100, 50, 0, 0, 120, 120, $srcWidth, $srcHeight);
                imagejpeg($dest , '../public/img/picture' . $id . '.png', 100);

                $this->postModel->addPost($_SESSION['user_id'], '../public/img/picture'. $id .'.png');
                unset($_SESSION['img']);
                unset($_SESSION['stk']);
            }
            redirect('camera/index');
        }

        public function delete_pic(){
            if(!isLoggedIn())
                redirect('users/login');
            if (isset($_POST['delete'])){
                $post_id = $_POST['delete'];
                unset($_POST['delete']);
                if($this->postModel->isMyPost($post_id, $_SESSION['user_id']))
                {
                    if($this->postModel->getPostById($post_id)){
                        $link = $this->postModel->getPostById($post_id)->image;
                        if(file_exists($link))
                            unlink($link);
                        $this->postModel->deletePost($post_id);
                    }
                }
            }
            $my_posts = $this->postModel->getMyPosts($_SESSION['user_id']);
                $data = [
                    'my_posts' => $my_posts
                ];
            $this->view('pages/camera', $data);
        }

        public function profile_pic(){
            if(!isLoggedIn())
                redirect('users/login');
            if(!isLoggedIn())
                redirect('users/login');
            if (isset($_POST['profile'])){
                $post_id = $_POST['profile'];
                unset($_POST['profile']);
                
                if($this->postModel->getPostById($post_id)){
                    $link = $this->postModel->getPostById($post_id)->image;
                    $this->userModel->modifyPicture($_SESSION['user_id'], $link);
                    $_SESSION['profile_pic'] = $link;
                }
            }
            $my_posts = $this->postModel->getMyPosts($_SESSION['user_id']);
                $data = [
                    'my_posts' => $my_posts
                ];
            $this->view('pages/camera', $data);
        }
    }