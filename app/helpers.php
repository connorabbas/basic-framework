<?php

use App\Data\Config;

// Helper functions available anywhere within the application

/**
 * access config values by using '.' as the nesting delimiter
 */
if (!function_exists('config')) {
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
}

/**
 * Spoof the request method for an html form
 */
if (!function_exists('methodSpoof')) {
    function methodSpoof(string $method): string
    {
        $validMethods = ['PUT', 'PATCH', 'DELETE'];
        $method = strtoupper($method);
        $input = '';

        if (in_array($method, $validMethods)) {
            ob_start();
            ?>
            <input type="hidden" name="_method" value="<?= $method ?>">
            <?php
            $input = ob_get_clean();
        }

        return $input;
    }
}

/**
 * Set csrf token input for form
 */
if (!function_exists('csrf')) {
    function csrf()
    {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(50));
        }

        ob_start();
        ?>
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <?php
        $input = ob_get_clean();

        return $input;
    }
}

/**
 * Check csrf data from form is valid
 */
if (!function_exists('csrfValid')) {
    function csrfValid()
    {
        if (!isset($_SESSION['csrf']) || !isset($_REQUEST['csrf'])) {
            return false;
        }
        if ($_SESSION['csrf'] != $_REQUEST['csrf']) {
            return false;
        }

        return true;
    }
}

/**
 * Redirect to a different route
 */
if (!function_exists('redirect')) {
    function redirect(string $route)
    {
        header("location: " . $route);
        exit();
    }
}

/**
 * Redirect to previous page
 */
if (!function_exists('back')) {
    function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

if (!function_exists('successFlashMessage')) {
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
}

if (!function_exists('errorFlashMessage')) {
    function errorFlashMessage()
    {
        $errorAlert = '';
        if (isset($_SESSION['flash_error_msg']) && $_SESSION['flash_error_msg'] != '') {
            ob_start();
            ?>
            <div class="alert alert-danger mb-3" role="alert">
                <?php if(is_array($_SESSION['flash_error_msg'])): ?>
                    <?php foreach ($_SESSION['flash_error_msg'] as $message): ?>
                        <?= $message ?><br>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= $_SESSION['flash_error_msg'] ?>
                <?php endif ?>
            </div>
            <?php
            $errorAlert = ob_get_clean();
            unset($_SESSION["flash_error_msg"]);
        }

        return $errorAlert;
    }
}
