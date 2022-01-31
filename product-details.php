<?php require_once './helper.class.php' ?>
<?php require_once './product.class.php' ?>
<?php require_once './user.class.php' ?>
<?php

if (!isset($_GET['id'])) {

    header("Location: ./products.php");
    die();
}


$product = new Product($_GET['id']);


if (isset($_POST['addToCart'])) {
    if ($product->addToCart($_POST['quantity'])) {
        Helper::addMessage("{$product->title} <small> x {$_POST['quantity']}</small> successfully added to cart!");
    } else {
        Helper::addError("Failed to add {$product->title} to cart !");
    }
}


if (isset($_POST['comment'])) {
    $product->insertComment(nl2br($_POST['body']));
}


if (isset($_POST['remove_comment'])) {

    if ($product->deleteComment($_POST['comment_id'])) {
        Helper::addMessage('Comment deleted.');
    } else {
        Helper::addError('Failed to delete comment.');
    }
}

$comments = $product->getComments();

?>


<?php require './header.layout.php'; ?>

<h1 class="mt-5">
    <?php echo $product->title; ?>
</h1>

<div class="row">
    <div class="col-md-5">
        <img src="<?php echo $product->img; ?>" class="img-fluid">
    </div>
    <div class="col-md-7">
        <h3 class="mb-5">Description</h3>
        <p>
            <?php echo $product->description; ?>
        </p>

        <h5 class="float-right mt-5">
            <?php echo $product->price; ?>RSD</h5>
        <br clear="all" />
        <form action="./product-details.php?id=<?php echo $_GET['id']; ?>" method="POST" class="float-right">
            <div class="input-group mt-3">
                <input type="number" name="quantity" class="form-control" value="1" min="1" />
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" name="addToCart">Add to cart</button>
                </div>
            </div>

        </form>


    </div>
</div>

<h3 class="mt-5 mb-4">Comments</h3>

<form action="./product-details.php?id=<?php echo $product->id; ?>" method="POST">
    <div class="form-group">
        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
        <textarea class="form-control" id="textarea" name="body" rows="3" placeholder="Enter Your Comment" style="border-radius:8pt;"></textarea>
    </div>
    <button name="comment" class="btn btn-outline-info mb-5 float-right" style="border-radius: 8pt;">Post a comment</button>
    <br clear="all" />
</form>

<?php foreach ($comments as $comment) {; ?>

    <?php
    require_once './user.class.php';
    $loggedInUser = new User();
    $loggedInUser->loadLoggedInUser();
    $canDeleteComment = false;

    if ($loggedInUser->acc_type == 'admin') {
        $canDeleteComment = true;
    }
    if ($comment->user_id == $loggedInUser->id) {
        $canDeleteComment = true;
    }

    ?>

    <div class="comment mb-2 row">
        <div class="comment-avatar col-md-1 col-sm-2 text-center pr-1">
            <a href="">
                <i class="fas fa-user" style="font-size:30px;"></i>
            </a>
        </div>
        <div class="comment-content col-md-11 col-sm-10">
            <h6 class="small comment-meta">
                <p style="font-size:14px; color: #969a9e;">
                    Posted by: (
                    <strong>
                        <?php echo $comment->name; ?>
                    </strong>)
                    <?php echo $comment->created_at; ?>
                </p>
            </h6>
            <?php if ($canDeleteComment) { ?>
                <form action="" method="POST">
                    <button name="remove_comment" class="btn btn-outline-danger btn-sm float-right mb-5" style="border-radius: 8px;">Remove Comment</button>
                    <input type="hidden" name="comment_id" value="<?php echo $comment->id; ?>">
                </form>
            <?php } ?>
            <div class="comment-body">
                <p style="word-wrap:break-word;">
                    <?php echo $comment->body; ?>
                    <br>

                </p>
            </div>
        </div>
    </div>

<?php }; ?>




<?php require './footer.layout.php'; ?>