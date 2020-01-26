<?php

namespace Component;

/**
 * Class Route
 * @package Component
 */
class Route
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $controller;

    /**
     * @var string
     */
    private string $action;

    private array $params;

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param string $controller
     * @param string $action
     */
    public function __construct(
        string $name,
        string $path,
        string $controller,
        string $action
    ) {
        $this->name       = $name;
        $this->path       = $path;
        $this->controller = $controller;
        $this->action     = $action;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function controller(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function action(): string
    {
        return $this->action;
    }

    public function addParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }
}
