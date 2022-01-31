<?php

$conf = require './config.inc.php';

try {
    $db = new PDO(
        "mysql:host={$conf['dbhost']};dbname={$conf['dbname']};charset=utf8",
        $conf["dbuser"],
        $conf["dbpass"]
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    require "./initdb.php";
    return $db;
} catch (PDOException $e) {
    var_dump($e);
    return false;
}
