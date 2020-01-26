<?php

namespace Component;

use RuntimeException;

class Router
{
    /**
     * @var array
     */
    private array $routes;

    /**
     * @var Route
     */
    private Route $matchedRoute;

    /**
     * Router constructor.
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        foreach ($routes as $name => $route) {
            $this->routes[] = new Route($name, $route['path'], $route['controller'], $route['action']);
        }
    }

    /**
     * @param string $path
     * @return bool
     */
    public function match(string $path = '/'): bool
    {
        $path = $path === '/' ? $path : trim($path, '/');

        foreach ($this->routes as $route) {
            /* @var $route Route */

            $routePath = $route->path();

            preg_match_all('#\{(.*?)\}#uis', $routePath, $matches);

            $replace = [];
            $search = [];
            foreach ($matches[0] as $key => $match) {
                if (!strpos($match, ':')) {
                    continue;
                }

                $partial = explode(':', trim($match, '{}'));

                $replace[] = sprintf('(?<%s>%s)', $partial[0], $partial[1]);

                $search[] = $matches[0][$key];
            }

            $routePath = str_replace($search, $replace, $routePath);

            if (preg_match('#^' . $routePath . '$#uis', $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $route->addParams($params);
                $this->matchedRoute = $route;
                return true;
            }
        }

        return false;
    }

    /**
     * @return Route
     */
    public function getMatchedRoute(): Route
    {
        return $this->matchedRoute;
    }

    public function dispatch()
    {
        $controllerClass = $this->matchedRoute->controller();

        if (!class_exists($controllerClass)) {
            throw new RuntimeException(sprintf('class %s not found', $controllerClass));
        }

        if (is_callable($this->matchedRoute->controller())) {
            return $this->matchedRoute->controller();
        }

        return function (...$dependency) use ($controllerClass) {
            return call_user_func_array(
                [new $controllerClass(...$dependency), $this->matchedRoute->action()],
                $this->matchedRoute->getParams()
            );
        };
    }
}
