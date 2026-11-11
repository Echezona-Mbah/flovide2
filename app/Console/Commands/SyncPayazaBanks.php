<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Services\PayazaService;
use Illuminate\Console\Command;

class SyncPayazaBanks extends Command
{
    protected $signature = 'payaza:sync-banks {currency=UGX} {--dry-run}';

    protected $description = 'Add or update Payaza banks in the local bank list without deleting existing rows';

    public function handle(PayazaService $payaza): int
    {
        $currency = strtoupper((string) $this->argument('currency'));
        $response = $payaza->getBanks($currency);

        if (! ($response['success'] ?? false)) {
            $this->error('Failed to fetch Payaza banks: '.json_encode($response['data'] ?? $response['message'] ?? null));

            return self::FAILURE;
        }

        $banks = data_get($response, 'data.data');

        if (! is_array($banks) || $banks === []) {
            $this->error("Payaza returned no {$currency} banks in the expected data list.");

            return self::FAILURE;
        }

        $synced = 0;
        $skipped = 0;
        $dryRun = (bool) $this->option('dry-run');

        foreach ($banks as $bank) {
            if (! is_array($bank)) {
                $this->warn('Skipping a malformed Payaza bank record.');
                $skipped++;

                continue;
            }

            $code = trim((string) ($bank['code'] ?? ''));
            $name = trim((string) ($bank['name'] ?? ''));

            if ($code === '' || $name === '') {
                $this->warn('Skipping a Payaza bank without a code or name.');
                $skipped++;

                continue;
            }

            if ($dryRun) {
                $this->line("Would sync {$name} ({$code}).");
            } else {
                $values = [
                    'name' => $name,
                    'country_iso' => $this->countryIso($currency, $bank['country_code'] ?? null),
                    'type' => strtolower((string) ($bank['type'] ?? '')) === 'mobile_money'
                        ? 'mobile_money'
                        : 'bank',
                ];

                if (isset($bank['id'])) {
                    $values['provider_bank_id'] = (string) $bank['id'];
                }

                if (filled($bank['sort_code'] ?? null)) {
                    $values['sort_code'] = $bank['sort_code'];
                }

                Bank::updateOrCreate(
                    [
                        'provider' => 'payaza',
                        'currency' => $currency,
                        'bank_code' => $code,
                    ],
                    $values
                );
            }

            $synced++;
        }

        if ($synced === 0) {
            $this->error('No valid Payaza bank records were found; no rows were changed.');

            return self::FAILURE;
        }

        $action = $dryRun ? 'would be added or updated' : 'added or updated';
        $this->info("{$synced} Payaza banks for {$currency} {$action}. Existing bank rows were not deleted.");

        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} malformed Payaza records.");
        }

        return self::SUCCESS;
    }

    private function countryIso(string $currency, mixed $providerCountryCode): ?string
    {
        if ($currency === 'UGX') {
            return 'UG';
        }

        $countryCode = strtoupper(trim((string) $providerCountryCode));

        return $countryCode === '' ? null : substr($countryCode, 0, 2);
    }
}
