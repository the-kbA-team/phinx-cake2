<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$vendorDir = dirname(dirname(dirname(dirname(__FILE__))));

if (!defined('ROOT')) {
    define('ROOT', dirname($vendorDir));
}

if (!defined('APP_DIR')) {
    define('APP_DIR', '');
}

if (!defined('WEBROOT_DIR')) {
    define('WEBROOT_DIR', basename(dirname(ROOT)));
}
if (!defined('WWW_ROOT')) {
    define('WWW_ROOT', dirname(ROOT) . DS);
}

if (!defined('CONFIG')) {
    define('CONFIG', ROOT . DS . APP_DIR . DS . 'Config' . DS);
}

$pathCakeInVendorDir = $vendorDir . DS . 'cakephp' . DS . 'cakephp' . DS . 'lib';
define('CAKE_CORE_INCLUDE_PATH', $pathCakeInVendorDir);

$boot = true;
if (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
    trigger_error('CakePHP core could not be found. CakePHP core should be at "'.CAKE_CORE_INCLUDE_PATH.'"', E_USER_ERROR);
}

App::uses('ConnectionManager', 'Model');
$dbConfig = ConnectionManager::getDataSource('default');

return
[
    'paths' => [
        'migrations' => ROOT.'/Config/Migrations',
        'seeds' => ROOT.'/Config/Seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'phinx_cake2',
        'phinx_cake2' => [
            'adapter' => 'mysql',
            'host' => $dbConfig->config['host'],
            'name' => $dbConfig->config['database'],
            'user' => $dbConfig->config['login'],
            'pass' => $dbConfig->config['password'],
            'port' => $dbConfig->config['port'],
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
