<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * Chatbot endpoints are public API-style routes called from JS widgets.
     *
     * @var array<int, string>
     */
    protected $except = [
        'chatbot/chat',
        'chatbot/contact',
        'chatbot/init',
        'payment/create-order',
        'payment/verify',
    ];
}
