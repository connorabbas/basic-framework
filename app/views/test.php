<?php
$pageTitle = 'About';
require_once('./app/views/layout/header.php');
?>

<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1>Tester Example</h1>
        </div>
        <div class="col">
            <br>
            Test Data from controller:
            <?php
            echo '<pre style="max-height:600px; overflow-y: auto; border:1px solid #000;">';
            var_dump($testData);
            echo '</pre>';
            ?>
        </div>
    </div>
</div>

<?php
require_once('./app/views/layout/footer.php');
?>