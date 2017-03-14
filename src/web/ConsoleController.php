<?php declare(strict_types=1);

namespace jet\web;

class ConsoleController
{
    protected $request;
    protected $response;

    public function __construct(\jet\base\Request $request)
    {
        $this->request = $request;
        echo 'ConsoleController INIT', '<br>';
    }
}
