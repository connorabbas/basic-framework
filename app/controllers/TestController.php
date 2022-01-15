<?php
class TestController extends SiteController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $testData = array('test', 'test2', 'test3');
        return App::view('test', [
            'pageTitle' => 'Tester',
            'testData' => $testData,
            'connection' => $this->db,
        ]);
    }

    public function postTest()
    {
        var_dump($_POST);
    }
}
