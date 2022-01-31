<?php require_once './helper.class.php'; ?>

<?php Helper::sessionStart(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WebShop</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/fontawesome-all.min.css">
</head>

<body>



    <?php include 'navbar.inc.php'; ?>

    <div class="container" style="margin-top: 64px ;">
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <?php include './sidebar.inc.php'; ?>
                </div>
            </div>

            <div class="col-md-9 d-flex justify-content-center">


                <?php if (Helper::ifMessage()) { ?>
                    <div class="alert alert-success">

                        <strong>Success!</strong>
                        <?php echo Helper::getMessage(); ?>

                    </div>
                <?php } ?>




                <?php if (Helper::ifError()) { ?>
                    <div class="alert alert-danger">

                        <strong>Error!</strong>
                        <?php echo Helper::getError(); ?>

                    </div>
                <?php } ?>
                <div class="content">