<?php

class Category
{
    private $db;
    public $id;
    public $title;

    function __construct($id = null)
    {
        $this->db = require './db.inc.php';

        if ($id) {
            $stmt_get = $this->db->prepare("
                SELECT *
                FROM `categories`
                where `id` = :id
            ");
            $stmt_get->execute([':id' => $id]);
            $cat = $stmt_get->fetch();
            $this->id = $cat->id;
            $this->title = $cat->title;
        }
    }

    public function insert()
    {

        $stmt_insert = $this->db->prepare("
            INSERT INTO `categories`
            (`title`)
            VALUES
            (:title)
        
        ");
        return $stmt_insert->execute([
            ':title' => $this->title
        ]);
    }

    public function all()
    {
        $stmt_all = $this->db->prepare("
        SELECT id, title, (
            SELECT count(*)
            FROM `products`
            WHERE `cat_id` = categories.id
            AND `deleted_at` IS NULL
            ) as num_of_products
        FROM `categories`
        ORDER BY `title` ASC
        ");
        $stmt_all->execute();
        return $stmt_all->fetchALL();
    }

    public function delete()
    {
        $stmt_delete = $this->db->prepare("
         DELETE 
         FROM `categories`
         WHERE `id` =  :id
         ");

        return $stmt_delete->execute(['id' => $this->id]);
    }
}
