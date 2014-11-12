<?php

namespace TinyLara\TinyRoute;

/**
 * @method static TinyRoute get(string $route, Callable $callback)
 * @method static TinyRoute post(string $route, Callable $callback)
 * @method static TinyRoute put(string $route, Callable $callback)
 * @method static TinyRoute delete(string $route, Callable $callback)
 * @method static TinyRoute options(string $route, Callable $callback)
 * @method static TinyRoute head(string $route, Callable $callback)
 */
class TinyRoute {

  public static $routes = array();

  public static $methods = array();

  public static $callbacks = array();

  public static $patterns = array(
    ':any' => '[^/]+',
    ':num' => '[0-9]+',
    ':all' => '.*'
  );

  public static $error_callback;

  /**
   * Defines a route w/ callback and method
   */
  public static function __callstatic($method, $params)
  {

    $uri = dirname($_SERVER['PHP_SELF']).$params[0];
    $callback = $params[1];

    array_push(self::$routes, $uri);
    array_push(self::$methods, strtoupper($method));
    array_push(self::$callbacks, $callback);
  }

  /**
   * Defines callback if route is not found
  */
  public static function error($callback)
  {
    self::$error_callback = $callback;
  }

  /**
   * Runs the callback for the given request
   *
   * $after: Processor After. It will process the value returned by Controller.
   * Example: View@process
   *
   */
  public static function dispatch($after=null)
  {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

      //var_dump($uri);var_dump($method);exit();

    $searches = array_keys(static::$patterns);
    $replaces = array_values(static::$patterns);

    $found_route = false;

    // check if route is defined without regex
    if (in_array($uri, self::$routes)) {
      $route_pos = array_keys(self::$routes, $uri);
      foreach ($route_pos as $route) {

        if (self::$methods[$route] == $method) {
          $found_route = true;

          //if route is not an object
          if(!is_object(self::$callbacks[$route])){

            //grab all parts based on a / separator
            $parts = explode('/',self::$callbacks[$route]);

            //collect the last index of the array
            $last = end($parts);

            //grab the controller name and method call
            $segments = explode('@',$last);
            //instanitate controller
            $controller = new $segments[0]();

            //call method
            $return = $controller->$segments[1]();

            if ($after) {
              $after_segments = explode('@', $after);
              $after_segments[0]::$after_segments[1]($return);
            }

          } else {
            //call closure
            call_user_func(self::$callbacks[$route]);
          }
        }
      }
    } else {
      // check if defined with regex
      $pos = 0;
      foreach (self::$routes as $route) {

        if (strpos($route, ':') !== false) {
          $route = str_replace($searches, $replaces, $route);
        }

        if (preg_match('#^' . $route . '$#', $uri, $matched)) {
          if (self::$methods[$pos] == $method) {
            $found_route = true;

            array_shift($matched); //remove $matched[0] as [1] is the first parameter.


            if(!is_object(self::$callbacks[$pos])){

              //grab all parts based on a / separator
              $parts = explode('/',self::$callbacks[$pos]);

              //collect the last index of the array
              $last = end($parts);

              //grab the controller name and method call
              $segments = explode('@',$last);

              //instanitate controller
              $controller = new $segments[0]();

              //call method and pass any extra parameters to the method
              $return = $controller->$segments[1](implode(",", $matched));

              if ($after) {
                $after_segments = explode('@', $after);
                $after_segments[0]::$after_segments[1]($return);
              }

            } else {
              call_user_func_array(self::$callbacks[$pos], $matched);
            }

          }
        }
      $pos++;
      }
    }


    // run the error callback if the route was not found
    if ($found_route == false) {
      if (!self::$error_callback) {
        self::$error_callback = function() {
          header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
          echo '404';
        };
      }
      call_user_func(self::$error_callback);
    }
  }
}