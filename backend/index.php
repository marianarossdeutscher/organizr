<?php
declare(strict_types=1);

use Src\Controllers\AuthController;
use Src\Controllers\TaskController;
use Src\Controllers\UserController;

date_default_timezone_set('UTC');
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- Roteamento ---
$script  = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base    = dirname($script);
$uri     = '/' . ltrim(substr($request, strlen($base)), '/');
$method  = $_SERVER['REQUEST_METHOD'];

switch (true) {
    // Autenticação
    case $method === 'POST' && $uri === '/auth/register':
        (new AuthController())->register();
        break;
    case $method === 'POST' && $uri === '/auth/login':
        (new AuthController())->login();
        break;

    // Tarefas
    case $method === 'GET' && $uri === '/tasks':
        (new TaskController())->index();
        break;
    case $method === 'GET' && preg_match('~^/tasks/(\d+)$~', $uri, $m):
        (new TaskController())->show((int)$m[1]);
        break;
    case $method === 'POST' && $uri === '/tasks':
        (new TaskController())->create();
        break;
    case $method === 'PUT' && preg_match('~^/tasks/(\d+)$~', $uri, $m):
        (new TaskController())->update((int)$m[1]);
        break;
    case $method === 'DELETE' && preg_match('~^/tasks/(\d+)$~', $uri, $m):
        (new TaskController())->delete((int)$m[1]);
        break;

    // Utilizadores
    case $method === 'GET' && $uri === '/users':
        (new UserController())->index();
        break;
    case $method === 'GET' && preg_match('~^/users/(\d+)$~', $uri, $m):
        (new UserController())->show((int)$m[1]);
        break;
    case $method === 'PUT' && preg_match('~^/users/(\d+)$~', $uri, $m):
        (new UserController())->update((int)$m[1]);
        break;
    case $method === 'DELETE' && preg_match('~^/users/(\d+)$~', $uri, $m):
        (new UserController())->delete((int)$m[1]);
        break;

    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
        break;
}