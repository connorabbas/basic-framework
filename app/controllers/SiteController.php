<?php
class SiteController
{
    public function __construct()
    {
    }

    public function invoke()
    {
        // Valid routes for site
        $routes = new Route();
        require_once('../app/routes/public.php');
    }
}
