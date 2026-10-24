<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Services\SwapprService;
use Illuminate\Console\Command;

class SyncSwapprBanks extends Command
{
    protected $signature = 'swappr:sync-banks {currency=NGN} {--mode=test : Swappr key environment (test or live)}';

    protected $description = 'Add or update Swappr banks in the local bank list without deleting existing rows';

    public function handle(SwapprService $swappr): int
    {
        $currency = strtoupper((string) $this->argument('currency'));
        $mode = strtolower((string) $this->option('mode'));

        if ($currency !== 'NGN') {
            $this->error('Swappr bank synchronization is currently supported for NGN only.');
            return self::FAILURE;
        }

        if (!in_array($mode, ['test', 'live'], true)) {
            $this->error('The --mode option must be either test or live.');
            return self::FAILURE;
        }

        $response = $swappr->getBanks($currency, $mode);

        if (!$response['success']) {
            if (
                $response['status'] === 401
                && data_get($response, 'data.error.code') === 'unknown_key'
            ) {
                $this->error("Swappr rejected the {$mode} API key as unknown or revoked. Check SWAPPR_" . strtoupper($mode) . "_API_KEY in .env.");
                return self::FAILURE;
            }

            $this->error('Failed to fetch Swappr banks: ' . json_encode($response['data']));
            return self::FAILURE;
        }

        $banks = data_get($response, 'data.data') ?? data_get($response, 'data');

        if (!is_array($banks) || $banks === []) {
            $this->error('Swappr returned no NGN banks in the expected data list.');
            return self::FAILURE;
        }

        $synced = 0;

        foreach ($banks as $bank) {
            if (!is_array($bank)) {
                $this->warn('Skipping a malformed Swappr bank record.');
                continue;
            }

            $code = trim((string) ($bank['cbn_code'] ?? $bank['nip_code'] ?? ''));
            $name = trim((string) ($bank['name'] ?? $bank['bank_name'] ?? ''));

            if ($code === '' || $name === '') {
                $this->warn('Skipping a Swappr bank without a bank code or name.');
                continue;
            }

            Bank::updateOrCreate(
                [
                    'provider' => 'swappr',
                    'country_iso' => 'NG',
                    'currency' => 'NGN',
                    'bank_code' => $code,
                ],
                [
                    'name' => $name,
                    'bank_nibss_code' => $bank['nip_code'] ?? null,
                    'provider_bank_id' => $bank['id'] ?? null,
                    'sort_code' => null,
                    'type' => 'bank',
                ]
            );

            $synced++;
        }

        if ($synced === 0) {
            $this->error('No valid Swappr bank records were found; no rows were changed.');
            return self::FAILURE;
        }

        $this->info("Added or updated {$synced} Swappr banks for NGN. Existing bank rows were not deleted.");

        return self::SUCCESS;
    }
}
