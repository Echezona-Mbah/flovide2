<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTransactionReference implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Example 1: Check reference matches expected format
        return preg_match('/^[A-Z]{3}-\d{4}-\d{4}$/', $value);
        
        // Example 2: More complex validation
        /*
        return preg_match('/^'. 
            'TXN-'.                  // Prefix
            '[A-Z0-9]{4}-'.          // Merchant code
            '\d{4}'.                 // Year
            '(0[1-9]|1[0-2])'.      // Month
            '\d{2}'.                 // Day
            '-[A-Z0-9]{6}$/i',      // Random string
            $value);
        */
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid transaction reference in format: ABC-1234-5678';
    }
}