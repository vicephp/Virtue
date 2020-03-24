<?php

namespace Virtue\View\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Server\MiddlewareInterface as ServerMiddleware;
use Psr\Http\Server\RequestHandlerInterface as HandlesServerRequests;
use Slim\Exception\HttpException;
use Virtue\View\ProvidesViews;

class TemplateError implements ServerMiddleware
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
            $code = $httpException->getCode();

            return $this->view->withTemplate(
                "errors/{$code}.phtml"
            )->withStatus($code);
        } catch (\Throwable $serverError) {
            return $this->view->withTemplate(
                'errors/500.phtml', ['exception' => $serverError]
            )->withStatus(500);
        }
    }
}
