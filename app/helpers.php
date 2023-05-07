<?php

use App\Core\Config;
use App\Core\Request;
use App\Core\Session;

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
        return container(Config::class)->get($file, $key);
    }
}

/**
 * Access config values by using "." as the nesting delimiter
 */
if (!function_exists('request')) {
    function request(): Request
    {
        return container(Request::class);
    }
}

/**
 * Use session data
 */
if (!function_exists('session')) {
    function session(): Session
    {
        return container(Session::class);
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
        if (is_null(session()->get('csrf'))) {
            session()->set('csrf', bin2hex(random_bytes(50)));
        }

        ob_start();
        ?>
        <input type="hidden" name="csrf" value="<?= session()->get('csrf') ?>">
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
        $csrfSession = session()->get('csrf');
        $csrfInput = request()->input('csrf');
        if (is_null($csrfSession) || is_null($csrfInput)) {
            return false;
        }
        if ($csrfSession != $csrfInput) {
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
            session()->set(
                'flash_error_msg',
                'Invalid request. Possible cross site request forgery detected.'
            );
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
        $message = session()->get('flash_success_msg');
        $successAlert = '';
        if (!is_null($message) && $message != '') {
            ob_start();
            ?>
            <div class="alert alert-success mb-3" role="alert">
                <?= $message ?>
            </div>
            <?php
            $successAlert = ob_get_clean();
            session()->remove('flash_success_msg');
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
        $errors = session()->get('flash_error_msg');
        $errorAlert = '';
        if (!is_null($errors) && $errors != '') {
            ob_start();
            ?>
            <div class="alert alert-danger mb-3" role="alert">
                <?php if (is_array($errors)): ?>
                    <?php if (count($errors) > 1): ?>
                        <ul class="mb-0">
                            <?php foreach ($errors as $message): ?>
                                <li><?= $message ?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php elseif (count($errors) == 1): ?>
                        <?= $errors[0] ?>
                    <?php endif ?>
                <?php else: ?>
                    <?= $errors ?>
                <?php endif ?>
            </div>
            <?php
            $errorAlert = ob_get_clean();
            session()->remove('flash_error_msg');
        }

        return $errorAlert;
    }
}
