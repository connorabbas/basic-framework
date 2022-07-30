# PHP Mini Framework
PHP Mini is a full-stack PHP web framework that gives you the basics for starting a web project in a lightweight "mini" package.

## Key Features
- Routing for GET, POST, PATCH, PUT & DELETE HTTP requests
- MVC architecture
- View templating using Plates PHP
- Basic CLI commands for creating Models & Controllers
- Class auto loading
- PDO database class
- Bootstrap 5 included

## Installation
Download using composer, and setup your .env file.
``` bash command-line
composer create-project connora/php-mf <your-project-name>
```

## Routing
### Request Methods
The framework's router offers the following methods for the common http site requests:
``` php
$routes->get($uri, $callback);
$routes->post($uri, $callback);
$routes->put($uri, $callback);
$routes->patch($uri, $callback);
$routes->delete($uri, $callback);
```
### Callback functions
The callback will either be a self contained function, where you can execute your routes logic, or it will be an array where the first item is the class you want to reference (usually a controller), and the second item is the method name.
``` php
// Basic route using a closure
$routes->get('/home', function() {
    return 'Hello World';
});
// Alternatively, use a controller class and a method to store your logic in
$routes->get('/home-alt', [HomeController::class, 'index']);
```
### Parameters
You can set dynamic values in your routes slug that will be available in the $_REQUEST super global. Warning: This data is NOT sanitized, just like any GET url parameter.
``` php
// Ex: yoursite.com/blog/1
$routes->get('/blog/$id', function() {
    // Reference the dynamic variable
    $id = $_REQUEST['id'];
});
```
### Organization
As your application grows, you will probably want to better organize your routes instead of having them all in one file. Feel free to organize any file/folder structure you wish! By default, you can define routes within any .php file that resides inside of the /routes directory.

## Controllers
Controllers are where you should store your routes logic for handling the incoming HTTP request. There is an example controller class provided.

Note: In the current state, controller methods should NOT accept parameters, there is no DI container being used...

Creating a controller is easy with the built in cli tools included with mini. Just open a command line interface, make sure you are cd'd into the root directory of your project and enter the command:
``` bash command-line
php mini new:controller YourControllerName
```

## Views
By default, PHP Mini Framework uses [Plates](https://platesphp.com/) for it's view template system.
### Static Page?
The router class also has a method for calling your view directly, so you don't have to bother with closures or controllers for your more simple pages:
``` php
$router->view('/', 'pages/welcome');
```
### In Your Controller Method
When calling your view within a controller, you will use the globally available view helper method. The method accepts the view file path (starting at app/views, and no file extension) and an array of data variables you want accessible in said view.
``` php
public function index()
{
    $foo = 'bar';

    return view('pages/example', [
        'foo' => $foo,
    ]);
}
```

## Models and Database
Models are meant to interact with your database. The included DB class is used to connect and execute your DB queries. The DB class uses PDO, and is setup to accept a dsn of stored credentials to connect using MySQL as the default (This could be changed to another PDO supported driver like ODBC).

It's recommended that the database connection should be established outside the model class, and passed into the constructor as a dependency.

You can create a model using the cli tools just like you can with controllers:
``` bash command-line
php mini new:model YourModelName
```
### Example Model Usage
``` php
<?php

namespace App\Models;

class Example
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function exampleQuery($data)
    {
        $sql = "SELECT * FROM schema.table Where column = :data";
        $this->db->query($sql);
        $this->db->bind(':data', $data);
        $results = $this->db->resultSet();

        return $results;
    }
}

```
``` php
<?php

use App\Core\DB;
use App\Models\Example;

class TesterController extends SiteController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        $exampleModel = new Example($this->db);
        $exampleData = $exampleModel->exampleQuery('test_data');

        return view('pages/example', [
            'exampleData' => $exampleData,
        ]);
    }
}
```

## CLI Tools

Create a controller via CLI:
``` bash command-line
php mini new:controller YourControllerName
```

Create a model via CLI:
``` bash command-line
php mini new:model YourModelName
```