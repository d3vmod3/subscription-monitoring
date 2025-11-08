<?php

return [
    'salt' => env('HASHIDS_SALT', config('app.key')),
    'min_length' => env('HASHIDS_MIN_LENGTH', 10),
];
