<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="./index.php">WebShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php"><i class="fas fa-home"></i> Home </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./products.php">Products</a>
                </li>

            </ul>


            <!-- DESNA STRANA -->
            <ul class="navbar-nav">

                <?php require_once './user.class.php'; ?>
                <?php require_once './product.class.php'; ?>
                <?php if (User::isLoggedIn()) { ?>

                    <?php
                    $u = new User();

                    $u->loadLoggedInUser();
                    $p = new Product();
                    ?>

                    <li class="nav-item">
                        <!-- <a href="./cart.php"><i class="fas fa-shopping-cart" style="color:white; font-size:30px;"></i></a> -->
                        <a href="./cart.php"><img class="img_cart" src="./img/icons/Cart-Icon-PNG-Graphic-Cave-e1461785088730-300x300.png"></a>
                        <sup><span class="badge badge-warning"><?php echo $p->numOfProductsInCart(); ?></span></sup>
                    </li>

                    <li class="nav-item dropdown" title="<?php echo $u->email; ?>" data-toggle="tooltip" data-placement="bottom">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $u->name; ?>

                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="./update-profile.php">Update profile <i class="fas fa-wrench" style="color: #969a9e;"></i></a>
                            <?php if ($u->acc_type == 'admin') { ?>
                                <h6 class="dropdown-header">Administration</h6>
                                <a class="dropdown-item" href="./manage-categories.php">Manage Categories</a>
                                <a class="dropdown-item" href="./add-product.php">Add Product <i class="fas fa-plus-circle" style="color: #969a9e;"></i></a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="./logout.php">Logout <i class="fas fa-sign-out-alt" style="color: #969a9e;"></i></a>
                        </div>
                    </li>

                <?php } else { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Create Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Login</a>
                    </li>

                <?php } ?>

            </ul>

        </div>
    </div>
</nav>