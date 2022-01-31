<?php require './user-only.inc.php'; ?>
<?php require_once './product.class.php'; ?>

<?php
$p = new Product();
if (isset($_GET['cat_id'])) {
  $products = $p->fromCategory($_GET['cat_id']);
}

if (isset($_POST['delete'])) {

  $product = new Product($_POST['id']);
  if ($product->delete()) {
    Helper::addMessage("Product deleted successfully!");
  } else {
    Helper::addError("Failed to delete product");
  }
}

$products = $p->all();

?>

<?php include './header.layout.php'; ?>


<h1 class="mt-5">Products</h1>
<div class="row">
  <?php foreach ($products as $product) { ?>

    <div class="col-md-4">
      <div class="card mt-4">

        <img class="card-img-top product-image" src="<?php echo $product->img; ?>">
        <div class="card-body clearfix">
          <h5 class="card-title">
            <?php echo $product->title; ?>
          </h5>
          <p class="card-text">
            <b>Price:</b> <?php echo $product->price; ?> RSD
          </p>
          <a href="./product-details.php?id=<?php echo $product->id; ?>" <button class="btn btn-outline-primary">Details</button></a>
          <?php if ($u->acc_type == 'admin') { ?>
            <form action="./products.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $product->id; ?>">
              <button name="delete" class="btn btn-danger" style="margin-top: 5px;">Delete</button>
            </form>
          <?php } ?>

        </div>

      </div>

    </div>
  <?php } ?>

</div>

<?php include './footer.layout.php'; ?>