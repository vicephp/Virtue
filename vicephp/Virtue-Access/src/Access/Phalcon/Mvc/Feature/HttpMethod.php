<?php

namespace Virtue\Access\Phalcon\Mvc\Feature;

use Phalcon\Http\Request;
use Virtue\Access;

class HttpMethod implements Access\GrantsAccess
{
    /** @var Access\GrantsAccess */
    private $permission;
    /** @var Request */
    private $request;

    public function __construct(Access\GrantsAccess $permission, Request $request)
    {
        $this->permission = $permission;
        $this->request = $request;
    }

    public function granted(string $resource): bool
    {
        return $this->permission->granted("{$resource}:{$this->request->getMethod()}");
    }
}
