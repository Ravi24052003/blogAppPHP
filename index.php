<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header("HTTP/1.1 200 OK");
die();
}

require_once 'config.php';
require_once "./controllers/AuthController.php";
require_once "./controllers/PostController.php";

switch ($method) {
    case 'GET':
        require 'request_method/get.php';
        break;
    case 'POST':
        require 'request_method/post.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(["notFound" => "Not Found"]);
        break;
}
?>