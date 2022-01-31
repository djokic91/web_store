<?php 
require_once './helper.class.php ';
require_once './user.class.php ';

$u = new User();
$u->loadLoggedInUser();

if($u->acc_type !='admin'){
    $pageName = Helper::getRequestedPageName();
    Helper::addError("You need administrator account to access <b>$pageName</b> page!");
    header("Location: ./index.php");
    die();


}
