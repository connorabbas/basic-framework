# PHP Mini Framework
A full-stack PHP framework that gives you the basics for starting a web project in a lightweight "mini" package.

## Key Features
- Simple routing
- MVC architecture
- View templates using [Plates](https://platesphp.com/)
- Class auto loading
- PDO database class
- Bootstrap 5 included

## Installation
Download using composer.
``` bash command-line
composer create-project connora/php-mf <your-project-name>
```
The project .env file should be created on install when using composer. If not, a provided example file is included.

### Serving your site
If you want to serve your site locally for quick testing or development and you have php installed locally, use the "serve" command while working in the root of your project. Note: this will only serve your site with php, not MySQL.

``` bash command-line
php mini serve
```

Alternatively, use Docker or XAMPP with a vhost configuration.

## Routing
### Request Methods
The framework's router offers the following methods for the common http site requests:
``` php
$router->get($uri, $callback);
$router->post($uri, $callback);
$router->put($uri, $callback);
$router->patch($uri, $callback);
$router->delete($uri, $callback);
```
### Callback functions
The callback will either be a self contained function, where you can execute your routes logic, or it will be an array where the first item is the class you want to reference (usually a controller), and the second item is the method name.
``` php
// Basic route using a closure
$router->get('/home', function() {
    echo 'Hello World';
});
// Alternatively, use a controller class and a method to store your logic in
$router->get('/home-alt', [HomeController::class, 'index']);
```
### Parameters
You can set dynamic values in your routes slug that will be available in the $_REQUEST super global. The index will be the same name you used for your variable in the route uri.
``` php
// Ex: yoursite.com/blog/1
$router->get('/blog/$id', function() {
    // Reference the dynamic variable
    $id = $_REQUEST['id'];
});
```
### Organization
As your application grows, you will probably want to better organize your routes instead of having them all in one file. Feel free to organize any file/folder structure you wish! By default, you can define routes within any .php file that resides inside of the /routes directory.

## Controllers
Controllers are where you should store your routes logic for handling the incoming HTTP request. There is an example controller class provided.

Note: In the current state, controller methods should NOT accept parameters, there is no dependency injection container being used...

Creating a controller is easy with the built in cli tools included with the framework. Just open a command line interface at the root directory of your project and enter the command:
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
When calling your view within a controller, you will use the static show() method from the View class. The method accepts the view file path (no file extension) and an array of data variables you want accessible in the view.
``` php
public function index()
{
    $foo = 'bar';

    View::show('pages/example', [
        'foo' => $foo,
    ]);
}
```

## Models and Database
Models are meant to interact with your database. The included DB class is used to connect and execute your DB queries. The DB class uses PDO, and is setup to accept a dsn of stored credentials to connect using MySQL as the default (This could be changed to another PDO supported driver like ODBC).

It's recommended that the database connection only be established once (usually in the controller) and passed throughout the application using dependency injection wherever it is needed.

You can create a model using the cli tools just like you can with controllers:
``` bash command-line
php mini new:model YourModelName
```
### Example
``` php
<?php

namespace App\Models;

class Example extends Model
{
    public function exampleQuery($data)
    {
        $sql = "SELECT * FROM schema.table Where column = :data";

        $this->db->query($sql)
            ->bind(':data', $data);

        return $this->db->resultSet();
    }
}

```
``` php
<?php

use App\Core\DB;
use App\Core\View;
use App\Models\Example;

class TesterController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        $exampleModel = new Example($this->db);
        $exampleData = $exampleModel->exampleQuery('test_data_123');

        View::show('pages/example', [
            'exampleData' => $exampleData,
        ]);
    }
}
```

## Helper functions
Helper functions are meant to be accessed anywhere within the application. There are few included with the framework, and feel free to add our own as well!

/app/core/Helpers.php

## Environmental and Configuration Data
### .env
The framework will provide an example .env file for you upon installation, create a new .env file with the same content to start.

This file is to be used for your private data such as API keys, database access credentials, etc. It is added to the .gitignore by default.

Data from the .env file is accessible in the $_ENV super global.

### config()
It's best practice that the data from your .env should only be accessed in the config class.

/app/data/Config.php

The config class allows you to set your options for things like database or mail connections, site settings, etc.

The config() helper function is used to access the desired data. Use a period as the delimiter for accessing the nested value in the configuration array.

```php
// get the database host name
$host = config('database.main.host');
```

## CLI Tools

Create a controller:
``` bash command-line
php mini new:controller YourControllerName
```

Create a model:
``` bash command-line
php mini new:model YourModelName
```

Serve your site locally:
``` bash command-line
php mini serve
```