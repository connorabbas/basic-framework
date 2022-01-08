<?php
class TestController extends Controller
{
    public function index($db)
    {
        $testData = array('test', 'test2', 'test3');
        array_push($testData, $this->test());

        return App::view('test', [
            'testData' => $testData,
            'db' => $db,
        ]);
    }

    public function test()
    {
        return 'testdata';
    }

    public function postTest()
    {
        var_dump($_POST);
    }
}
