<?php
$pageTitle = 'Tester';
require_once('./app/views/layout/header.php');
?>

<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1>Tester Example</h1>
            <form action="<?=BASE_DIR?>tester" method="post">
                <input type="hidden" name="test1" value="value data">
                <button class="btn btn-dark" action="submit">submit</button>
            </form>
        </div>
        <div class="col">
            <br>
            Test Data from controller:
            <?php
            echo '<pre style="max-height:600px; overflow-y: auto; border:1px solid #000;">';
            var_dump($testData);
            var_dump($db);
            echo '</pre>';
            ?>
        </div>
    </div>
</div>

<?php
require_once('./app/views/layout/footer.php');
?>