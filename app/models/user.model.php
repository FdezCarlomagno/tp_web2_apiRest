<?php
require_once 'config.php';
class User_model {
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host='.MYSQL_HOST .';dbname='.MYSQL_DB.';charset=utf8', MYSQL_USER, MYSQL_PASS);
    }

    public function getUserByNickname($nickname){
        $query = $this->db->prepare("SELECT * FROM usuario WHERE nickname = ?");
        $query->execute([$nickname]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
}