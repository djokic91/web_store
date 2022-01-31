<?php 
class Helper{

    public static function sessionStart(){
        if(! isset($_SESSION)){
            session_start();
        }

    }

    public static function addMessage($message){
        Helper::sessionStart();
        $_SESSION['message']= $message;
        var_dump($message);
    }

    public static function ifMessage(){
        Helper::sessionStart();
        return isset($_SESSION['message']);
    }

    public static function getMessage(){
        Helper::sessionStart();
        if(isset($_SESSION['message'])){
                $message= $_SESSION['message'];

                unset($_SESSION['message']);

                return $message;
        }
    }
    


    
    public static function addError($error){
        Helper::sessionStart();
        $_SESSION['error']= $error;
        var_dump($error);
    }

    public static function ifError(){
        Helper::sessionStart();
        return isset($_SESSION['error']);
    }

    public static function getError(){
        Helper::sessionStart();
        if(isset($_SESSION['error'])){
                $error= $_SESSION['error'];

                unset($_SESSION['error']);

                return $error;
        }
    }
    public static function getRequestedPageName(){
        $requestedUrl = $_SERVER['REQUEST_URI'];
        $requestedUrlParts = explode('/', $requestedUrl);
        $page = $requestedUrlParts[count($requestedUrlParts)-1];
        $pageName = str_replace('.php', '', $page); 
        $pageName = str_replace('-', ' ', $pageName); 
        return $pageName;
    }
    

}
