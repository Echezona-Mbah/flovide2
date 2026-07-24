<?php

return [
    'bonus_trigger_amount_personal' => env('REFERRAL_BONUS_TRIGGER_AMOUNT_PERSONAL', 10),
    'bonus_trigger_amount_business' => env('REFERRAL_BONUS_TRIGGER_AMOUNT_BUSINESS', 50),
    'bonus_trigger_currency'        => env('REFERRAL_BONUS_TRIGGER_CURRENCY', 'CAD'),
    'trigger_mode'                  => env('REFERRAL_BONUS_TRIGGER_MODE', 'cumulative'),
];