<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$vendorDir = dirname(dirname(dirname(dirname(__FILE__))));

// Easiest case: cakephp/cakephp is installed
$cakephpDir = \Composer\InstalledVersions::getInstallPath("cakephp/cakephp");

// More complex case, cakephp/cakephp is replaced by another package
if (null === $cakephpDir) {
    $composerInstalledJson = $vendorDir . DS . 'composer/installed.json';
    $composerInstalledJsonEncoded = file_get_contents($composerInstalledJson);
    if (!is_string($composerInstalledJsonEncoded)) {
        throw new Exception('Could not read composer/installed.json');
    }
    try {
        /** @var StdClass $composerInstalled */
        $composerInstalled = json_decode($composerInstalledJsonEncoded, false, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        throw new Exception('Could not read composer/installed.json');
    }
    foreach ($composerInstalled->packages as $packageInfo) {
        if (isset($packageInfo->replace->{'cakephp/cakephp'})) {
            $cakephpDir = \Composer\InstalledVersions::getInstallPath($packageInfo->name);
            break;
        }
    }
}
$cakephpLibDir = $cakephpDir . DS . 'lib';

define('CAKE_CORE_INCLUDE_PATH', $cakephpLibDir);

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

$boot = true;
if (!include CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php') {
    trigger_error('CakePHP core could not be found. CakePHP core should be at "'.CAKE_CORE_INCLUDE_PATH.'"', E_USER_ERROR);
}

/**
 * Plugin handling
 */
$plugin = getenv('PLUGIN');
$migrationRoot = ROOT;

if (!empty($plugin)) {
    $found = false;
    foreach (App::path('plugins') as $path) {
        $pluginRoot = $path . $plugin;
        if (file_exists($pluginRoot)) {
            $migrationRoot =  $pluginRoot;
            $found = true;
            break;
        }
    }

    if (!$found) {
        throw new exception(sprintf('Could not find plugin directory for plugin "%s"', $plugin));
    }
}

$dataSource = 'default';
$pluginConfig = sprintf('%1$s%2$sConfig%2$sphinx.php', $migrationRoot, DS);
if (file_exists($pluginConfig)) {
    require_once $pluginConfig;
    if (Configure::check('phinx.datasource') && is_string(Configure::read('phinx.datasource'))) {
        $dataSource = Configure::read('phinx.datasource');
    }
}

App::uses('ConnectionManager', 'Model');
$dbConfig = ConnectionManager::getDataSource($dataSource);

return [
    'paths' => [
        'migrations' => sprintf('%1$s%2$sConfig%2$sMigrations', $migrationRoot, DS),
        'seeds' =>  sprintf('%1$s%2$sConfig%2$sSeeds', $migrationRoot, DS),
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
