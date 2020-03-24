<?php

namespace Virtue\View;

use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Psr7\Stream;
use Slim\Views\PhpRenderer;

class View implements ProvidesViews
{
    /** @var ResponseFactory */
    private $factory;
    /** @var PhpRenderer */
    private $html;

    public function __construct(ResponseFactory $factory, PhpRenderer $html)
    {
        $this->factory = $factory;
        $this->html = $html;
    }

    public function withDownload(string $tmpfile, string $filename): Response
    {
        $response = $this->factory->createResponse();
        return $response->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Length', (string)filesize($tmpfile))
            ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $filename))
            ->withBody(new Stream(fopen($tmpfile, 'r')));
    }

    public function withJson($content): Response
    {
        $response = $this->factory->createResponse();
        $response->getBody()->write(json_encode($content));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function withTemplate(string $template, array $data = []): Response
    {
        return $this->html->render(
            $this->factory->createResponse(), $template, $data
        );
    }

    public function withRedirect(string $location, $status = 302): Response
    {
        $response = $this->factory->createResponse();

        return $response->withHeader('Location', $location)->withStatus($status);
    }

    public function withText(string $text, string $type = 'plain'): Response
    {
        $response = $this->factory->createResponse();
        $response->getBody()->write($text);

        return $response->withHeader('Content-Type', "text/{$type}");
    }
}
