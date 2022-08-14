<?php

use App\Data\Config;

// Helper functions available anywhere within the application

/**
 * access config values by using '.' as the nesting delimiter
 */
function config(string $configPath)
{
    $configKeys = explode('.', $configPath);
    $config = (new Config($_ENV))->get();
    $finalKey = $config;

    for ($i = 0; $i < count($configKeys); $i++) {
        $finalKey = $finalKey[$configKeys[$i]];
    }

    return $finalKey;
}

/**
 * Set csrf token input for form
 */
function csrf()
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(50));
    }

    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}

/**
 * Check csrf data from form is valid
 */
function csrfValid()
{
    if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
        return false;
    }
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        return false;
    }

    return true;
}

/**
 * Redirect to a different route
 */
function redirect(string $route)
{
    header("location: " . $route);
    exit();
}

function successFlashMessage()
{
    $successAlert = '';
    if (isset($_SESSION['flash_success_msg']) && $_SESSION['flash_success_msg'] != '') {
        ob_start();
        ?>
        <div class="alert alert-success mb-3" role="alert">
            <?= $_SESSION['flash_success_msg'] ?>
        </div>
        <?php
        $successAlert = ob_get_clean();
        unset($_SESSION["flash_success_msg"]);
    }

    return $successAlert;
}

function errorFlashMessage()
{
    $errorAlert = '';
    if (isset($_SESSION['flash_error_msg']) && $_SESSION['flash_error_msg'] != '') {
        ob_start();
        ?>
        <div class="alert alert-danger mb-3" role="alert">
            <?php
            if (is_array($_SESSION['flash_error_msg'])) {
                foreach ($_SESSION['flash_error_msg'] as $message) {
                    echo $message . '<br>';
                }
            } else {
                echo $_SESSION['flash_error_msg'];
            }
            ?>
        </div>
        <?php
        $errorAlert = ob_get_clean();
        unset($_SESSION["flash_error_msg"]);
    }

    return $errorAlert;
}

function dump($data)
{
    ?>
    <style>
        pre.dump{
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <?php
    echo '<pre class="dump">';
    var_dump($data);
    echo '</pre>';
}

function dd($data)
{
    ?>
    <style>
        pre.dump{
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <?php
    echo '<pre class="dump">';
    var_dump($data);
    echo '</pre>';
    die();
}
