<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidAmountBasedOnType implements Rule
{
    protected $transactionType;

    /**
     * Create a new rule instance.
     *
     * @param string $transactionType
     */
    public function __construct($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $amount
     * @return bool
     */
    public function passes($attribute, $amount)
    {
        $amount = (float) $amount;
        
        if ($this->transactionType === 'credit') {
            // Credits must be positive and below $10,000
            return $amount > 0 && $amount <= 10000;
        }
        
        if ($this->transactionType === 'debit') {
            // Debits must be negative or positive but with special rules
            return $amount < 0 || ($amount > 0 && $amount <= 5000);
        }
        
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->transactionType === 'credit'
            ? 'Credit amounts must be positive and ≤ $10,000'
            : 'Debit amounts must be negative or positive amounts ≤ $5,000';
    }
}