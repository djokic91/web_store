<?php

// $stmt_createDatabase = $db->prepare("
// CREATE DATABASE IF NOT EXISTS `web_store`
// ");
// $stmt_createDatabase->execute();

$stmt_createUsersTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `users`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(30),
        `email` varchar (30),
        `password` varchar (80),
        `acc_type` enum('user','admin') DEFAULT 'user',
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT null
    )
");
$stmt_createUsersTable->execute();



$stmt_createCategoriesTable = $db->prepare("
        CREATE TABLE IF NOT EXISTS `categories`(
            `id` int AUTO_INCREMENT PRIMARY KEY,
            `title` varchar (30)
        )
");
$stmt_createCategoriesTable->execute();


$stmt_createCommentsTable = $db->prepare("
            CREATE TABLE IF NOT EXISTS `comments`(
                `id` int AUTO_INCREMENT PRIMARY KEY,
                `product_id` int,
                `user_id` int,
                `body` varchar (255),
                `created_at` datetime DEFAULT now(),
                `deleted_at` datetime DEFAULT null
            )
");

$stmt_createCommentsTable->execute();

$stmt_getUsers = $db->prepare("
                SELECT *
                FROM `users`
");
$stmt_getUsers->execute();
$num_of_users = $stmt_getUsers->rowCount();
$users = $stmt_getUsers->fetchALL();



if ($num_of_users <= 0) {

    $stmt_insertAdminAcc = $db->prepare("
    INSERT INTO `users` 
    (`name`, `email`, `password`, `acc_type`)
        VALUES
    (:name, :email, :password, :acc_type)
");

    $stmt_insertAdminAcc->execute([
        ':name' => $conf['admin_name'],
        ':email' => $conf['admin_email'],
        ':password' => $conf['admin_password'],
        ':acc_type' => 'admin'
    ]);
}



$stmt_createProductsTable = $db->prepare("
CREATE TABLE IF NOT EXISTS `products`(
    `id` int   AUTO_INCREMENT PRIMARY KEY,
    `cat_id` int,
    `title` varchar (255),
    `description` text,
    `price` int,
    `img` varchar (255),
    `created_at` datetime DEFAULT now(),
    `updated_at` datetime DEFAULT now() ON UPDATE now(),
    `deleted_at` datetime DEFAULT null

)
");
$stmt_createProductsTable->execute();

$stmt_createCartsTable = $db->prepare("
CREATE TABLE IF NOT EXISTS  `carts`(
    `id` int    AUTO_INCREMENT PRIMARY KEY,
    `user_id` int,
    `product_id` int,
    `quantity` int,
    `created_at` datetime DEFAULT now()

    ) 
");
$stmt_createCartsTable->execute();
