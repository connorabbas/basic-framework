<?php
$pageTitle = 'Tester';
require_once('./app/views/layout/header.php');
?>

<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1 class="mb-3" >Tester Example</h1>
            <form action="<?=App::route('/tester')?>" method="post">
                <input type="hidden" name="test1" value="value data">
                <p class="mb-3">Submit form to post to same route</p>
                <button class="btn btn-dark" action="submit">submit</button>
            </form>
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