<?php

use Google\Service\Sheets;

return [
    'application_name' => env('GOOGLE_APPLICATION_NAME', 'GPRAdmin'),
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT'),
    'spreadsheet_id' => env('GOOGLE_SPREADSHEET_ID'),
    'spreadsheet_range' => env('GOOGLE_SPREADSHEET_RANGE'),
    'credentials_path' => base_path(env('GOOGLE_CREDENTIALS_PATH', 'credentials.json')),
];
