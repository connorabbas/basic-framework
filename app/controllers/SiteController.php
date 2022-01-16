<?php
class SiteController
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function invoke()
    {
        // Valid routes for site
        $routes = new Route($this->db);
        require_once('./app/routes.php');
    }
}
