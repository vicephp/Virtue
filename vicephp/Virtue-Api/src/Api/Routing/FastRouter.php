<?php

namespace Virtue\Api\Routing;

use FastRoute;
use Psr\Container\ContainerInterface as Locator;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use function array_pop;

class FastRouter implements RouteCollector, Router
{
    /** @var Locator */
    private $kernel;
    /** @var RouteGroup[] */
    private $routeGroups = [];
    /** @var int */
    private $routeCounter = 0;
    /** @var FastRoute\RouteCollector */
    private $routes;

    public function __construct(Locator $kernel) {
        $this->kernel = $kernel;
        $this->routes = $kernel->get(FastRoute\RouteCollector::class);
    }

    public function group(string $pattern, $callable): RouteGroup
    {
        $api = new Api($this->kernel, $this, $pattern);
        $routeGroup = new RouteGroup($callable, $this->kernel, $api);
        $this->routeGroups[] = $routeGroup;
        $routeGroup->collectRoutes();
        array_pop($this->routeGroups);

        return $routeGroup;
    }

    public function map(array $methods, string $pattern, $handler): Route
    {
        $route = $this->createRoute($methods, $pattern, $handler);
        $this->routes->addRoute($methods, $pattern, $route);

        return $route;
    }

    public function route(ServerRequest $request): ServerRequest
    {
        $dispatcher = new FastRoute\Dispatcher\GroupCountBased($this->routes->getData());
        $result = new RoutingResults($dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath()));

        return $result->withRequest($request);
    }

    protected function createRoute(array $methods, string $pattern, $handler): Route
    {
        return new Route(
            $methods,
            $pattern,
            $handler,
            $this->kernel,
            $this->routeGroups,
            $this->routeCounter++
        );
    }
}
