<?php 
require_once './helper.class.php ';
require_once './user.class.php ';

if(!User::isLoggedIn()){
    $pageName = Helper::getRequestedPageName();
    Helper::addError("You need to be logged in to access <b>$pageName</b> page!");
    header("Location: ./login.php");
    die();
}
