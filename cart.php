<?php include './user-only.inc.php'; ?>
<?php require_once './product.class.php'; ?>
<?php require_once './helper.class.php'; ?>

<?php

$p = new Product();

if (isset($_POST['remove_from_cart'])) {
  if ($p->removeFromCart($_POST['cart_id'])) {
    Helper::addMessage("You deleted product from cart!");
  }
}

if (isset($_POST['update'])) {
  if ($p->updateCart($_POST['cart_update_id'], $_POST['updated_quantity'])) {
    Helper::addMessage("You updated quantity of product");
  }
}


$products = $p->getCart();

?>


<?php require './header.layout.php'; ?>

<h1 class="mt-5">Cart</h1>


<table class="table">
  <thead class="table-primary">
    <tr>
      <th scope="col">Title</th>
      <th scope="col">Quantity</th>
      <th scope="col">Price</th>
      <th scope="col">Total price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php $totalPrice = 0; ?>
    <?php foreach ($products as $product) { ?>
      <?php $totalPrice += $product->quantity * $product->price; ?>
      <tr class="table-light">
        <td><?php echo $product->title; ?></td>
        <td>
          <form action="./cart.php" method="POST">
            <div class="input-group  input-group-sm">
              <input type="hidden" name="cart_update_id" value="<?php echo $product->id; ?>">
              <input class="form-control" type="number" name="updated_quantity" placeholder="Enter quantity" aria-label="Search" value="<?php echo $product->quantity; ?>" min="1">
              <div class="input-group-append">
                <button class="btn btn-outline-success" name="update">Update</button>
              </div>
            </div>
          </form>
        </td>
        <td><?php echo $product->price; ?></td>
        <td><?php echo $product->quantity * $product->price; ?></td>
        <td>
          <form action="./cart.php" method="POST">
            <input type="hidden" name="cart_id" value="<?php echo $product->id; ?>">
            <button class="btn btn-outline-danger btn-sm" name="remove_from_cart">Remove from cart</button>
          </form>
        </td>
      </tr>
    <?php } ?>
    <tr class="table-warning">
      <td></td>
      <td></td>
      <td><strong>Total price:</strong></td>
      <td><strong><?php echo $totalPrice; ?> RSD</strong></td>
      <td></td>
    </tr>
  </tbody>
</table>

<?php require './footer.layout.php'; ?>