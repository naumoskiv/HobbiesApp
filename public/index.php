<?php
use app\core\Application;
use app\controllers\AuthorizeController;
use app\controllers\SiteController;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => \app\models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];


$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/login', [AuthorizeController::class, 'login']);
$app->router->post('/login', [AuthorizeController::class, 'login']);
$app->router->get('/register', [AuthorizeController::class, 'register']);
$app->router->post('/register', [AuthorizeController::class, 'register']);
$app->router->get('/logout', [AuthorizeController::class, 'logout']);
$app->router->get('/profile', [AuthorizeController::class, 'profile']);

$app->run();