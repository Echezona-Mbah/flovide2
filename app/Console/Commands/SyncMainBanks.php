<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Models\MainBank;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncMainBanks extends Command
{
    protected $signature = 'banks:sync-mainbanks {--currency= : only synchronize banks for this currency} {--provider= : only synchronize banks that have a row for this provider} {--dry-run}';

    protected $description = 'Create the main bank registry and Flovide codes from all provider bank lists';

    public function handle(): int
    {
        $currency = strtoupper((string) $this->option('currency'));
        $provider = strtolower((string) $this->option('provider'));

        $banks = Bank::query()
            ->whereNotNull('currency')
            ->where('currency', '!=', '')
            ->when($currency !== '', fn ($query) => $query->where('currency', $currency))
            ->get();

        if ($banks->isEmpty()) {
            $message = $currency === ''
                ? 'No provider banks with a currency were found.'
                : "No provider banks found for {$currency}.";
            $this->error($message);

            return self::FAILURE;
        }

        $mainBanks = MainBank::all()->keyBy(fn (MainBank $bank) => $this->identityKey(
            $bank->currency,
            $bank->country_iso,
            $bank->type,
            $bank->normalized_name
        ));

        $groups = $banks->groupBy(fn (Bank $bank) => $this->identityKey(
            (string) $bank->currency,
            (string) $bank->country_iso,
            (string) ($bank->type ?: 'bank'),
            $this->normalizeName((string) $bank->name)
        ));

        $codeOwners = [];
        foreach ($groups as $identity => $group) {
            $codes = $group->pluck('flovide_code')
                ->filter(fn ($code) => filled($code))
                ->map(fn ($code) => (string) $code)
                ->unique();
            $mainBank = $mainBanks->get($identity);

            if ($mainBank) {
                $codes->push((string) $mainBank->flovide_code);
            }

            foreach ($codes->unique() as $code) {
                $codeOwners[strtoupper((string) $group->first()->currency).'|'.$code][$identity] = true;
            }
        }

        $usedCodes = Bank::whereNotNull('flovide_code')
            ->pluck('flovide_code')
            ->merge(MainBank::whereNotNull('flovide_code')->pluck('flovide_code'))
            ->map(fn ($code) => (string) $code)
            ->unique()
            ->values()
            ->all();

        $numericCodes = array_filter($usedCodes, fn (string $code) => ctype_digit($code));
        $nextNumber = $numericCodes ? max(array_map('intval', $numericCodes)) + 1 : 1;
        $updates = [];
        $conflicts = [];

        foreach ($groups as $identity => $group) {
            if ($provider !== '' && ! $group->contains(
                fn (Bank $bank) => strtolower((string) $bank->provider) === $provider
            )) {
                continue;
            }

            $mainBank = $mainBanks->get($identity);
            $existingCodes = $group->pluck('flovide_code')
                ->filter(fn ($code) => filled($code))
                ->map(fn ($code) => (string) $code)
                ->merge($mainBank ? [(string) $mainBank->flovide_code] : [])
                ->unique()
                ->values();

            if ($existingCodes->count() > 1) {
                $conflicts[] = [
                    $group->first()->currency,
                    $group->first()->name,
                    $existingCodes->implode(', '),
                ];

                continue;
            }

            $existingCode = $existingCodes->first();
            if ($existingCode !== null) {
                $codeOwnerCount = count(
                    $codeOwners[strtoupper((string) $group->first()->currency).'|'.$existingCode] ?? []
                );
                $isScopedProviderGroup = $provider !== '' && $group->contains(
                    fn (Bank $bank) => strtolower((string) $bank->provider) === $provider
                );
                $codeIsUsedByAnotherMainBank = MainBank::where('currency', strtoupper((string) $group->first()->currency))
                    ->where('flovide_code', $existingCode)
                    ->when($mainBank, fn ($query) => $query->where('id', '!=', $mainBank->id))
                    ->exists();

                if ($codeOwnerCount > 1 && (! $isScopedProviderGroup || $codeIsUsedByAnotherMainBank)) {
                    $conflicts[] = [
                        $group->first()->currency,
                        $group->first()->name,
                        $existingCode,
                    ];

                    continue;
                }
            }

            if ($existingCodes->count() === 1) {
                $code = $existingCodes->first();
            } else {
                do {
                    $code = str_pad((string) $nextNumber++, 3, '0', STR_PAD_LEFT);
                } while (in_array($code, $usedCodes, true));

                $usedCodes[] = $code;
            }

            $updates[] = [
                'identity' => $identity,
                'main_bank' => $mainBank,
                'banks' => $group,
                'code' => $code,
                'name' => $group->first()->name,
                'country_iso' => strtoupper((string) $group->first()->country_iso),
                'currency' => strtoupper((string) $group->first()->currency),
                'type' => strtolower((string) ($group->first()->type ?: 'bank')),
                'normalized_name' => $this->normalizeName((string) $group->first()->name),
            ];
        }

        $this->table(
            ['Currency', 'Flovide code', 'Bank', 'Provider rows'],
            array_map(fn (array $update) => [
                $update['currency'],
                $update['code'],
                $update['name'],
                $update['banks']->count(),
            ], $updates)
        );

        if ($conflicts) {
            $this->warn('Banks with conflicting existing Flovide codes were skipped.');
            $this->table(['Currency', 'Bank', 'Existing codes'], $conflicts);
        }

        if ($this->option('dry-run')) {
            $this->info(count($updates).' main bank records would be synchronized. No database changes made.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($updates): void {
            foreach ($updates as $update) {
                if (! $update['main_bank']) {
                    MainBank::create([
                        'name' => $update['name'],
                        'country_iso' => $update['country_iso'],
                        'currency' => $update['currency'],
                        'type' => $update['type'],
                        'normalized_name' => $update['normalized_name'],
                        'flovide_code' => $update['code'],
                    ]);
                }

                $update['banks']
                    ->filter(fn (Bank $bank) => blank($bank->flovide_code))
                    ->each(fn (Bank $bank) => Bank::whereKey($bank->getKey())
                        ->where(fn ($query) => $query->whereNull('flovide_code')->orWhere('flovide_code', ''))
                        ->update(['flovide_code' => $update['code']]));
            }
        });

        $this->info(count($updates).' main bank records synchronized.');

        return self::SUCCESS;
    }

    private function identityKey(string $currency, string $countryIso, string $type, string $normalizedName): string
    {
        return implode('|', [
            strtoupper($currency),
            strtoupper($countryIso),
            strtolower($type ?: 'bank'),
            $normalizedName,
        ]);
    }

    private function normalizeName(string $name): string
    {
        $name = strtolower(str_replace('&', 'and', trim($name)));
        $name = preg_replace('/\b(plc|limited|ltd)\b/', ' ', $name);

        return preg_replace('/[^a-z0-9]+/', '', $name);
    }
}
