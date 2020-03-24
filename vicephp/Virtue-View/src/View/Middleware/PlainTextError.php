<?php

namespace Virtue\View\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Server\MiddlewareInterface as ServerMiddleware;
use Psr\Http\Server\RequestHandlerInterface as HandlesServerRequests;
use Slim\Exception\HttpException;
use Virtue\View\ProvidesViews;

class PlainTextError implements ServerMiddleware
{
    /** @var ProvidesViews */
    private $view;

    public function __construct(ProvidesViews $view)
    {
        $this->view = $view;
    }

    public function process(ServerRequest $request, HandlesServerRequests $handler): Response
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $httpException) {
            return $this->view->withText(
<<<TEXT
{$httpException->getTitle()}
Message: {$httpException->getDescription()}
TEXT
            )->withStatus($httpException->getCode());
        } catch (\Throwable $serverError) {
            return $this->view->withText(
<<<TEXT
500 Internal Server Error
Message: {$serverError->getMessage()}
File: {$serverError->getFile()}
Line: {$serverError->getLine()}
TEXT
            )->withStatus(500);
        }
    }
}
