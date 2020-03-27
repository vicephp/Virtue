<?php

namespace Virtue\Http\Message;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Virtue\Http\Message\Header\HeaderParser;

class RequestParser
{
    public function header(ServerRequest $request): HeaderParser
    {
        return new Header\HeaderParser(
            new Header\AcceptParser(),
            new Header\ForwardedParser(),
            $request
        );
    }
}
