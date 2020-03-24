<?php

namespace Virtue\View;

use Psr\Http\Message\ResponseInterface as Response;

interface ProvidesViews
{
    public function withDownload(string $tmpfile, string $filename): Response;
    public function withJson($content): Response;
    public function withTemplate(string $template, array $data = []): Response;
    public function withRedirect(string $location, $status = 302): Response;
    public function withText(string $text, string $type = 'plain'): Response;
}
