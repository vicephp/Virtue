<?php

namespace Virtue\Access\Slim\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim;
use Virtue\Access;

class RouteAccess implements MiddlewareInterface
{
    /** @var Access\GrantsAccess */
    private $routeAccess;

    public function __construct(Access\GrantsAccess $routeAccess)
    {
        $this->routeAccess = $routeAccess;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = Slim\Routing\RouteContext::fromRequest($request)->getRoute();
        $resource = $route ? $route->getPattern() : '';
        if ($this->routeAccess->granted($resource)) {
            return $handler->handle($request);
        }

        throw new \Slim\Exception\HttpForbiddenException($request);
    }
}
