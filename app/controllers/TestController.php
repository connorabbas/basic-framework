<?php
class TestController extends Controller
{
    public function index($db)
    {
        $testData = array('test', 'test2', 'test3');

        return App::view('test', [
            'testData' => $testData,
        ]);
    }
}
