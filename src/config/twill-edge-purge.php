<?php

return [
    'enabled' => env('TWILL_SECURITY_HEADERS_ENABLED', true),

    'middleware' => [
        'inject' => env('TWILL_SECURITY_MIDDLEWARE_INJECTION_ENABLED', true),

        'enabled_inside_twill' => false,

        'groups' => [
            [
                'group' => 'web',
                'type' => 'prepend',
                'classes' => [\A17\TwillEdgePurge\Http\Middleware\All::class],
            ],

            [
                'group' => 'api',
                'type' => 'prepend',
                'classes' => [\A17\TwillEdgePurge\Http\Middleware\All::class],
            ],
        ],
    ],

    'headers' => [
        'csp' => [
            'available' => true,
            'type' => 'csp',
            'form' => ['title' => 'Content Security Policy'],
            'header' => 'Content-Security-Policy',
            'default' => [
                'block' => "default-src 'self'; img-src https://*; child-src 'none';",
                'report-only' => "object-src 'none';",
            ],
            'service' => \A17\TwillEdgePurge\Services\Headers\CSP::class,
        ],

        'hsts' => [
            'available' => true,
            'type' => 'hsts',
            'form' => ['title' => 'Strict Transport Security'],
            'header' => 'Strict-Transport-Security',
            'default' => ['value' => 'max-age=31536000; includeSubDomains'],
            'service' => \A17\TwillEdgePurge\Services\Headers\HSTS::class,
        ],

        'permissions-policy' => [
            'available' => true,
            'type' => 'permissions-policy',
            'form' => ['title' => 'Permissions Policy'],
            'header' => 'Permissions-Policy',
            'default' => [
                'value' =>
                    'autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()',
            ],
            'service' => \A17\TwillEdgePurge\Services\Headers\PermissionsPolicy::class,
        ],

        'referrer-policy' => [
            'available' => true,
            'type' => 'referrer-policy',
            'form' => ['title' => 'Referrer Policy'],
            'header' => 'Referrer-Policy',
            'default' => ['value' => 'no-referrer-when-downgrade'],
            'service' => \A17\TwillEdgePurge\Services\Headers\ReferrerPolicy::class,
        ],

        'x-content-type-policy' => [
            'available' => true,
            'type' => 'x-content-type-policy',
            'form' => ['title' => 'X-Content-Type-Options'],
            'header' => 'X-Content-Type-Options',
            'default' => ['value' => 'nosniff'],
            'service' => \A17\TwillEdgePurge\Services\Headers\XContentType::class,
        ],

        'x-frame-policy' => [
            'available' => true,
            'type' => 'x-frame-policy',
            'form' => ['title' => 'X-Frame-Options'],
            'header' => 'X-Frame-Options',
            'default' => ['value' => 'SAMEORIGIN'],
            'service' => \A17\TwillEdgePurge\Services\Headers\XFrame::class,
        ],

        'xss-protection-policy' => [
            'available' => true,
            'type' => 'xss-protection-policy',
            'form' => ['title' => 'X-XSS-Protection'],
            'header' => 'X-XSS-Protection',
            'default' => ['value' => '1; mode=block'],
            'service' => \A17\TwillEdgePurge\Services\Headers\XSSProtection::class,
        ],

        'expect-ct' => [
            'available' => true,
            'type' => 'expect-ct',
            'form' => ['title' => 'Expect CT'],
            'header' => 'Expect-CT',
            'default' => ['value' => 'enforce, max-age=30'],
            'service' => \A17\TwillEdgePurge\Services\Headers\ExpectCT::class,
        ],
    ],

    'unwanted-headers' => ['X-Powered-By', 'server', 'Server'],
];
