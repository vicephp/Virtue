<?php

namespace Virtue\JWT\Http;

interface OAuthKeysStore
{
    public function get(string $issuer): array;
}
