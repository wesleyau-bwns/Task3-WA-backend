<?php

return [
    'access_token_expire_minutes' => env('PASSPORT_ACCESS_TOKEN_EXPIRE_MINUTES', 10),
    'refresh_token_expire_days' => env('PASSPORT_REFRESH_TOKEN_EXPIRE_DAYS', 30),
    'personal_access_token_expire_months' => env('PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRE_MONTHS', 6),
];
