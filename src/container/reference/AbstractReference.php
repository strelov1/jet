<?php declare(strict_types=1);

namespace jet\container\reference;

abstract class AbstractReference
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}