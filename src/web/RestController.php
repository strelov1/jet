<?php declare(strict_types=1);

namespace jet\web;

class RestController
{
    protected $request;
    protected $response;

    public function __construct(\jet\base\Request $request)
    {
        $this->request = $request;
        echo 'RestController INIT', '<br>';
    }
}
