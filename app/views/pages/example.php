
<?php
$this->layout('template::main', [
    'pageTitle' => 'Example',
    'pageDesc' => "An Example Page...",
]);
?>
<div class="d-flex align-items-center justify-content-center" style="height: 90vh">
    <div class="text-center">
        <h3 class="mb-3">Example Page</h3>
        <p>Foo: <?= $foo ?></p>
    </div>
</div>
