<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$config = [
    'request' => \jet\base\Request::class,
    'response' => \jet\base\Response::class,
    'controller' => \jet\web\Controller::class,
];

$app = new \jet\base\Application($config);

$app->get('/', function () {
    return 'Hello World';
});

$app->get('/foo', function ($params) use ($app, $config) {
    return "Hello World {$params->bar}";
});

$app->run();

function d($expression)
{
    echo '<pre>';
    print_r($expression);
}

function dd($expression)
{
    d($expression);
    exit();
}