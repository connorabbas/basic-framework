<?php

use League\Plates\Engine;

// Helper functions available anywhere within the application

function view($view, $data = [])
{
    // View templating using Plates from composer
    // docs: https://platesphp.com/
    if (file_exists('../app/views/' . $view . '.php')) {
        $templates = new Engine('../app/views/');
        $templates->addFolder('template', '../app/views/templates/');
        echo $templates->render($view, $data);
    } 
    // Not found
    else {
        require_once('../app/views/pages/404.php');
    } 
}

function set_csrf()
{
    if ( ! isset($_SESSION["csrf"]) ) { $_SESSION["csrf"] = bin2hex(random_bytes(50)); }
    return '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
}

function is_csrf_valid()
{
    if ( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])) { return false; }
    if ( $_SESSION['csrf'] != $_POST['csrf']) { return false; }
    return true;
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
