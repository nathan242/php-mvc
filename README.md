# PHP-MVC

PHP MVC framework with the following features:

* Simple routing that can pass route values to controller methods.
* DI Container like concept that can resolve type hinted class dependencies and can use configured factory classes to build objects.
* Request, response and session objects that are injected into all controllers.
* Simple view system that renders views from html/php files and can pass in variables.
* Supports CLI commands using command controller classes.
* Simple model like class to represent database records.
* Initial concept of SQL abstraction (very basic early stages) with SQL query builder.
* Database classes to support MySQL and SQLite.

### Running the sample application

There is a sample application in the **Application** folder. Configure your web server to pass all web requests to **Application/www/index.php**. If you are using Apache the included **.htaccess** file can be used. To run via the PHP development web server, change to the **Application/www** folder and run:

```
php -S 127.0.0.1:8080 cli-server-index.php
```

This will pass all requests via the **cli-server-index.php** file which will allow static content from **www** while running on the PHP built in web server.

To run CLI commands, execute the **index.php** file using the PHP CLI:

```
php index.php

PHP-MVC Test Application [v0.0.1]

Available commands:
repl                      - Start interactive shell
create-users-table        - Create users table
create-test-table         - Create test table
dump-config               - Dump configuration of specified type
show-test-records         - Show records in the test table
no_method                 - Test missing method
no_controller             - Test missing controller
```

### Creating a new application

To create a new application, copy the **Framework/template** folder to the root directory (where **Application** is) and rename it accordingly. Add the application config to the **config** folder. Environment specific config should be put into **config.php** (see **config.php.sample**) and this will be available within the config files in the **$local** variable.

