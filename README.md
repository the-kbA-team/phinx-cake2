# Phinx for CakePHP2

[Phinx] is the default tool for database migrations in CakePHP3 and CakePHP4. Phinx can be used as a standalone tool,
so it is possible to use it with CakePHP2. This project makes usage of Phinx with CakePHP2 less complicated.

## Prerequisites

* PHP >= 7.0
* CakePHP2 installed via composer
* Mysql (no other DB supported a the moment)
* Migrations in folder Config/Migrations
* Seeds in folder Config/Seeds

## What does it do?

This package provides a helper script and a default Phinx config file for usage with you CakePHP2 project.

By using the helper script it is not necessary to create a Phinx configuration for your project.

## Installation

 ```bash
composer require kba-team/phinx-cake2
```

## Usage

Usage: `phinx-cake2.sh [command] [arguments] [options]`

## Example

```bash
./vendor/bin/phinx-cake2.sh migrate
```

If you want to migrate a plugin's migration.

```bash
PLUGIN=<plugin_name> ./vendor/bin/phinx-cake2.sh migrate
```

In case the plugin uses its own database, you need to set the following in a file called `phinx.php` in the plugin's Config directory.

```php
Configure::write('phinx.datasource', "<datasource name>");
```


[Phinx]:https://phinx.org/
