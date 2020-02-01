<?php 
    class User {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function register($data, $password){
            $this->db->query('INSERT INTO users (username, email, password, picture, token, notification) VALUES (:username, :email, :password, :picture, :token, 1)');
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $password);
            $this->db->bind(':picture', 'null');
            $this->db->bind(':token', $data['token']);

            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function login($username, $password){
            $this->db->query('SELECT * FROM users WHERE username = :username');

            $this->db->bind(':username', $username);
            $password = hash('whirlpool', $password);
            $row = $this->db->single();

            if($password == $row->password)
                return $row;
            else
                return false;
        }

        public function findUserByEmail($email){
            $this->db->query('SELECT * FROM users WHERE email = :email');

            $this->db->bind(':email', $email);

            $row = $this->db->single();

            if($this->db->rowCount() > 0)
                return $row;
            else
                return false; 
        }

        public function findUserByUsername($username){
            $this->db->query('SELECT * FROM users WHERE username = :username');

            $this->db->bind(':username', $username);

            $row = $this->db->single();

            if($this->db->rowCount() > 0)
                return $row;
            else
                return false;
        }

        public function getUserById($id){
            $this->db->query('SELECT * FROM users WHERE id = :id');

            $this->db->bind(':id', $id);

            $row = $this->db->single();

            if($this->db->rowCount() > 0)
                return $row;
            else
                return false;
        }
        public function modifyUsername($id, $username){
            $this->db->query('UPDATE users SET username = :username WHERE id = :id');
            $this->db->bind(':username', $username);
            $this->db->bind(':id', $id);
            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function modifyEmail($id, $email){
            $this->db->query('UPDATE users SET email = :email WHERE id = :id');
            $this->db->bind(':email', $email);
            $this->db->bind(':id', $id);
            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function modifyPassword($id, $password){
            $this->db->query('UPDATE users SET password = :password WHERE id = :id');
            $this->db->bind(':password', $password);
            $this->db->bind(':id', $id);
            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function modifyNotification($id, $notification){
            $this->db->query('UPDATE users SET notification = :notification WHERE id = :id');
            $this->db->bind(':notification', $notification);
            $this->db->bind(':id', $id);
            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function modifyPicture($id, $picture){
            $this->db->query('UPDATE users SET picture = :picture WHERE id = :id');
            $this->db->bind(':picture', $picture);
            $this->db->bind(':id', $id);
            if($this->db->execute())
                return true;
            else
                return false;
        }

        public function isToken($token){
            $this->db->query('SELECT token from users WHERE token = :token');
            $this->db->bind(':token', $token);
            $row = $this->db->single();

            if($this->db->rowCount() > 0)
                return $row;
            else
                return false;
        }

        public function tokenDone($token){
            $this->db->query('UPDATE users SET token = "done" WHERE token = :token');
            $this->db->bind(':token', $token);
            if($this->db->execute())
                return true;
            else
                return false;
        }        

    }