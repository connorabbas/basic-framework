<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Site Icon -->
        <link rel="icon" href="<?=BASE_DIR?>resources/images/php_PNG50.png"/>
        <link rel="shortcut icon" href="<?=BASE_DIR?>resources/images/php_PNG50.png"/>
        <link rel="apple-touch-icon" href="<?=BASE_DIR?>resources/images/php_PNG50.png"/>
        <link rel="apple-touch-icon-precomposed" href="<?=BASE_DIR?>resources/images/php_PNG50.png"/>

        <!-- Resources -->
        <link href="<?=BASE_DIR?>resources/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="<?=BASE_DIR?>resources/css/styles.css" rel="stylesheet">

        <!-- Scripts -->
        <script src='<?=BASE_DIR?>resources/js/jquery.min.js' type='text/javascript'  ></script>

        <!-- Page Title -->
        <title>PHP Mini Framework - <?=$pageTitle?></title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="<?=BASE_DIR?>">Home</a>
                        <a class="nav-link" href="<?=BASE_DIR?>tester">Test</a>
                    </div>
                </div>
            </div>
        </nav>
        <div id="contentContainer" class="">
            <!-- Site content from views starts -->
