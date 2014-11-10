<?php
/**
 * Created by PhpStorm.
 * User: 4003310
 * Date: 2014/11/10
 * Time: 17:55
 */

// PUBLIC_PATH
define('PUBLIC_PATH', __DIR__);

// bootstrap
require PUBLIC_PATH . '/../bootstrap.php';

// Routes and Begin processing
require BASE_PATH . '/config/routes.php';