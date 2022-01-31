<?php require_once './category.class.php'; ?>
<?php require_once './product.class.php'; ?>
<?php
$c = new Category();
$categories = $c->all();; ?>



<h1 class="mt-5">Categories</h1>
<div class="list-group">
    <a href="#" class="list-group-item list-group-item-action active">
        Computer Equipment
    </a>
    <?php foreach ($categories as $category) {  ?>
        <a href="./products.php?cat_id=<?php echo $category->id; ?>" class="list-group-item list-group-item-action d-flex flex-row justify-content-between align-items-center">
            <?php echo $category->title; ?>
            <span class="badge badge-warning "><?php echo $category->num_of_products ?></span></a>

    <?php } ?>


</div>