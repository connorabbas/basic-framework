<div class="container">
    <div class="row my-5">
        <div class="col">
            <h1 class="mb-3" >Tester Example</h1>
            <form action="<?= App::path('/tester') ?>" method="post">
                <input type="hidden" name="test1" value="value data">
                <p class="mb-3">Submit form to post to same route</p>
                <button class="btn btn-dark" action="submit">submit</button>
            </form>
            <br>
            Test Data from controller:
            <?php
            echo '<pre class="dump">';
            var_dump($testData);
            var_dump($connection);
            echo '</pre>';
            ?>
        </div>
    </div>
</div>