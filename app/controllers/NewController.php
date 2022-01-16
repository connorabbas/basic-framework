clear<?php
class NewController extends SiteController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
    }
}