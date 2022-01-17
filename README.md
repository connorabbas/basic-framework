# PHP Mini Framework

Key Features:
- Routing for GET, POST, PATCH, PUT & DELETE HTTP requests
- MVC architecture
- Basic CLI actions for creating Models & Controllers
- Class auto loading
- PDO database class
- Bootstrap 5, jQuery, and React included

Starter content for env.php file (create in /app directory):
```
<?php
// Enviroment specific variables

// Database
putenv("DB_HOST=127.0.0.1");
putenv("DB_USERNAME=root");
putenv("DB_PASSWORD=");
putenv("DB_NAME=");

// Site Base Directory
putenv("BASE_DIR=/php-mf/");
?>
```

Create a controller via CLI:
``` bash command-line
php mini create:controller YourControllerName
```

Create a model via CLI:
``` bash command-line
php mini create:model YourModelName
```