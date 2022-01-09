<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Resources -->
        <link href="<?=App::route('/resources/css/bootstrap/bootstrap.min.css')?>" rel="stylesheet">
        <link href="<?=App::route('/resources/css/styles.css')?>" rel="stylesheet">
        <link href="<?=App::route('/resources/css/animate.min.css')?>" rel="stylesheet">

        <!-- Scripts -->
        <script src='<?=App::route('/resources/js/jquery.min.js')?>' type='text/javascript'  ></script>

        <!-- Page Title -->
        <title>PHP Mini Framework - <?=$pageTitle?></title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="<?=App::route('/')?>">Home</a>
                        <a class="nav-link" href="<?=App::route('/tester')?>">Tester Page</a>
                    </div>
                </div>
            </div>
        </nav>
        <div id="contentContainer" class="animate__animated animate__fadeIn">
            <!-- Site content from views starts -->
