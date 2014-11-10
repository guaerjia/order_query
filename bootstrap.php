<?php
/**
 * Created by PhpStorm.
 * User: 4003310
 * Date: 2014/11/10
 * Time: 17:59
 */

use Illuminate\Database\Capsule\Manager as Capsule;

// BASE_PATH
define('BASE_PATH', __DIR__);

// VIEW_BASE_PATH
define('VIEW_BASE_PATH', BASE_PATH . '/app/views/');

// BASE_URL
$config = require BASE_PATH . '/config/config.php';
define('BASE_URL', $config['base_url']);

// TIME_ZONE
date_default_timezone_get($config['time_zone']);

// Autoload
require BASE_PATH . '/vendor/autoload.php';

// View loader
class_alias('\TinyLara\TinyView\TinyView', 'View');

// Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection( require BASE_PATH . '/config/database.php' );
$capsule->bootEloquent();

// whoops: php errors for cool kids
$whoops = new \Whoops\Run;
$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler );
$whoops->register();