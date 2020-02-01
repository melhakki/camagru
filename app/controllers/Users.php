<?php 
    class Users extends Controller{
        
        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('User');
        }

        public function register(){
            if(isLoggedIn())
                redirect('camera/index');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if(!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['comfirm_password']))
                    redirect('users/register');
                if(is_array($_POST['username']) || is_array($_POST['email']) || is_array($_POST['password']) || is_array($_POST['comfirm_password']))
                    redirect('users/register');
                $token = 'qwertyuiopoijhgfdsQWET123654789-_';
                $token = str_shuffle($token);
                $token = substr($token , 0, 10);
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'comfirm_password' => trim($_POST['comfirm_password']),
                    'username_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'comfirm_password_err' => '',
                    'token' => $token
                ];

                if(empty($data['username']))
                    $data['username_err'] = 'Please enter username';
                else{
                    if($this->userModel->findUserByUsername($data['username']))
                        $data['username_err'] = 'Username is already taken';
                    if(!preg_match('/^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/', $data['username']))
                        $data['username_err'] = 'bad Username';
                    if(strlen($data['username']) >= 254)
                        $data['username_err'] = 'Uername is too long';
                }

                if(empty($data['email']))
                    $data['email_err'] = 'Please enter email';
                else{
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['email_err'] = "Invalid email format";
                      }
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Email is already taken';
                    }
                    if(strlen($data['email']) >= 254)
                        $data['email_err'] = 'Email is too long';
                }

                $uppercase = preg_match('@[A-Z]@', $data['password']);
                $lowercase = preg_match('@[a-z]@', $data['password']);
                $number    = preg_match('@[0-9]@', $data['password']);
                if(empty($data['password'])){
                        $data['password_err'] = 'Please enter password';
                }else{
                    if(preg_match('/^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/', $data['password']))
                    {
                        if(!$uppercase || !$lowercase || !$number || strlen($data['password']) < 8)
                            $data['password_err'] = 'Please make your password stronger.';
                        if(strlen($data['password']) >= 254)
                            $data['password_err'] = 'Password is too long';
                    }else
                        $data['password_err'] = 'bad Password';
                }

                if(empty($data['comfirm_password']))
                    $data['comfirm_password_err'] = 'Please comfirm password';
                else{
                    if($data['password'] != $data['comfirm_password']){
                        $data['comfirm_password_err'] = 'Passwords do not match';
                    }
                }

                if(empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['comfirm_password_err'])){
                    $hashedpassword = hash('whirlpool', $data['password']);
                    $to = $data['email'];
                    $subject = 'Please verify your Email';
                    $message = "In order to validate your account, please click on the link below <br> <a href='http://localhost/camagru/users/confirm?token=$token'>link</a> ";
                    send_mail($to, $subject, $message);
                    if($this->userModel->register($data, $hashedpassword)){
                        flash('register_success', 'Please verify your email');
                        redirect('users/login');
                    }else{
                        die('Something went wrong');
                    }
                }
            }else{
                $data = [
                    'username' => '',
                    'email' => '',
                    'password' => '',
                    'comfirm_password' => '',
                    'username_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'comfirm_password_err' => '',
                ];
            }
            $this->view('users/register', $data);
        }

        public function confirm(){
            if(isset($_GET['token']))
            {
                $token = $_GET['token'];
                if($this->userModel->isToken($token))
                {
                    if($this->userModel->tokenDone($token)){
                        flash('register_success', 'You are registred and can login');
                        $this->view('users/confirm');
                    }else{
                        die('Something went wrong');
                    }
                }else
                    redirect('users/register');
            }
            else 
                redirect('users/register');
        }

        public function login(){
            if(isLoggedIn())
                redirect('camera/index');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if(!isset($_POST['username']) ||!isset($_POST['password']))
                    redirect('users/register');
                if(is_array($_POST['username']) || is_array($_POST['password']))
                    redirect('users/register');

                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'username' => htmlspecialchars(trim($_POST['username'])),
                    'password' => trim($_POST['password']),
                    'username_err' => '',
                    'password_err' => '',
                ];

                if($user = $this->userModel->findUserByUsername($data['username'])){
                    if($user->token !== "done")
                        $data['username_err'] = 'Please Verify your email';

                    if(empty($data['username']))
                        $data['username_err'] = 'Please enter your username';
                }else{
                    $data['username_err'] = 'Username dose not exist';
                }
                
                

                if(empty($data['password']))
                    $data['password_err'] = 'Please enter your password';


                if(empty($data['username_err']) && empty($data['password_err'])){
                    $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                    if($loggedInUser){
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['password_err'] = 'Password incorrect';
                    }
                }
            }else{ 
                $data = [
                    'username' => '',
                    'password' => '',
                    'username_err' => '',
                    'password_err' => '',
                ];
            }
            $this->view('users/login', $data);
        }

        public function modify(){
            if(!isLoggedIn())
                redirect('users/login');
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['comfirm_password'])){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'comfirm_password' => trim($_POST['comfirm_password']),
                    'notificatin' => trim($_POST['notificatin']),
                    'opassword' => trim($_POST['opassword']),
                    'username_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'comfirm_password_err' => '',
                    'notificatin_err' => '',
                    'opassword_err' => '',
                ];

                if (!empty($data['username']))
                {
                    if($this->userModel->findUserByUsername($data['username']))
                        $data['username_err'] = 'Username is already taken';
                    if(!preg_match('/^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/', $data['username']))
                        $data['username_err'] = 'bad Username';
                    if(strlen($data['username']) >= 254)
                        $data['username_err'] = 'Uername is too long';
                }
                if (!empty($data['email']))
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                        $data['email_err'] = "Invalid email format";
                    else if($this->userModel->findUserByEmail($data['email']))
                        $data['email_err'] = 'Email is already taken';

                $uppercase = preg_match('@[A-Z]@', $data['password']);
                $lowercase = preg_match('@[a-z]@', $data['password']);
                $number    = preg_match('@[0-9]@', $data['password']);
                if(!empty($data['password'])){
                    if(preg_match('/^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/', $data['password']))
                    {
                        if(!$uppercase || !$lowercase || !$number || strlen($data['password']) < 8)
                            $data['password_err'] = 'Please make your password stronger.';
                        if(strlen($data['password']) >= 254)
                            $data['password_err'] = 'Password is too long';
                    }else
                        $data['password_err'] = 'bad Password';
                    if(empty($data['comfirm_password']))
                        $data['comfirm_password_err'] = 'Please comfirm password';
                    else
                        if($data['password'] != $data['comfirm_password'])
                            $data['comfirm_password_err'] = 'Passwords do not match';
                }

                if (!empty($data['notificatin'])){
                    if($data['notificatin'] !== 'ON' && $data['notificatin'] !== 'OFF')
                        $data['notificatin_err'] = 'Invalid request';
                }

                if(empty($data['opassword']))
                        $data['opassword_err'] = 'Please enter your password';
                else{
                    $user = $this->userModel->getUserById($_SESSION['user_id']);
                    $hashedpassword = hash('whirlpool', $data['opassword']);
                    if($user->password !== $hashedpassword)
                        $data['opassword_err'] = 'Password incorrect';
                }

                if (!empty($data['username']) || !empty($data['email']) || !empty($data['password']) || !empty($data['notificatin']) || !empty($data['opassword']))
                {
                    if(empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['comfirm_password_err']) && empty($data['notificatin_err']) && empty($data['opassword_err']))
                    {
                        if (!empty($data['username']))
                            $this->userModel->modifyUsername($_SESSION['user_id'], $data['username']);
                        if (!empty($data['email']))
                            $this->userModel->modifyEmail($_SESSION['user_id'], $data['email']);
                        if(!empty($data['password']))
                        {
                            $hashedpassword = hash('whirlpool', $data['password']);
                            $this->userModel->modifyPassword($_SESSION['user_id'], $hashedpassword);
                        }
                        if (!empty($data['notificatin']))
                        {
                            if($data['notificatin'] == 'ON')
                                $this->userModel->modifyNotification($_SESSION['user_id'], 1);
                            if($data['notificatin'] == 'OFF')
                                $this->userModel->modifyNotification($_SESSION['user_id'], 0);
                        }
                        $this->createUserSession($user);
                        redirect('camera/index');
                    }
                } else { 
                    $data = [
                        'username' => '',
                        'email' => '',
                        'password' => '',
                        'comfirm_password' => '',
                        'notificatin' => '',
                        'opassword' => '',
                    ];
                }
            }else { 
                $data = [
                    'username' =>'',
                    'email' => '',
                    'password' => '',
                    'comfirm_password' => '',
                    'notificatin' => '',
                    'opassword' => '',
                ];
            }
            $this->view('users/modify', $data);
        }

        public function forgot(){
            if(isLoggedIn())
                redirect('camera/index');
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = [
                    'email' => trim($_POST['email']),
                    'email_err' => '',
                ];

                if(empty($data['email']))
                    $data['email_err'] = 'Please enter email';
                else{
                    if(!$this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'email not exist';
                    }
                }
                $user = $this->userModel->findUserByEmail($data['email']);
                if(empty($data['email_err'])){
                    $npassword = 'qwertyuiopoijhgfdsQWET123654789+-/)(&^%$#';
                    $npassword = str_shuffle($npassword);
                    $npassword = substr($npassword , 0, 12);
                    $hashedpassword = hash('whirlpool', $npassword);
                    $to = $data['email'];
                    $subject = "forgot Password";
                    $message = "your new password is = '$npassword'";
                    send_mail($to, $subject, $message);
                    if($this->userModel->modifyPassword($user->id, $hashedpassword))
                        redirect('users/login');
                    else
                        die ("wtf");
                }
            
            }else{ 
                $data = [
                    'email' => '',
                    'email_err' => '',
                ];
            }
            $this->view('users/forgot', $data);
        }

        public function createUserSession($user){
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_username'] = $user->username;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['profile_pic'] = $user->picture;

            redirect('camera/index');
        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_username']);
            unset($_SESSION['user_email']);
            session_destroy();
            redirect('users/login');
        }

        public function user_exist($user_id){
            if($this->userModel->getUserById($user_id))
                return true;
            else{
                $this->logout();
                return false;
            }
        }
    }