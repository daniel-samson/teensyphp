<?php
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
 * @param callable $action
 */
function route(bool $http_method_predicate, bool $path_predicate, callable $action): void
{
    if (!$http_method_predicate || !$path_predicate) {
        return;
    }

    call_user_func_array($action, array());
    exit();
}

/**
 * redirect client to url
 * @param string $url
 * @return string
 */
function redirect(string $url): string
{
    header('Location: ' . $url, true);
    exit();
}
