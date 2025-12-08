<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'sys/histories/list',
        'sys/histories/location',
        'sys/histories/occupation',
        'sys/histories/dni',
        '/sys/ex-dx/validate-match',
        '/sys/ex-mx/validate-match',
        '/sys/diagnostics/list',
        '/sys/diagnostics/search',
        '/sys/drugs/search'
    ];
}
