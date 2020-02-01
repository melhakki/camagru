<?php
    class Post {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getLastId(){
            $this->db->query('SELECT max(id) as id FROM posts');
            $row = $this->db->single();
            return ($row);
        }

        public function getPosts($start, $plus){
            $this->db->query('SELECT * FROM posts order by create_date desc LIMIT ' . $start . ', '. $plus);
            $row = $this->db->resultSet();
            return ($row);
        }

        public function getPostById($id){
            $this->db->query('SELECT * FROM posts WHERE id = :id order by create_date desc');
            $this->db->bind(':id', $id);
            $row = $this->db->single();
            if($this->db->single())
                return $row;
            else
                return false;
        }


        public function getMyPosts($user_id){
            $this->db->query('SELECT * FROM posts WHERE user_id = :user_id order by create_date desc');
            $this->db->bind(':user_id', $user_id);
            $rows = $this->db->resultSet();
            if ($this->db->resultSet())
                return $rows;
            else
                return false;
        }

        public function addPost($user_id, $image){
            $this->db->query('INSERT into posts (image, user_id, create_date) values (:image, :user_id, NOW())');
            $this->db->bind(':image', $image);
            $this->db->bind(':user_id', $user_id);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function isMyPost($id , $user_id){
            $this->db->query('SELECT * FROM posts WHERE id = :id AND user_id = :user_id');
            $this->db->bind(':id', $id);
            $this->db->bind(':user_id', $user_id);
            $row = $this->db->single();
            if($this->db->single())
                return true;
            else
                return false;
        }

        public function deletePost($id){
            $this->db->query('
            DELETE FROM posts WHERE id = :id;
            DELETE FROM likes WHERE post_id = :id;
            DELETE FROM comments WHERE post_id = :id;');
            $this->db->bind(':id', $id);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function updateLikeInPost($id, $nb_likes){
            $this->db->query('UPDATE posts SET nb_likes = :nb_likes WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':nb_likes', $nb_likes);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function addLike($post_id, $user_id){
            $this->db->query('INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':post_id', $post_id);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function disLike($post_id, $user_id){
            $this->db->query('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':post_id', $post_id);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function alredyLike($post_id, $user_id){
            $this->db->query('SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':post_id', $post_id);
            
            if($this->db->single())
                return true;
            else
                return false;
        }

        public function getComments($post_id){
            $this->db->query('SELECT * FROM comments WHERE post_id = :post_id order by comment_date	 desc');
            $this->db->bind(':post_id', $post_id);
            $rows = $this->db->resultSet();
            return $rows;
        }

        public function addComment($user_id, $post_id, $comment){
            $this->db->query('INSERT INTO comments (user_id, post_id, comment, comment_date) VALUES (:user_id, :post_id, :comment, NOW())');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':post_id', $post_id);
            $this->db->bind(':comment', $comment);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

        public function updateCommentInPost($id, $nb_comments){
            $this->db->query('UPDATE posts SET nb_comments = :nb_comments WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':nb_comments', $nb_comments);
            if ($this->db->execute())
                return (true);
            else
                return (false);
        }

    }