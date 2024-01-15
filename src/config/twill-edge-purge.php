<?php

return [
    'enabled' => env('TWILL_EDGE_PURGE_ENABLED', false),

    'allowed' => [
        'roles' => explode(',', env('TWILL_EDGE_PURGE_ALLOWED_ROLES', 'SUPERADMIN,ADMIN')),
    ],

    'service' => [
        'name' => env('TWILL_EDGE_PURGE_SERVICE', 'cloudflare'),
    ],
];
