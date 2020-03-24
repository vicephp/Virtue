<?php

namespace Virtue\View\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Server\MiddlewareInterface as ServerMiddleware;
use Psr\Http\Server\RequestHandlerInterface as HandlesServerRequests;
use Slim\Exception\HttpException;
use Virtue\View\ProvidesViews;

class JsonError implements ServerMiddleware
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
            return $this->view->withJson(
                ['error' => $httpException->getDescription()]
            )->withStatus($httpException->getCode());
        } catch (\Throwable $serverError) {
            return $this->view->withJson(
                [
                    'error' => 'The server has encountered an unexpected error.',
                    'details' => [
                        'type' => 'get_class($serverError)',
                        'code' => $serverError->getCode() ?? 'n/a',
                        'message' => $serverError->getMessage(),
                        'file' => $serverError->getFile(),
                        'line' => $serverError->getLine(),
                    ],
                ]
            )->withStatus(500);
        }
    }
}
