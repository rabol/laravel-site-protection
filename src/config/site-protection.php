<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Passwords For Laravel Site Protection
    |--------------------------------------------------------------------------
    |
    | 
    | 
    |
    */

    'passwords' => env('SITE_PROTECTION_PASSWORDS'),
    'cookie_life_time' => env('SITE_PROTECTION_COOKIE_LIFE_TIME',0)

];