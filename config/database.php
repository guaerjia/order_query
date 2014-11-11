<?php
/**
 * Created by PhpStorm.
 * User: 4003310
 * Date: 2014/11/10
 * Time: 18:16
 */

return array(
    'driver'    => 'mysql',
    'host'      => $_SERVER['DATABASE_HOST'],
    'database'  => $_SERVER['DATABASE_DB'],
    'username'  => $_SERVER['DATABASE_USERNAME'],
    'password'  => $_SERVER['DATABASE_USERPASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
);