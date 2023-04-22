# PHP Basic Framework
## About
A full-stack PHP framework that gives you the basics for starting a web project.

PHP 8 is required.

## Key Features
- MVC architecture
- Simple routing
- View templates using [Plates](https://platesphp.com/)
- Class auto loading
- Dependency Injection Container
- PDO database wrapper class
- [Bootstrap](https://getbootstrap.com/docs/5.2/getting-started/introduction/) 5.2 included

## Example Project
For more in-depth code examples, and to see a fully working application using the framework I have made an example project to reference.

[PHP User Auth](https://github.com/connorabbas/php-user-auth)

---
# Documentation

## Installation
Download using [Composer](https://getcomposer.org/).
``` bash command-line
composer create-project connora/basic your-project-name
```
The project `.env` file should be created on install when using composer. An `.env.example` file is included as well.

### Serving Your Site Locally
If you want to serve your site locally for quick testing or development and you have PHP installed on your machine, use the "serve" command while working in the root of your project. Note: this will only serve your site with php, not MySQL.

``` bash command-line
php basic serve
```

Alternatively, use Laragon, Docker, or XAMPP with a vhost configuration.

## Routing
### Registering Your Routes
By default, you will register your routes within `/routes/main.php`.

You will have access to the `$this->router` property which supplies an instance of the `App\Core\Router` class.

The `App\Core\Router` class offers the following register methods that align with the common HTTP verbs:
``` php
$this->router->get($uri, $callback);
$this->router->post($uri, $callback);
$this->router->put($uri, $callback);
$this->router->patch($uri, $callback);
$this->router->delete($uri, $callback);
```
These methods will register your routes as valid endpoints within your application. If you try to access a url/route that isn't registered, a `404` page will be displayed.

### Callback Functions
The route's callback will either be a closure where you can execute your endpoint logic directly, or an array where the first item is the fully qualified class you want to reference (a controller), and the second item is the method name that will be called.
``` php
// Basic route using a closure
$this->router->get('/home', function () {
    return 'Hello World';
});
// Alternatively, use a controller class and a method to store your logic in
$this->router->get('/home-alt', [HomeController::class, 'index']);
```
### Parameters
You can set dynamic parameters in your routes uri by prefixing with a hashtag `#`. The parameter's value will be available in the ` $_REQUEST ` superglobal. The index will be the parameter name you used, without the hashtag `#`.
``` php
// Ex: yoursite.com/blog/post/123
$this->router->get('/blog/post/#id', function () {
    // Reference the dynamic variable
    $id = $_REQUEST['id'];
});
```

### Batch Registering
You can register routes in batches that share similar qualities, such as a controller, a uri prefix, or both. The intent here is to reduce boilerplate code, and provide better organization.

When batching routes, it's required to chain on the `batch()` method at the end, which accepts a closure argument containing the routes you want the batch properties to apply to.

Batch related methods available to you:

```php
$this->router->controller(string $className);
$this->router->prefixUri(string $uri);
$this->router->batch(callable $closure);
```
When batching routes with the `prefixUri()` method, the routes within the closure will all be prepended by your defined uri prefix.

For example:
```php
$this->router
    ->prefixUri('/users')
    ->batch(function () {
        $this->router
            // /users GET (show all users)
            ->get('/', [UserController::class, 'index'])
            // /users/create GET (form to create a user)
            ->get('/create', [UserController::class, 'create'])
            // /users POST (endpoint to store a new user)
            ->post('/', [UserController::class, 'store'])
            // /users/123 GET (show a single user)
            ->get('/#id', [UserController::class, 'show'])
            // /users/123/edit GET (form to edit user properties)
            ->get('/#id/edit', [UserController::class, 'edit'])
            // /users/123 PATCH (endpoint to update user properties)
            ->patch('/#id', [UserController::class, 'update'])
            // /users/123 DELETE (endpoint to remove a user record)
            ->delete('/#id', [UserController::class, 'destroy']);
    });
```
When batching routes with the `controller()` method, take note that:
- The register method's second argument inside the closure will be a string referencing the endpoint method, instead of the default array syntax
- The `$this->router->view()` method is unavailable within the batch closure, since we are required to reference a controller method

For example:
```php
$this->router
    ->controller(UserController::class)
    ->batch(function () {
        $this->router
            ->get('/users', 'index')
            ->get('/users/create', 'create')
            ->post('/users', 'store')
            ->get('/users/#id', 'show')
            ->get('/users/#id/edit', 'edit')
            ->patch('/users/#id', 'update')
            ->delete('/users/#id', 'destroy');
    });
```
Or use both:
```php
$this->router
    ->controller(UserController::class)
    ->prefixUri('/users')
    ->batch(function () {
        $this->router
            ->get('/', 'index')
            ->get('/create', 'create')
            ->post('/', 'store')
            ->get('/#id', 'show')
            ->get('/#id/edit', 'edit')
            ->patch('/#id', 'update')
            ->delete('/#id', 'destroy');
    });
```
NOTE: you cannot nest a `batch()` method within itself.

### Form Requests
The standard html ` <form> ` tag only accepts ` GET ` and ` POST ` as valid request methods. We can overcome this by using the ` method_spoof(string $method) ` helper function. This requires our form to use the `POST` method request and to specify the "spoofed" method inside the form using `PUT`, `PATCH`, or `DELETE`. 

For example:
```php
$this->router->patch('/update-example', [ExampleClass::class, 'updateMethod']);
```
```html
<!-- Example form request to update data -->
<form action="/update-example" method="POST">
    <?= csrf() ?>
    <?= method_spoof('PATCH') ?>
    <label class="form-label">Field to update</label>
    <input name="exampleField" value="<?= $originalValue ?>" required>
    <button type="submit" name="updateSubmit">Update</button>
</form>
```
It's also recommended to use the included `csrf()` and `csrf_valid()` helper functions to ensure your requests are safe from any potential [Cross Site Request Forgery](https://owasp.org/www-community/attacks/csrf).

### Organization
As your application grows, you will probably want to better organize your routes instead of having them all in the `/routes/main.php` file. By default, you have access to the `$this->router` property within any PHP file that resides inside of the `/routes` directory. So feel free to organize any file/folder structure you wish!

## Controllers
Controllers are classes meant to handle the logic for an incoming HTTP request. Make sure to set your controller methods access modifiers to `public` so the router can execute them successfully. 

Controllers should be named and organized based on the subject matter the request is pertaining too. Is is also recommended, but not required to include the word "Controller" in your class name.

There is an example controller class provided.

Note: Controller methods should not accept any arguments, the included dependency injection container will only resolve classes established in the constructor. User input can be obtained from within your controller methods using PHP's superglobals.

## Dependency Injection Container
By default, you can type hint any class in a controller's `__construct()` method to have the container handle it's dependencies for you. The container will use reflection and recursion to automatically instantiate and set all the needed dependencies your classes may have.

```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class UserController
{
    protected $userData;

    // utilizing the containers automatic resolution
    // by type hinting the class we want
    public function __construct(User $userData)
    {
        $this->userData = $userData;
    }

    public function index()
    {
        $users = $this->userData->getAll();

        return View::render('pages.users.list', ['users' => $users]);
    }
}
```
`App\Core\Container` class methods available to you:
```php
$this->container->get(string $id);
$this->container->set(string $id, callable $callback);
$this->container->setOnce(string $id, callable $callback);
```
To create a class binding, use the `set()` method, passing in the class or interface you want registered, and a closure that should return the new class instance.

If you want your bound class to only be instantiated once, and used in all the subsequent references in the container, use the `setOnce()` method. For example, the `App\Core\DB` class is set once by default.

To return/resolve a class instance from the container, use the `get()` method. This is what the container uses internally when using automatic resolution with your injected classes.

When setting up your manual bindings, you can access the container within the closure using the `$container` argument. This allows you to use `$container->get()` inside the closure to resolve any dependencies for the class you are returning.

If you need to manually set up a class or interface and it's binding, you may do so in the `App\Core\App` class `containerSetup()` method:

```php
/**
 * Establish any container class bindings for the application
 */
public function containerSetup(): self
{
    // included by default
    $this->container->setOnce(DB::class, function ($container) {
        return new DB();
    });

    // reference the actual repository whenever the interface is referenced/injected
    $this->container->set(UserRepositoryInterface::class, function ($container) {
        return new UserRepository($container->get(User::class));
    });

    return $this;
}
```

If you don't want to resolve certain classes in your controller's constructor, you can use the included `container()` helper function to access the `get()` method.

```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class ExampleController
{
    public function index()
    {
        // get/resolve the User class from the container without injecting into the constructor
        // dependencies automatically resolved, pretty neat
        $userData = container(User::class);
        $user = $userData->getById($_REQUEST['id']);

        return View::render('pages.example', ['user' => $user]);
    }
}
```
Note: You are not required to use the included DI Container. Feel free to manually instantiate your classes and pass them around wherever needed using traditional dependency injection techniques.

## Views
By default, the framework uses [Plates](https://platesphp.com/) for it's view template system. The `App\Core\View` class is used as a basic wrapper.

### Static Page?
The `App\Core\Router` class also has a method for calling your view directly, so you don't have to bother with closures or controllers for your more simple pages:
``` php
$this->router->view('/', 'pages.welcome');
```
### In Your Controller Method
When referencing your view within a controller, use the static ` App\Core\View::render() ` method to return the template content. The method accepts the view file reference (using a period `.` as the nesting delimiter, no file extension) and an array of data variables you want accessible in the view.
``` php
public function index()
{
    $foo = 'bar';

    return View::render('pages.example', ['foo' => $foo]);
}
```

## Models and Database
Models are classes that are meant to interact with your database.

### The DB Class
The included `App\Core\DB` class acts as a wrapper around [PDO](https://www.php.net/manual/en/intro.pdo.php) and is intended to make connecting to a database and executing your queries easier. The class is setup to accept a connection configuration array of credentials and options. You can change the default options, or setup multiple connections using the `App\Data\Config` class and your application's `.env` file, more on that later.

To make things easier, your model classes should extend the included `App\Core\Model` abstract class to include the `$this->db` property, and have it's database connection created automatically for you.

``` php
<?php

namespace App\Models;

use App\Core\Model;

class Example extends Model
{
    // $this->db available to use for your queries
}
```

If you don't extend the base model class, or you override the constructor, you will need to establish your own database property/connection.
```php
<?php

namespace App\Models;

use App\Core\DB;

class Example
{
    protected $db;

    // using an alternative database connection
    public function __construct()
    {
        $this->db = new DB(config('database.alt'));
    }
}

```

The `App\Core\DB` class offers the following methods:
``` php
// return the established PDO connection
$this->db->getConnection();
// create a prepared statement on the established connection
$this->db->query(string $sql);
// bind a value to the prepared statement query
$this->db->bind($param, $value, $type = null);
// execute the prepared statement, used for INSERT, UPDATE, DELETE, etc.
$this->db->execute();
// return an array of results, fetching objects by default
$this->db->resultSet($fetchMode = null);
// return a single result
$this->db->single($fetchMode = null);
// return the row count from the prepared statement
$this->db->rowCount();
// get the last inserted ID from the connection
$this->db->lastInsertId();
```

### Example
```php
<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    private $table = 'users';

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";

        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table 
            WHERE id = :id";

        $this->db
            ->query($sql)
            ->bind(':id', $id);

        return $this->db->single();
    }

    public function getByUsername($username, $email)
    {
        $sql = "SELECT * FROM $this->table 
            WHERE username = :username OR email = :email";

        $this->db
            ->query($sql)
            ->bind(':username', $username)
            ->bind(':email', $email);

        return $this->db->single();
    }

    public function create($name, $email, $username, $password)
    {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO $this->table(name, email, username, password) 
            VALUES(:name, :email, :username, :password)";

        $this->db
            ->query($sql)
            ->bind(':name', $name)
            ->bind(':email', $email)
            ->bind(':username', $username)
            ->bind(':password', $hashedPwd);

        return $this->db->execute();
    }

    public function update(int $userId, array $properties)
    {
        $setString = '';
        foreach ($properties as $property => $value) {
            $setString .= $property . ' = ' . ':' . $property;
            if ($property != array_key_last($properties)) {
                $setString .= ', ';
            } else {
                $setString .= ' ';
            }
        }

        $sql = "UPDATE $this->table
            SET $setString
            WHERE id = :id";

        $this->db->query($sql);
        foreach ($properties as $property => $value) {
            $this->db->bind(':' . $property, $value);
        }
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    public function delete(int $userId)
    {
        $sql = "DELETE FROM $this->table
            WHERE id = :id";

        $this->db
            ->query($sql)
            ->bind(':id', $userId);

        return $this->db->execute();
    }
}
```
To follow MVC conventions, and for better organization in your application, it is highly recommended to only use the DB class and execute queries within your model classes.

### Establishing a Connection
There are multiple approaches to creating and using a database connection within a PHP web application.

As stated previously, the `App\Core\DB` class is what creates our database connection, and it is registered into the container by default using the `setOnce()` method within `/app/core/App.php`. With this approach, we can ensure that there is only one database class/connection created per request lifecycle.

This approach is beneficial if you intend on using the container to resolve needed classes within your controllers. However, if this is not the approach you want/need in your application, feel free to remove the binding.

## Helper Functions
Helper functions are meant to be accessed anywhere within the application. There are few included with the framework, feel free to add our own as well.

` /app/helpers.php `

## Environmental and Configuration Data
### .env
The project `.env` file should be created on install when using composer. If not, a provided example file is included.

This file is for your settings variables that may differ from each environment your site is being used (local, staging, production). It is also used to store private data such as API keys, database access credentials, etc. It is added to the `.gitignore` by default.

Data from the `.env` file is accessible in the ` $_ENV ` superglobal.

### config()
It's best practice that the data from your .env should only be accessed in the config class. `App\Data\Config`

The config class allows you to set your options for things like database or mail connections, site settings, etc. in an organized array structure.

The ` config() ` helper function is used to access the desired data. Using a period `.` as the nesting delimiter for accessing the nested values in the configuration array.

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
