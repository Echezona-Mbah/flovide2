<?php

namespace App\Console\Commands;

use App\Models\Bank;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateFlovideBankCodes extends Command
{
    protected $signature = 'banks:generate-flovide-codes {currency} {--dry-run}';

    protected $description = 'Generate numeric Flovide bank codes';

    public function handle(): int
    {
        $currency = strtoupper($this->argument('currency'));
        $dryRun = (bool) $this->option('dry-run');

        // Load existing codes too, so a provider row can reuse its bank's code.
        $banks = Bank::where('currency', $currency)->get();

        if ($banks->isEmpty()) {
            $this->error("No banks found for {$currency}.");
            return self::FAILURE;
        }

        $groups = $banks->groupBy(fn (Bank $bank) => implode('|', [
            strtoupper((string) $bank->country_iso),
            strtolower((string) $bank->type),
            $this->normalise($bank->name),
        ]));

        $usedCodes = Bank::whereNotNull('flovide_code')
            ->pluck('flovide_code')
            ->map(fn ($code) => (string) $code)
            ->all();

        $numericCodes = array_filter($usedCodes, fn ($code) => ctype_digit($code));
        $nextNumber = $numericCodes ? max(array_map('intval', $numericCodes)) + 1 : 1;

        $updates = [];
        $conflicts = [];

        foreach ($groups as $group) {
            $existingCodes = $group->pluck('flovide_code')
                ->filter()
                ->unique()
                ->values();

            if ($existingCodes->count() > 1) {
                $conflicts[] = [
                    $group->first()->name,
                    $existingCodes->implode(', '),
                    $group->pluck('provider')->unique()->implode(', '),
                ];
                continue;
            }

            if ($existingCodes->count() === 1) {
                $code = (string) $existingCodes->first();
            } else {
                do {
                    $code = str_pad((string) $nextNumber++, 3, '0', STR_PAD_LEFT);
                } while (in_array($code, $usedCodes, true));

                $usedCodes[] = $code;
            }

            $missingIds = $group
                ->filter(fn (Bank $bank) => blank($bank->flovide_code))
                ->pluck('id')
                ->all();

            if ($missingIds) {
                $updates[] = [
                    'ids' => $missingIds,
                    'code' => $code,
                    'name' => $group->first()->name,
                    'providers' => $group->pluck('provider')->unique()->implode(', '),
                ];
            }
        }

        $this->table(
            ['Flovide code', 'Bank', 'Providers', 'Rows'],
            array_map(fn ($update) => [
                $update['code'],
                $update['name'],
                $update['providers'],
                count($update['ids']),
            ], $updates)
        );

        if ($conflicts) {
            $this->warn('These banks already have conflicting Flovide codes; they were skipped.');
            $this->table(['Bank', 'Existing codes', 'Providers'], $conflicts);
        }

        if ($dryRun) {
            $this->info(count($updates) . ' code assignments would be saved. No database changes made.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($updates) {
            foreach ($updates as $update) {
                Bank::whereIn('id', $update['ids'])
                    ->whereNull('flovide_code')
                    ->update(['flovide_code' => $update['code']]);
            }
        });

        $this->info(count($updates) . ' Flovide code assignments saved.');

        return self::SUCCESS;
    }

    protected function normalise(string $name): string
    {
        $name = strtolower(str_replace('&', 'and', trim($name)));
        $name = preg_replace('/\b(plc|limited|ltd)\b/', ' ', $name);

        return preg_replace('/[^a-z0-9]+/', '', $name);
    }
}