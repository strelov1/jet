<?php declare(strict_types=1);

namespace jet\base;

class Request
{
    public const GET = 'get';
    public const POST = 'post';
    public const REQUEST = 'request';
    public const COOKIE = 'cookie';
    public const HEADER = 'header';

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function post(string $name = null)
    {
        return $this->getParam(self::POST, $name);
    }

    public function get(string $name = null)
    {
        return (object)$this->getParam(self::GET, $name);
    }

    public function request(string $name = null)
    {
        return $this->getParam(self::REQUEST, $name);
    }

    public function cookie(string $name = null)
    {
        return $this->getParam(self::COOKIE, $name);
    }

    public function header(string $name = null)
    {
        return $this->getParam(self::HEADER, $name);
    }

    public function uri()
    {
        return $this->header('PATH_INFO');
    }

    public function resolve()
    {
        $uri = $this->uri();
        $get = $this->get();
        if (!$uri) {
            return ['/', $get];
        }
        return [$uri, $get];
    }

    /**
     * @param $type
     * @param null | string $name
     * @return bool|mixed
     */
    private function getParam($type, $name = null)
    {
        $param = $this->typeToParam($type);

        if (null === $name) {
            return $param;
        }
        return $param[$name] ?? null;
    }

    private function typeToParam($type)
    {
        $types = [
            self::GET => $_GET,
            self::POST => $_POST,
            self::REQUEST => $_REQUEST,
            self::COOKIE => $_COOKIE,
            self::HEADER => $_SERVER,
        ];
        return $types[$type] ?? null;
    }
}
