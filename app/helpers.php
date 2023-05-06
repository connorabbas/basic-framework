<?php

/**
 * Helper functions available anywhere within the application
 */

/**
 * Access classes in the container
 */
if (!function_exists('container')) {
    function container(string $reference)
    {
        global $container; // don't hate me
        return $container->get($reference);
    }
}

/**
 * Passthrough method for using Config::get()
 * Access values by using "." as the nesting delimiter for the $key
 */
if (!function_exists('config')) {
    function config(string $file, string $key)
    {
        return container(App\Core\Config::class)->get($file, $key);
    }
}

/**
 * Access config values by using "." as the nesting delimiter
 */
if (!function_exists('request')) {
    function request()
    {
        return container(App\Core\Request::class);
    }
}

/**
 * Access config values by using "." as the nesting delimiter
 */
if (!function_exists('current_url')) {
    function current_url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        return $url;
    }
}

/**
 * Spoof the request method for an html form
 */
if (!function_exists('method_spoof')) {
    function method_spoof(string $method): string
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
if (!function_exists('csrf_valid')) {
    function csrf_valid()
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
 * Handle the logic to check for CSRF
 */
if (!function_exists('handle_csrf')) {
    function handle_csrf()
    {
        if (!csrf_valid()) {
            $_SESSION['flash_error_msg'] = 'Invalid request. Possible cross site request forgery detected.';
            return back();
        }
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

/**
 * Flash a success message
 */
if (!function_exists('success_flash_message')) {
    function success_flash_message()
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

/**
 * Flash error message/s
 */
if (!function_exists('error_flash_message')) {
    function error_flash_message()
    {
        $errorAlert = '';
        if (isset($_SESSION['flash_error_msg']) && $_SESSION['flash_error_msg'] != '') {
            ob_start();
            ?>
            <div class="alert alert-danger mb-3" role="alert">
                <?php if(is_array($_SESSION['flash_error_msg'])): ?>
                    <?php if(count($_SESSION['flash_error_msg']) > 1): ?>
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['flash_error_msg'] as $message): ?>
                                <li><?= $message ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php elseif(count($_SESSION['flash_error_msg']) == 1): ?>
                        <?= $_SESSION['flash_error_msg'][0] ?>
                    <?php endif ?>
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
