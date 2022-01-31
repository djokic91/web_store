<?php require_once './user.class.php'; ?>
<?php require_once './helper.class.php'; ?>
<?php

if (User::isloggedIn()) {
  header("Location: ./index.php");
}

if (isset($_POST['login'])) {
  $u = new User();
  $u->email = $_POST['email'];
  $u->password = $_POST['password'];
  if ($u->login()) {
    Helper::addMessage("Succsessfully login");

    header("Location: ./index.php");
    die();
  }
}



?>


<?php require './header.layout.php'; ?>

<h1 class="mt-5">Login</h1>

<form action="./login.php" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
  </div>
  <button class="btn btn-primary" name="login">Login</button>
</form>














<?php require './footer.layout.php'; ?>