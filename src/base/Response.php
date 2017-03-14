<?php declare(strict_types=1);

namespace jet\base;

class Response
{
    public $data;

    public function send()
    {
       echo $this->data;
       exit();
    }
}
