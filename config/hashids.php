<?php

return [
    // Sinkron semua model - jangan ubah setelah data ada
    'salt' => env('HASHIDS_SALT', 'd4rpl4b-polindra-warm-2026-salt'),
    'min_length' => env('HASHIDS_MIN_LENGTH', 10),
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
];
