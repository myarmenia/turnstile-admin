<?php
// File: config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*', '_next/image'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://turniket.am', // Your Next.js frontend
        'http://localhost:3000' // For local development
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
