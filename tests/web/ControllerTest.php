<?php declare(strict_types=1);

namespace tests\web;

use jet\base\Application;
use jet\web\Controller;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /** @var  Application */
    protected $app;
    public function setUp()
    {
        parent::setUp();

        $config = [
            'request' => \jet\base\Request::class,
            'response' => \jet\base\Response::class,
            'controller' => \jet\web\Controller::class
        ];
        $this->app = new Application($config);
    }
    public function testCreateController()
    {
        $controller = $this->app->di(Controller::class);
        $this->assertTrue($controller instanceof Controller);

        $controller2 = $this->app->di('controller');
        $this->assertTrue($controller2 instanceof Controller);
    }

}
