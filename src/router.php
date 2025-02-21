<?php
require_once __DIR__ . "/stop.php";

/** HTTP methods */
const GET = 'GET';
const HEAD = 'HEAD';
const POST = 'POST';
const PUT = 'PUT';
const DELETE = 'DELETE';
const CONNECT = 'CONNECT';
const OPTIONS = 'OPTIONS';
const TRACE = 'TRACE';

/**
 * Predicate to determine is the http method in effect
 * @param string $method
 * @return bool
 */
function method(string $method): bool
{
    return $_SERVER['REQUEST_METHOD'] === $method;
}

/**
 * Predicate to determine is the url path being called
 * @param string $path
 * @return bool
 */
function url_path(string $path): bool
{
    if ($path === '*') {
        return true;
    }

    if ($path === '/') {
        $path = '';
    } elseif (!empty($path) && $path[0] === '/') {
        $path = substr($path, 1);
    }

    global $PATH_PREFIX;
    if(!empty($PATH_PREFIX)) {
        $path = $PATH_PREFIX . "/" . $path;
    }

    if (str_ends_with($path, '/')) {
        $path = substr($path, 0, -1);
    }

    return $path === $_GET['url'];
}

/**
 * Predicate to determine is the url path being called, and set up $_GET with the params being passed in
 * @param string $path path in this case is expecting something like /user/:id/:page
 * @return bool
 */
function url_path_params(string $path): bool
{
    if ($path[0] === '/') {
        $path = substr($path, 1);
    }

    global $PATH_PREFIX;
    if(!empty($PATH_PREFIX)) {
        $path = $PATH_PREFIX . "/" . $path;
    }

    $query_string_parts = explode('/', $_GET['url']);
    $path_parts = explode('/', $path);
    if (count($query_string_parts) !== count($path_parts)) {
        return false;
    }

    $pos = strpos($path, ':');
    if ($pos !== false) {
        // starts with $path
        if (substr($path, 0, $pos) === substr($_GET['url'], 0, $pos)) {
            foreach ($query_string_parts as $part => $query_string_part) {
                if ($path_parts[$part][0] === ':') {
                    $_GET[$path_parts[$part]] = $query_string_part;
                }
            }
            return true;
        }
    }

    return false;
}


/**
 * Group routes by prefix
 * @param $prefix
 * @param callable $routes
 * @return void
 */
function routerGroup($prefix, callable $routes): void
{
    global $PATH_PREFIX;
    $previous_prefix = $PATH_PREFIX;
    $PATH_PREFIX .= $prefix;

    if (!empty($PATH_PREFIX) && $PATH_PREFIX[0] === '/') {
        $PATH_PREFIX = substr($PATH_PREFIX, 1);
    }

    // if last character is a slash, remove it
    if (str_ends_with($PATH_PREFIX, '/')) {
        $PATH_PREFIX = substr($PATH_PREFIX, 0, -1);
    }

    call_user_func($routes);

    // reset path prefix
    $PATH_PREFIX = $previous_prefix;
}

/**
 * runs routes
 * @param callable $routes - function that includes routes
 * @return void
 */
function router(callable $routes)
{
    ini_set('display_errors', 'Off');
    error_reporting(E_ALL);

    try {
        call_user_func($routes);

        if (isset($GLOBALS['TEENSYPHP_STOP_CODE'])) {
            // for testing purposes, do not throw error when stop() is called
            return;
        }

        // throw when not routes can be found
        throw new \Error("Not Found", 404);
    } catch (Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        render($e->getCode(), json_out(['error' => $e->getMessage()]));
    } catch (Error $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        render($e->getCode(), json_out(['error' => $e->getMessage()]));
    }
}

/**
 * Calls an action when the http method and path match the request before closing the connection
 * @param bool $http_method_predicate
 * @param bool $path_predicate
 * @param callable|string $action - class (with invoke method) or function
 */
function route(bool $http_method_predicate, bool $path_predicate, callable|string $action): void
{
    if (!$http_method_predicate || !$path_predicate) {
        return;
    }

    if (is_string($action)) {
        // allow invoking a class (single action controller)
        $action = new $action();
    }

    call_user_func_array($action, array());
    stop();
}

/**
 * redirect client to url
 * @param string $url
 * @return void
 */
function redirect(string $url): void
{
    header('Location: ' . $url, true);
    stop();
}

/**
 * uses request uri path instead of url query parameter
 * @return void
 */
function use_request_uri(): void
{
    $url = $_SERVER['REQUEST_URI'];
    if ($url[0] === '/') {
        $url = substr($url, 1);
    }

    $_GET['url'] = $url;
}
