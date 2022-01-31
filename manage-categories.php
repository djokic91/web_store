<?php require './admin-only.inc.php'; ?>
<?php require_once './category.class.php'; ?>
<?php require_once './helper.class.php'; ?>




<?php
$c = new Category();

if (isset($_POST['add'])) {
  if ($_POST['title'] != "") {
    $c->title = $_POST['title'];
    if ($c->insert()) {
      Helper::addMessage("Category added successfully!");
    } else {
      Helper::addError("Failed to add category!");
    }
  } else {
    Helper::addError("Category title is required!");
  }
}

if (isset($_POST['delete'])) {

  $cat = new Category($_POST['id']);
  if ($cat->delete()) {
    Helper::addMessage("Category deleted successfully!");
  } else {
    Helper::addError("Failed to delete category");
  }
}

$categories = $c->all();

?>


<?php include './header.layout.php'; ?>
<h1 class="mt-5">Manage categories</h1>

<form action="./manage-categories.php" method="POST">

  <div class="input-group mb-3">
    <input name="title" type="text" class="form-control" placeholder="Enter category" aria-label="Recipient's username" aria-describedby="basic-addon2">
    <div class="input-group-append">
      <button class="btn btn-primary" name="add">Add categoty</button>
    </div>
  </div>

</form>

<div class="container mt-4">

  <h3>All categories</h3><br>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Title</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $cat) {  ?>

        <tr>
          <th scope="row"><?php echo $cat->id; ?></th>
          <td><?php echo $cat->title; ?></td>

          <td>
            <form action="./manage-categories.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $cat->id; ?>">
              <button name="delete" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>

      <?php } ?>

    </tbody>
  </table>
</div>

<?php include './footer.layout.php'; ?>