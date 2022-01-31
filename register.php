<?php require './user.class.php'; ?>
<?php require './helper.class.php'; ?>

<?php
if (isset($_POST['register'])) {
  $u = new User();
  $u->name = $_POST['name'];
  $u->email = $_POST['email'];
  $u->password = $_POST['password'];
  $u->password_repeat = $_POST['password_repeat'];
  if ($u->insert()) {
    Helper::addMessage("Account created successfully!");
    header("Location: ./login.php");
  }
}

?>


<?php require './header.layout.php';  ?>

<h1 class="mt-5">Register</h1>

<form action="./register.php" method="post">
  <div class="form-group">
    <label for="input_name">Name</label>
    <input type="text" class="form-control" id="input_name" placeholder="Enter name" name="name" required>
  </div>
  <div class="form-group">
    <label for="input_email">Email address</label>
    <input type="email" class="form-control" id="input_email" placeholder="Enter email" name="email" required>
  </div>
  <div class="form-group">
    <label for="input_password">Password</label>
    <input type="password" class="form-control" id="input_password" placeholder="Password" name="password" required>
  </div>
  <div class="form-group">
    <label for="input_password_repeat">Password repeat</label>
    <input type="password" class="form-control" id="input_password_repeat" placeholder="Password" name="password_repeat" required>
  </div>
  <button class="btn btn-primary" name="register">Create account</button>
</form>



<?php require './footer.layout.php';  ?>