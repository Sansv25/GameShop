<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Set to '*' to trust all proxies, which is appropriate when running
     * behind Railway's Caddy reverse proxy where the proxy IP is not fixed.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * HEADER_X_FORWARDED_FOR      — client IP
     * HEADER_X_FORWARDED_HOST     — original host
     * HEADER_X_FORWARDED_PORT     — original port
     * HEADER_X_FORWARDED_PROTO    — http or https (critical for HTTPS URL generation)
     * HEADER_X_FORWARDED_PREFIX   — path prefix
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_PREFIX;
}
