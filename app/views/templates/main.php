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
        <meta property="og:site_name" content="PHP Mini Framework" />
        <meta property="og:title" content="PHP Mini Framework - <?= $pageTitle ?>" />
        <meta property="og:description" content="<?= $pageDesc ?>" />
        <meta property="og:image" content="/images/php-logo.png" />
        <meta property="og:image:width" content="1280" />
        <meta property="og:image:height" content="670" />

        <!-- Resources -->
        <link href="/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="/css/styles.css" rel="stylesheet">
    </head>
    <body class="bg-dark text-white">

        <!-- Main Content -->
        <?= $this->section('content') ?>

        <!-- Script Resources -->
        <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>

    </body>
</html>