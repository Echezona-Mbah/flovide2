<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Services\OhentPayService;
use Illuminate\Console\Command;

class SyncOhentPayBanks extends Command
{
    protected $signature = 'ohentpay:sync-banks {country} {currency}';
    protected $description = 'Sync OhentPay bank list into the local banks table';

    public function handle(OhentPayService $ohentPay)
    {
        $country  = strtoupper($this->argument('country'));
        $currency = strtoupper($this->argument('currency'));

        $response = $ohentPay->getBankFields($country, $currency);

        if (!$response['success']) {
            $this->error('Failed to fetch bank fields: ' . json_encode($response['data']));
            return 1;
        }

        $fields = $response['data'] ?? [];

        $bankField = collect($fields)->firstWhere('name', 'bank_id');

        if (!$bankField || empty($bankField['options'])) {
            $this->error('No bank_id field with options found in /bankfields response.');
            $this->line('Raw response: ' . json_encode($fields));
            return 1;
        }

        $synced = 0;

        foreach ($bankField['options'] as $option) {
            $providerBankId = isset($option['value']) ? (string) $option['value'] : null;

            if (!$providerBankId) {
                $this->warn('Skipping bank with no id at all: ' . ($option['label'] ?? 'unknown'));
                continue;
            }

            // Prefer a real bank_code when OhentPay supplies one (NG-style).
            // Fall back to their internal id when they don't (e.g. KES) —
            // this becomes the public bank_code our /banks API exposes.
            $publicBankCode = $option['bank_code'] ?? $providerBankId;

            Bank::updateOrCreate(
                [
                    'provider'    => 'ohentpay',
                    'country_iso' => $country,
                    'currency'    => $currency,
                    'bank_code'   => $publicBankCode,
                ],
                [
                    'name'             => $option['label'] ?? 'Unknown Bank',
                    'provider_bank_id' => $providerBankId,
                    'bank_nibss_code'  => $option['bank_nibss_code'] ?? null,
                    'sort_code'        => null,
                    'type'             => 'bank',
                ]
            );

            $synced++;
        }

        $this->info("Synced {$synced} OhentPay banks for {$country}/{$currency}.");
        return 0;
    }
}