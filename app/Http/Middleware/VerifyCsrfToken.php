<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/profile/register',
        '/api/alarm/add',
        '/api/alarm/remove',
        '/api/smoke_data/add',
        '/api/goal/add',
        '/api/smoke_data/smoke_free_for',
    ];
}
