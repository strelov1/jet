<?php declare(strict_types=1);

namespace jet\base;

use jet\container\Container;

class Application
{
    public const VERSION = '0.01';

    public $name;

    protected $routes;

    public $controller;
    public $config = [];

    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getRequest()
    {
        return $this->di(Request::class);
    }

    public function getResponse()
    {
        return $this->di(Response::class);
    }

    public function di($name = null)
    {
        $container = new Container($this->config);
        if ($name) {
            return $container->get($name);
        }
        return $container;
    }

    public function run()
    {
        $response = $this->handleRequest($this->getRequest());
        return $response->send();
    }

    public function get(string $route, callable $function)
    {
        $this->routes[$route] = $function;
    }

    public function handleRequest(Request $request)
    {
        [$route, $params] = $request->resolve();
        $result = $this->runHandle($route, $params);
        if ($result instanceof Response) {
            return $result;
        }
        $response = $this->getResponse();
        if ($result !== null) {
            $response->data = $result;
        }
        return $response;
    }

    public function runHandle($route, $params)
    {
        $function = $this->routes[$route];
        return $function($params);
    }
}
