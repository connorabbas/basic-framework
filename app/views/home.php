<?php
$this->layout('template::main', [
    'pageTitle' => 'Home',
    'pageDesc' => "Welcome to the php mini framework!",
]);
?>
<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1 class="mb-3">PHP Mini Framework</h1>
            <h5 class="mb-5">Developed and maintained by <a href="https://github.com/connorabbas" target="_blank">Connor Abbas</a></h5>
            <h5>Features:</h5>
            <ul>
                <li>Routing for GET, POST, PATCH, PUT & DELETE HTTP requests</li>
                <li>MVC architecture</li>
                <li>Basic CLI commands for creating Models & Controllers</li>
                <li>Class auto loading</li>
                <li>PDO database class</li>
                <li>Bootstrap 5 & animate.css included</li>
            </ul>
        </div>
    </div>
</div>