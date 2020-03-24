<?php

namespace Virtue\View\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Server\MiddlewareInterface as ServerMiddleware;
use Psr\Http\Server\RequestHandlerInterface as HandlesServerRequests;
use Slim\Exception\HttpException;
use Virtue\View\ProvidesViews;

class XmlError implements ServerMiddleware
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
                <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<error>
  <message>{$httpException->getTitle()}</message>
</error>
XML
            )->withStatus($httpException->getCode());
        } catch (\Throwable $serverError) {
            return $this->view->withText(
                <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<error>
  <message>500 Internal Server Error</message>
  <exception>
    <message>{$serverError->getMessage()}</message>
    <file>{$serverError->getFile()}</file>
    <line>{$serverError->getLine()}</line>
  </exception>
</error>
XML
            , 'xml')->withStatus(500);
        }
    }
}
