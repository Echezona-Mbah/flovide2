<?php

namespace App\Traits;

trait SelectsBalanceId
{
    /**
     * Get balance ID based on the currency code.
     *
     * @param string $currency
     * @return string|null
     */
    public function getBalanceIdByCurrency(string $currency): ?string
    {
        $balances = [
            'NGN' => '174991a8-4254-11f0-a0da-d6d46ae1b2fb',
            'CAD' => '174991a8-4254-11f0-cad00000001',
        ];

        return $balances[strtoupper($currency)] ?? null;
    }
}
