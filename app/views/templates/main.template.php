<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Page Title -->
        <title>PHP Mini Framework - <?= $pageTitle ?></title>

        <!-- SEO Tags -->
        <meta name="robots" content="max-snippet:-1,max-image-preview:standard,max-video-preview:-1" />
        <meta name="description" content="<?= $pageDesc ?>" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?= URL ?>" />
        <meta property="og:site_name" content="JDS Industries" />
        <meta property="og:title" content="JDS Industries - <?= $pageTitle ?>" />
        <meta property="og:description" content="<?= $pageDesc ?>" />
        <meta property="og:image" content="<?= App::path('/resources/images/php-logo.png') ?>" />
        <meta property="og:image:width" content="1280" />
        <meta property="og:image:height" content="670" />

        <!-- Resources -->
        <link href="<?= App::path('/resources/css/bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?= App::path('/resources/css/styles.css') ?>" rel="stylesheet">
        <link href="<?= App::path('/resources/css/animate.min.css') ?>" rel="stylesheet">

        <!-- Scripts -->
        <script src='<?= App::path('/resources/js/jquery.min.js') ?>' type='text/javascript'  ></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">php-mf</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="<?= App::path('/') ?>">Home</a>
                        <a class="nav-link" href="<?= App::path('/tester') ?>">Tester Page</a>
                    </div>
                </div>
            </div>
        </nav>
        <div id="contentContainer" class="animate__animated animate__fadeIn">
            <?php
            // Content View
            App::view($view, $data, null);
            ?>
        </div>

        <!-- Script Resources -->
        <script src="<?= App::path('/resources/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>

    </body>
</html>