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
        '/api/profile/get_all_information',

        '/api/alarm/add',
        '/api/alarm/remove',

        '/api/smoke_data/add',

        '/api/goal/add',

        '/api/smoke_data/get_tile_data',
    ];
}
