# PHP Mini Framework

## Documentation
View full documentation: [https://php-mf-docs.connor-abbas.com/](https://php-mf-docs.connor-abbas.com/)

## What is PHP Mini Framework?
PHP Mini is a full-stack PHP web framework that gives you the essentials for starting a web project in a lightweight "mini" package.

## Key Features
- Routing for GET, POST, PATCH, PUT & DELETE HTTP requests
- MVC architecture
- View templating using Plates PHP
- Basic CLI commands for creating Models & Controllers
- Class auto loading
- PDO database class
- Bootstrap 5 included

## Installation
- Clone repo
- Run composer update to add [Plates](https://github.com/thephpleague/plates) templating
- Change env to env.php located in /app/vars/

## CLI Tools

Create a controller via CLI:
``` bash command-line
php mini create:controller YourControllerName
```

Create a model via CLI:
``` bash command-line
php mini create:model YourModelName
```