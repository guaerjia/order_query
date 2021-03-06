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

// TIME_ZONE
//date_default_timezone_get($config['time_zone']);

// VIEW_BASE_PATH
define('VIEW_BASE_PATH', BASE_PATH . '/app/views/');

// BASE_URL
$config = require BASE_PATH . '/config/config.php';
define('BASE_URL', $config['base_url']);

define('ERROR_LOG', $config['error_log']);

// exception handler
function exceptionHandler($exception) {

    $error_message = "[".date("Y-m-d H:i:s")."] ".$exception->getMessage()."\n";
    $error_message .= "REQUEST: ".print_r($_REQUEST, true)."\n";
    $error_message .= "REQUEST_URI: ".print_r($_SERVER['REQUEST_URI'], true)."\n\n";
    error_log($error_message, 3, ERROR_LOG);

    die();

}
set_exception_handler('exceptionHandler');

// error handler
function errorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = "[".date("Y-m-d H:i:s")."] [{$errno}] {$errstr}\n";
    $error_message .= "Error on line {$errline} in {$errfile}\n\n";
    $error_message .= "REQUEST: ".print_r($_REQUEST, true)."\n";
    $error_message .= "REQUEST_URI: ".print_r($_SERVER['REQUEST_URI'], true)."\n\n";

    error_log($error_message, 3, ERROR_LOG);

    die();
}
set_error_handler("errorHandler");

register_shutdown_function('my_shutdown_function');
function my_shutdown_function() {

    // 捕获最后的错误
    if (error_get_last()) {
        error_log( "[".date("H:i:s")."]".print_r(error_get_last(), true)."\n\n", 3, ERROR_LOG);
    }
}



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