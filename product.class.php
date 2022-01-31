<?php
class Product
{

    private $db;
    public $id;
    public $title;
    public $cat_id;
    public $description;
    public $price;
    public $img;
    public $imageData;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $productImgDir = './img/products/';


    function __construct($id = null)
    {
        $this->db = require './db.inc.php';

        if ($id) {
            $stmt_get = $this->db->prepare("
                SELECT *
                FROM `products`
                where `id` = :id
            ");
            $stmt_get->execute([':id' => $id]);
            $product = $stmt_get->fetch();
            $this->id = $product->id;
            $this->title = $product->title;
            $this->cat_id = $product->cat_id;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->img = $product->img;
            $this->created_at = $product->created_at;
            $this->updated_at = $product->updated_at;
            $this->deleted_at = $product->deleted_at;
        }
    }

    public function insert()
    {



        $stmt_insert = $this->db->prepare("
            INSERT INTO `products`
            (`cat_id`, `title`, `description`,`price`,`img`)
            VALUES
            (:cat_id, :title, :description, :price, :img)
        
        ");
        $res = $stmt_insert->execute([

            ':cat_id' => $this->cat_id,
            ':title' => $this->title,
            ':description' => $this->description,
            ':price' => $this->price,
            ':img' => $this->img

        ]);

        if (!file_exists($this->productImgDir)) {
            mkdir($this->productImgDir, 0777, true);
        }

        $productId = $this->db->lastInsertId();
        $ext = pathinfo($this->imageData['name'], PATHINFO_EXTENSION);

        // $imagePath = $this->productImgDir . $productId . '.' . $ext ;
        $imagePath = "{$this->productImgDir}$productId.$ext";


        move_uploaded_file($this->imageData['tmp_name'], $imagePath);
        $this->id = $productId;
        $this->img = $imagePath;
        $this->update();

        return $res;
    }

    public function all()
    {
        $stmt_all = $this->db->prepare("
        SELECT *
        FROM `products`
        WHERE `deleted_at` IS NULL
        ");
        $stmt_all->execute();
        return $stmt_all->fetchALL();
    }

    public function delete()
    {
        $stmt_delete = $this->db->prepare("
        UPDATE `products`
        SET `deleted_at` = now()
        WHERE `id` = :id
        ");

        return $stmt_delete->execute(['id' => $this->id]);
    }

    public function update()
    {
        $stmt_update = $this->db->prepare("
            UPDATE `products`
            SET
                `title` = :title,
                `cat_id` = :cat_id,
                `description` = :description,
                `price` = :price,
                `img` = :img
            WHERE `id` = :id

       ");
        return $stmt_update->execute([
            'id' => $this->id,
            'title' => $this->title,
            'cat_id' => $this->cat_id,
            'description' => $this->description,
            'price' => $this->price,
            'img' => $this->img

        ]);
    }

    public function fromCategory($cat_id)
    {
        $stmt_fromCategory = $this->db->prepare("
            SELECT *
            FROM `products`
            WHERE `deleted_at` IS NULL
            AND `cat_id` = :cat_id
        ");

        $stmt_fromCategory->execute([
            ':cat_id' => $cat_id
        ]);

        return $stmt_fromCategory->fetchAll();
    }

    public function addToCart($quantity)
    {
        require_once './helper.class.php';
        Helper::sessionStart();
        $stmt_getCarts = $this->db->prepare("
            SELECT *
            FROM `carts`
            WHERE `product_id` = :product_id
            AND `user_id` = :user_id
        ");
        $stmt_getCarts->execute([
            ':product_id' => $this->id,
            ':user_id' => $_SESSION['user_id']
        ]);

        $cart = $stmt_getCarts->fetch();

        if ($stmt_getCarts->rowCount() > 0) {
            $stmt_updateQuantity = $this->db->prepare("
                UPDATE `carts`
                SET `quantity` = :new_quantity
                WHERE `id` = :id
            ");

            return $stmt_updateQuantity->execute([
                ':new_quantity' => $cart->quantity + $quantity,
                ':id' => $cart->id
            ]);
        } else {
            $stmt_insertIntoCart = $this->db->prepare("
                INSERT INTO `carts`
                (`product_id`, `user_id`, `quantity`)
                VALUES
                (:product_id, :user_id, :quantity)
            ");

            return $stmt_insertIntoCart->execute([
                ':product_id' => $this->id,
                ':user_id' => $_SESSION['user_id'],
                ':quantity' => $quantity
            ]);
        }
    }


    public function getCart()
    {
        require_once './helper.class.php';
        Helper::sessionStart();

        $stmt_getCart = $this->db->prepare("
            SELECT 
            carts.id,
            products.title,
            products.price,
            carts.quantity
            FROM `carts`, `products`
            WHERE `carts`.`product_id` = `products`.`id`
            AND `user_id` = :user_id
        ");

        $stmt_getCart->execute([
            ':user_id' => $_SESSION['user_id']
        ]);

        return $stmt_getCart->fetchAll();
    }


    public function removeFromCart($id)
    {
        $stmt_removeFromCart = $this->db->prepare("
            DELETE 
            FROM `carts`
            WHERE `id` = :id
        ");

        return $stmt_removeFromCart->execute([
            ':id' => $id
        ]);
    }

    public function updateCart($id, $new_quantity)
    {
        $stmt_updateCart = $this->db->prepare("
            UPDATE `carts`
            SET `quantity` = :updated_quantity
            WHERE `id` = :id
        ");

        return $stmt_updateCart->execute([
            ':updated_quantity' => $new_quantity,
            ':id' => $id
        ]);
    }

    public function insertComment($body)
    {
        require_once './helper.class.php';
        Helper::sessionStart();
        $stmt_insertComment = $this->db->prepare("
            INSERT INTO `comments`
            (`product_id`, `user_id`, `body`)
            VALUES
            (:product_id, :user_id, :body)
        ");

        return $stmt_insertComment->execute([
            ':product_id' => $this->id,
            ':user_id' => $_SESSION['user_id'],
            ':body' => $body
        ]);
    }

    public function getComments()
    {
        $stmt_getComments = $this->db->prepare("
            SELECT 
            `comments`.`body`,
            `comments`.`id`,
            `comments`.`user_id`,
            `comments`.`created_at`,
            `users`.`name`


            FROM `comments`, `users`
            WHERE `comments`.`user_id` = `users`.`id`
            AND `comments`.`product_id` = :product_id
            AND `comments`.`deleted_at` IS NULL
            ORDER BY `comments`.`created_at` DESC
        ");
        $stmt_getComments->execute([
            ':product_id' => $this->id
        ]);

        return $stmt_getComments->fetchAll();
    }

    public function deleteComment($id)
    {
        $stmt_deleteComment = $this->db->prepare("
            UPDATE `comments`
            SET `deleted_at` = now()
            WHERE `id` = :id
         

        ");

        return $stmt_deleteComment->execute([
            'id' => $id
        ]);
    }

    public function numOfProductsInCart()
    {
        require_once './helper.class.php';
        Helper::sessionStart();

        if (!isset($_SESSION['user_id'])) {
            return 0;
        }

        $stmt_numOfProductsInCart = $this->db->prepare("
            SELECT *
            FROM `carts`
            WHERE `user_id` = :user_id
        ");

        $stmt_numOfProductsInCart->execute([
            ':user_id' => $_SESSION['user_id']
        ]);

        return $stmt_numOfProductsInCart->rowCount();
    }
}
