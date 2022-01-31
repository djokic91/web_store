<?php
require_once './helper.class.php';

Helper::sessionStart();

if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

header("Location: ./index.php");
