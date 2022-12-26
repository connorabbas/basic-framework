# PHP Basic Framework
A full-stack PHP framework that gives you the basics for starting a web project in a lightweight package.

## Key Features
- Simple routing
- MVC architecture
- View templates using [Plates](https://platesphp.com/)
- Class auto loading
- PDO database wrapper class
- [Bootstrap](https://getbootstrap.com/docs/5.2/getting-started/introduction/) 5.2 included

## Installation
Download using [Composer](https://getcomposer.org/).
``` bash command-line
composer create-project connora/basic your-project-name
```
The project `.env` file should be created on install when using composer. If not, a provided example file is included.

### Serving your site
If you want to serve your site locally for quick testing or development and you have php installed on your machine, use the "serve" command while working in the root of your project. Note: this will only serve your site with php, not MySQL.

``` bash command-line
php basic serve
```

Alternatively, use Docker or XAMPP with a vhost configuration.

## Routing
### Request Methods
The framework's router offers the following methods for the common http site requests:
``` php
$this->router->get($uri, $callback);
$this->router->post($uri, $callback);
$this->router->put($uri, $callback);
$this->router->patch($uri, $callback);
$this->router->delete($uri, $callback);
```

### Callback functions
The callback will either be a self contained function, where you can execute your routes logic, or it will be an array where the first item is the class you want to reference (a controller), and the second item is the method name.
``` php
// Basic route using a closure
$this->router->get(
    '/home',
    function () {
        return 'Hello World';
    }
);
// Alternatively, use a controller class and a method to store your logic in
$this->router->get('/home-alt', [HomeController::class, 'index']);
```
### Parameters
You can set wildcard values in your routes slug that will be available in the ` $_REQUEST ` super global. The index will be the same name you used for your variable in the route uri.
``` php
// Ex: yoursite.com/blog/1
$this->router->get(
    '/blog/$id',
    function () {
        // Reference the dynamic variable
        $id = $_REQUEST['id'];
    }
);
```

### Form requests
The standard html ` <form> ` tag only accepts ` GET ` and ` POST ` as valid request methods. We can overcome this by using the ` methodSpoof(string $method) ` helper function. This requires our form to use the `POST` method request and to specify the "spoofed" method inside the form using `PUT`, `PATCH`, or `DELETE`. 

For example:
```php
$this->router->patch('/update-example', [ExampleClass::class, 'updateMethod']);
```
```html
<!-- Example form request to update data -->
<form action="/update-example" method="POST">
    <?= csrf() ?>
    <?= methodSpoof('PATCH') ?>
    <label class="form-label">Field to update</label>
    <input name="exampleField" value="<?= $originalValue ?>" required>
    <button type="submit" name="updateSubmit">Update</button>
</form>
```
It's also recommended to use the included `csrf()` and `csrfValid()` helper functions to ensure your requests are safe from any potential [Cross Site Request Forgery](https://owasp.org/www-community/attacks/csrf).

### Organization
As your application grows, you will probably want to better organize your routes instead of having them all in one file. Feel free to organize any file/folder structure you wish! By default, you can define routes within any .php file that resides inside of the /routes directory.

## Controllers
Controllers are where you should store your routes logic for handling the incoming HTTP request. There is an example controller class provided.

Note: In the current state, controller methods should NOT accept any parameters, the included dependency injection container will only resolve classes established in the class constructor.

Creating a controller is easy with the built in cli tools included with the framework. Just open a command line interface at the root directory of your project and enter the command:
``` bash command-line
php basic new:controller YourControllerName
```

## Dependency Injection Container
By default you can type hint any class in a controller `__construct()` method to have the container handle it's dependencies for you.

You can also instantiate the `App\Core\Container` class yourself and use the `get()` and `set()` methods to easily manage your class dependencies. The class will use reflection and recursion to automatically instantiate and set all the needed dependencies your classes may have.

## Views
By default, the framework uses [Plates](https://platesphp.com/) for it's view template system. The `App\Core\View` class is used as a basic wrapper.

### Static Page?
The router class also has a method for calling your view directly, so you don't have to bother with closures or controllers for your more simple pages:
``` php
$this->router->view('/', 'pages.welcome');
```
### In Your Controller Method
When calling your view within a controller, you will use the static ` View::render() ` method to return the template content. The method accepts the view file path (using "." as the nesting delimiter, no file extension) and an array of data variables you want accessible in the view.
``` php
public function index()
{
    $foo = 'bar';

    return View::render(
        'pages.example',
        ['foo' => $foo]
    );
}
```

## Models and Database
Models are meant to interact with your database. The included `App\Core\DB` class is used to connect to a data source and execute your queries. The DB class uses PDO, and is setup to accept a connection configuration of stored dsn credentials and PDO options to connect using MySQL as the default (This could be changed to another PDO supported driver like ODBC).

There is a `config('database.main')` connection included and used by default.

It's recommended that the database connection only be established once (usually in the controller) and passed throughout the application using dependency injection wherever it is needed.

You can create a model using the cli tools just like you can with controllers:
``` bash command-line
php basic new:model YourModelName
```
### Example
``` php
<?php

namespace App\Models;

use App\Core\Model;

class Example extends Model
{
    public function getData($data)
    {
        $sql = "SELECT * FROM schema.table Where column = :data";

        $this->db->query($sql)->bind(':data', $data);

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
        $exampleData = $exampleModel->getData('test_data_123');

        return View::render(
            'pages.example',
            ['exampleData' => $exampleData]
        );
    }
}
```

## Helper functions
Helper functions are meant to be accessed anywhere within the application. There are few included with the framework, feel free to add our own as well.

` /app/utilities/helpers.php `

## Environmental and Configuration Data
### .env
The project `.env` file should be created on install when using composer. If not, a provided example file is included.

This file is for your custom configuration settings that may differ from each environment your site is being used (local, staging, production). It is also used to store private data such as API keys, database access credentials, etc. It is added to the `.gitignore` by default.

Data from the `.env` file is accessible in the ` $_ENV ` super global.

### config()
It's best practice that the data from your .env should only be accessed in the config class.

` /app/data/Config.php `

The config class allows you to set your options for things like database or mail connections, site settings, etc.

The ` config() ` helper function is used to access the desired data. Using a period (".") as the nesting delimiter for accessing the nested values in the configuration array.

```php
// get the database host name
$host = config('database.main.host');
```

## CLI Tools

Create a controller:
``` bash command-line
php basic new:controller YourControllerName
```

Create a model:
``` bash command-line
php basic new:model YourModelName
```

Serve your site locally:
``` bash command-line
php basic serve
```