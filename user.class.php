<?php

class User{
    private $db;
    public $id;
    public $name;
    public $password;
    public $password_repeat;
    public $email;
    public $acc_type;
    public $created_at;
    public $updated_at;
    public $deleted_at; 

    function __construct($id=null){
        $this->db = require './db.inc.php';
        require_once './helper.class.php';

        if($id){
            $stmt_get=$this->db->prepare("
            SELECT *
            FROM `users`
            WHERE `id` = :id
            ");

            $stmt_get->execute([':id'=>$id]);
            $users = $stmt_get->fetch();
            $this->id = $users->id;
            $this->name = $users->name;
            $this->password = $users->password;
            $this->email = $users->email;
            $this->acc_type = $users->acc_type;
            $this->created_at = $users->created_at;
            $this->updated_at = $users->updated_at;
            $this->deleted_at = $users->deleted_at;
        }
    }


    public function emailIsValid(){
    
        if($this->email==""){
            Helper::addError("Email must be filled");
            return false;
        }

        if(! filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            Helper::addError("Email is not valid! ");
            return false;
        }
        

                $stmt_getUser = $this->db->prepare("
                SELECT *
                FROM `users`
                WHERE `email` = :email
                ");
                
                $stmt_getUser->execute([':email' =>$this->email]);
                
                if($stmt_getUser->rowCount() > 0){
                    Helper::addError("Email adress is already in use");
                    return false;
                }
                return true;
    }

    public function passwordIsValid(){
        if($this->password ==""){
            Helper::addError("Password can not be empty!");
            return false;
        }
        if($this->password!= $this->password_repeat){
            Helper::addError("Passwords do not match");
            return false;
        }
        return true;
    }


    public function newPasswordIsValid(){
        if(!$this->new_password){
            return true;
        }
        if($this->new_password == ""){
            Helper::addError("Password can not be empty!");
            return false;
        }
        if($this->new_password != $this->password_repeat){
            Helper::addError("Password don't match!");
            return false;
        }
        return true;
    }


    public function login(){
        $stmt_getUser =$this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `email` = :email
        AND `password` = :password
        ");
        $stmt_getUser->execute([
            ':email' => $this->email,
            ':password' => md5($this->password)
        ]); 

        if($stmt_getUser->rowCount() > 0){
            $user = $stmt_getUser->fetch();
            Helper::sessionStart();
            $_SESSION['user_id'] = $user->id;
            return true;
        } else{
            Helper::addError("invalid email or password.");
            return false;
        }
    }

    public static function isLoggedIn(){
        require_once './helper.class.php';
        Helper::sessionStart();
        if(isset($_SESSION['user_id'])){
            return true;
        
        }  

        return false;
    }

    public function loadLoggedInUser(){
        Helper::sessionStart();
        if(!User::isLoggedIn()){
            return false;
        }
        $stmt_getUser = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `id` = :id
        ");
        $stmt_getUser->execute([':id'=>$_SESSION['user_id'] ]);
        $user= $stmt_getUser->fetch();
        $this->id = $user->id;
        $this->name = $user->name;
        $this->password = $user->password;
        $this->email = $user->email;
        $this->acc_type = $user->acc_type;
        $this->created_at = $user->created_at;
        $this->updated_at = $user->updated_at;
        $this->deleted_at = $user->deleted_at;
    }


    public function insert(){
        if(!$this->emailIsValid()){
            return false;
        }
    
        if(!$this->passwordIsValid()){
            return false;
        }

        $stmt_insert = $this->db->prepare("
            INSERT INTO `users`
                (`name`, `password`, `email`)
            VALUES
                (:name, :password, :email)
        ");
        return $stmt_insert->execute([
            ':name'=>$this->name,
            ':password'=>md5($this->password),
            ':email'=>$this->email
        ]);
    }

    public function update(){
        if(!$this->newPasswordIsValid()){
                return false;
        }
    

        $stmt_update = $this->db->prepare("
        UPDATE `users`
        SET
            `name` = :name,
            `email` = :email,
            `password` = :password
        WHERE `id` = :id

        ");

        if($this->new_password){
            $password = md5($this->new_password);
        } else{
            $password = $this->password;
        }

        return $stmt_update->execute([
            ':id'=> $this->id,
            ':name'=> $this->name,
            ':email'=> $this->email,
            ':password'=> $password
        ]);

    }

}
