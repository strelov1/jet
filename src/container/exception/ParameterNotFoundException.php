<?php declare(strict_types=1);

namespace jet\container\exception;

use Psr\Container\ContainerExceptionInterface;

class ParameterNotFoundException extends \Exception implements ContainerExceptionInterface
{

}
