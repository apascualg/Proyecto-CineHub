<?php

return [
    'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),
    'image_base_url' => env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p'),
    'token' => env('TMDB_API_TOKEN'),
    'api_key' => env('TMDB_API_KEY'),
    'ca_bundle' => env('TMDB_CA_BUNDLE', storage_path('certs/cacert.pem')),
];
