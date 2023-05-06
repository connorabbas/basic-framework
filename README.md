# Basic PHP Framework
## About
A full-stack PHP framework that gives you the basics for starting a web project.

PHP 8 is required.

## Key Features
- MVC architecture
- Simple routing
- View templates using [Plates](https://platesphp.com/)
- Class auto loading and namespaces
- Dependency Injection Container
- PDO database wrapper class

---
# Documentation

## Installation
Download using [Composer](https://getcomposer.org/).
``` bash command-line
composer create-project connora/basic your-project-name
```
The project `.env` file should be created on install when using composer. An `.env.example` file is included as well.

### Serving Your Site Locally
If you want to serve your site locally for quick testing or development and you have PHP installed on your machine, use the "serve" command while working in the root of your project.

>__Note:__ this will only serve your site with php, not MySQL.

``` bash command-line
php basic serve
```

Alternatively, use Laragon, Docker, or XAMPP with a vhost configuration.

## Routing
### Registering
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
The route's callback will either be a closure (an anonymous function) where you can execute your endpoint logic directly, or a reference to a controller method using an array, where the first item is the fully qualified class you want to reference, and the second item is the method name that will be called.
``` php
// Basic route using a closure
$this->router->get('/home', function () {
    return 'Hello World';
});
// Alternatively, use a controller class and a method to store your logic in
$this->router->get('/home-alt', [HomeController::class, 'index']);
```
### Dynamic Parameters
You can set dynamic parameters in your routes uri by prefixing the slug with a hashtag `#`. The parameter's value will be available as an argument variable via your callback function.

Using within a controller:
```php
// Ex: yoursite.com/blog/post/123
// within /routes/main.php
$this->router->get('/blog/post/#id', [BlogPostController::class, 'show']);

// within /app/controllers/BlogPostController.php
public function show($id)
{
    // $id = '123'
    return View::render('pages.blog.post.single', [
        'blogPostData' => $this->blogPostModel->findById($id)
    ]);
}
```
Or using a basic closure:
``` php
$this->router->get('/example/#id', function ($id) {
    return $id
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
>__Note:__ you cannot nest a `batch()` method within itself.

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
### The Basics
Controllers are classes meant to handle the logic for an incoming HTTP request. Make sure to set your controller methods access modifiers to `public` so the router can execute them successfully. 

It is best practice to only handle the user input, and response logic within your controller methods. If you have more complicated business logic involved in your endpoint, it's recommended to abstract that into another class (typically a `Service` class).

Controllers should be named and organized based on the subject matter the request is pertaining too. Is is also recommended, but not required to include the word "Controller" in your class name.

There is an example controller class provided.

>__Note:__ Controller methods should only accept dynamic route parameter arguments, the included dependency injection container will only resolve classes established in the constructor.

### Requests / User Input
The framework provides a default `App\Core\Request` class that can be used to interact with user input within your controllers. The class will provide basic sanitation on the incoming request inputs, and methods for interacting with the data, rather than using PHP's super globals directly.

Available `App\Core\Request` methods:
```php
/**
 * Return an array of all sanitized inputs from the request
 */
$request->all();

/**
 * Return the sanitized $_GET value by it's key, optional default value
 */
$request->get(string $key, string $default = null);

/**
 * Return the sanitized $_POST value by it's key, optional default value
 */
$request->post(string $key, string $default = null);

/**
 * Return the sanitized $_REQUEST value by it's key, optional default value
 */
$request->input(string $key, string $default = null);
```

The `App\Core\Request` class can be instantiated within each controller method that needs it, or by using the the included `request()` helper function:
```php
<?php

namespace App\Controllers;

use App\Core\Request;

class ExampleController
{
    public function update()
    {
        // standard instantiation
        $request = new Request();
        $data = $request->input('data');

        // or with the helper
        $data = request()->input('data');
    }
}

```

## Dependency Injection Container
By default, you can type hint any class into a controller's `__construct()` method to have the container build out the class and it's dependencies for you. The container will use reflection and recursion to automatically instantiate and set all the needed dependencies your classes may have.

```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\UserModel;

class UserController
{
    private $userModel;

    // utilizing the containers automatic resolution
    // by type hinting the class we want
    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index()
    {
        $users = $this->userModel->getAll();

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
By default the container is used to easily instantiate a class you need, without you having to worry about instantiating it's dependencies. However, In certain situations your classes may not be resolvable by the container (requiring primitive constructor arguments, etc.), or perhaps you need a more custom implementation of the class returned from the container.

To manually set a class binding into the container, use the `set()` method, passing in a string reference `$id` (usually the fully qualified class name), and a closure as the `$callback` which should return the new class instance. Whenever your set class needs to be resolved by the container, it will use your registered callback to return it's implementation.

If you want your configured class to only be instantiated once, and used in all the subsequent references in the container, you can use the `setOnce()` method. There are a few core classes that are set once by default for use throughout the framework.

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
    $this->container->setOnce(Config::class, function ($container) {
        return new Config($_ENV);
    });
    $this->container->setOnce(Request::class, function ($container) {
        return new Request();
    });
    $this->container->setOnce(DB::class, function ($container) {
        $dbConfig = config('database', 'main');
        return new DB(
            $dbConfig['name'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['host']
        );
    });

    // reference the actual repository whenever the interface is referenced/injected
    $this->container->set(UserRepositoryInterface::class, function ($container) {
        return new UserRepository($container->get(UserModel::class));
    });

    return $this;
}
```

If you don't want to resolve certain classes in your controller's constructor, you can use the included `container()` helper function to access the `get()` method.

```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\UserModel;

class ExampleController
{
    public function index()
    {
        // get/resolve the UserModel class from the container without injecting into the constructor
        // dependencies automatically resolved, pretty neat
        $userModel = container(UserModel::class);
        $user = $userModel->getById($_REQUEST['id']);

        return View::render('pages.example', ['user' => $user]);
    }
}
```
>__Note:__ You are not required to use the included DI Container. Feel free to manually instantiate your classes and pass them around wherever needed using traditional dependency injection techniques.

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
The included `App\Core\DB` class acts as a wrapper around [PDO](https://www.php.net/manual/en/intro.pdo.php) and is intended to make connecting to a database and executing your queries easier. As mentioned earlier, the `App\Core\DB` class is set once into the container by default using the `database.main` configuration settings. You can change the default options, or setup multiple connections using `/config/database.php` and your application's `.env` file, more on that later.

### Establishing a Connection
There are multiple approaches to creating and using a database connection within a PHP web application.

As stated previously, the `App\Core\DB` class is what creates our database connection. There is a default container binding added within: `App\Core\App::containerSetup()`, it is commented out by default. With this approach, we can ensure that there is only one database class/connection created per request lifecycle, and it can be easily referenced in the application whenever it is needed.

To make things easier, your model classes should extend the included `App\Core\Model` abstract class to include the `$this->db` property.

``` php
<?php

namespace App\Models;

use App\Core\Model;

/**
 * $this->db available to use for your queries
 */
class ExampleModel extends Model
{
    private $table = 'example';

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->query($sql);
    }
}
```
```php
<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\ExampleModel;

class ExampleController
{
    private $exampleModel;

    public function __construct(ExampleModel $exampleModel)
    {
        $this->exampleModel = $exampleModel;
    }

    public function index()
    {
        return View::render('pages.example', [
            'data' => $this->exampleModel->getAll();
        ]);
    }
}
```

However, this may not be the approach you want/need in your application, so feel free to remove the binding or use more traditional dependency injection techniques for your database/models:
```php
<?php

namespace App\Controllers;

use App\Core\DB;
use App\Models\ExampleModel;

class ExampleController
{
    private $db;

    public function __construct(DB $db)
    {
        // Ex.1
        // Use the main DB connection that is configured in the container
        // via the type-hinted constructor argument
        $this->db = $db;

        // Ex.2
        // Create an alternative DB class binding in the container
        // Useful for models that need a different database connection
        $this->db = container('db_alt');

        // Ex.3
        // Create a connection on the fly
        $dbConfig = config('database', 'alt');
        $this->db = new DB(
            $dbConfig['name'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['host']
        );
    }

    public function index()
    {
        $exampleModel = new ExampleModel($this->db);

        return View::render('pages.example', [
            'data' => $exampleModel->getAll();
        ]);
    }
}
```

The `App\Core\DB` class offers the following methods:
``` php
/**
 * Get the established PDO connection
 */
$this->db->pdo();

/**
 * Prepares the query, binds the params, executes, and runs a fetchAll()
 */
$this->db->query(string $sql, array $params = []);

/**
 * Prepares the query, binds the params, executes, and runs a fetch()
 */
$this->db->single(string $sql, array $params = []);

/**
 * Prepares the query, binds the params, and executes the query
 */
$this->db->execute(string $sql, array $params = []);
```

### Example
```php
<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    private $table = 'users';

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        return $this->db->single($sql, [$id]);
    }

    public function getByEmail($email)
    {
        $sql = "SELECT * FROM $this->table WHERE email = ?";
        return $this->db->single($sql, [$email]);
    }

    public function create($name, $email, $username, $password)
    {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO $this->table(name, email, username, password) 
            VALUES(:name, :email, :username, :password)";

        return $this->db->execute($sql, [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $hashedPwd,
        ]);
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
        $properties['id'] = $userId;
        $sql = "UPDATE $this->table
            SET $setString
            WHERE id = :id";

        return $this->db->execute($sql, $properties);
    }

    public function delete(int $userId)
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        return $this->db->execute($sql, [$userId]);
    }
}
```
To follow MVC conventions, and for better organization in your application, it is highly recommended to only use the DB class and execute queries within your model classes.

## Helper Functions
Helper functions are meant to be accessed anywhere within the application. There are few included with the framework, feel free to add our own as well.

` /app/helpers.php `

## Environmental and Configuration Data
### The `.env` File
The project `.env` file should be created on install when using composer. If not, a provided example file is included to create a new one.

This file is for your settings variables that may differ from each environment your site is being used (local, staging, production), as well as storing private information you don't want committed to your source code repository, such as API keys, database access credentials, etc. It is added to the `.gitignore` by default.

```env
# Site Environment
ENV=local

# When the data has spaces
EXAMPLE_DATA="Example Data"
```

### Configuration Data
Configuration data can be thought of as your site "settings". This could include database connections, mail server info, site meta data, etc. This data will be stored in multi-dimensional arrays using `.php` files residing in the `/config` directory.

When you need to set configuration settings that are related to your private `.env` data, you can use the `$this->env` property to access it's values.

The `config(string $file, string $key)` helper function is used to access the desired data throughout the application. Using the config file reference as the first argument (without the file extension), and the value key location string as the second argument (using a period `.` as the nesting delimiter for accessing the nested values in the configuration array).

```php
// get the main database connection host name
$host = config('database', 'main.host');
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
