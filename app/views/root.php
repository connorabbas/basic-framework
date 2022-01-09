<?php
$pageTitle = 'Home';
require_once('./app/views/layout/header.php');
?>

<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1 class="mb-5">Welcome to PHP Mini Framework!</h1>
            <p>Features:</p>
            <ul>
                <li>Routing for GET and POST requests</li>
                <li>MVC architecture</li>
                <li>Class auto loading</li>
                <li>PDO database class</li>
                <li>Bootstrap 5, jQuery, and React included</li>
            </ul>
        </div>
    </div>
</div>

<?php
require_once('./app/views/layout/footer.php');
?>