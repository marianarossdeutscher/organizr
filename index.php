<?php
date_default_timezone_set('UTC');
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case $method === 'POST'   && preg_match('~^/api/auth/register$~', $uri):
        (new Src\Controllers\AuthController())->register();
        break;
    case $method === 'POST'   && preg_match('~^/api/auth/login$~', $uri):
        (new Src\Controllers\AuthController())->login();
        break;
    case $method === 'GET'    && preg_match('~^/api/status$~', $uri):
        (new Src\Controllers\StatusController())->index();
        break;
    case $method === 'POST'   && preg_match('~^/api/status$~', $uri):
        (new Src\Controllers\StatusController())->create();
        break;
    case $method === 'PUT'    && preg_match('~^/api/status/(\d+)$~', $uri, $m):
        (new Src\Controllers\StatusController())->update((int)$m[1]);
        break;
    case $method === 'DELETE' && preg_match('~^/api/status/(\d+)$~', $uri, $m):
        (new Src\Controllers\StatusController())->delete((int)$m[1]);
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Not Found"]);
        break;
}