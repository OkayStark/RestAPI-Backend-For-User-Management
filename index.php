<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Bootstrap the application
require __DIR__ . "/inc/bootstrap.php";

// Parse the URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', $uri);

// Ensure the URI structure is as expected
if ((isset($uriSegments[2]) && $uriSegments[2] != 'user') || !isset($uriSegments[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Include the necessary controller
require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";

// Instantiate the UserController
$userController = new UserController();

// Call the appropriate method based on the URI
$methodName = $uriSegments[3] . 'Action';
$userController->{$methodName}();
