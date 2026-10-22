<?php

namespace App\Console\Commands;

use App\Models\Bank;
use Illuminate\Console\Command;

class LinkFlovideBankCodes extends Command
{
    protected $signature = 'banks:link-flovide-codes {currency} {--base= : provider live today, its codes become the Flovide codes} {--dry-run}';
    protected $description = "Give every provider's banks for a currency one shared Flovide bank code";

    public function handle()
    {
        $currency = strtoupper($this->argument('currency'));
        $base     = strtolower((string) $this->option('base'));
        $dry      = (bool) $this->option('dry-run');

        if (!$base) {
            $this->error('Pass --base=<provider live for this currency today>, e.g. --base=payaza');
            return 1;
        }

        // 1) Base provider: its current code becomes the Flovide code (nothing changes for customers)
        $baseRows = Bank::where('provider', $base)->where('currency', $currency)->get();

        if ($baseRows->isEmpty()) {
            $this->error("No {$base} banks found for {$currency}.");
            return 1;
        }

        foreach ($baseRows as $row) {
            if (!$row->flovide_code && !$dry) {
                $row->update(['flovide_code' => $row->bank_code]);
            }
        }
        $this->info("{$base}: {$baseRows->count()} banks now use their own code as the Flovide code.");

        // Base banks whose normalised name is unique (ambiguous names are left for manual linking)
        $baseByName = $baseRows
            ->groupBy(fn ($b) => $this->normalise($b->name))
            ->filter(fn ($g) => $g->count() === 1)
            ->map->first();

        // 2) Every other provider: link by name
        $others = Bank::where('currency', $currency)
            ->where('provider', '!=', $base)
            ->whereNull('flovide_code')
            ->get();

        $nameCounts = $others->groupBy(fn ($b) => $b->provider . '|' . $this->normalise($b->name))->map->count();

        $linked    = 0;
        $unmatched = [];

        foreach ($others as $row) {
            $key   = $this->normalise($row->name);
            $match = $baseByName[$key] ?? null;
            $dupe  = ($nameCounts[$row->provider . '|' . $key] ?? 0) > 1;

            if ($match && !$dupe) {
                if (!$dry) {
                    $row->update(['flovide_code' => $match->flovide_code ?? $match->bank_code]);
                }
                $linked++;
            } else {
                $unmatched[] = [$row->provider, $row->bank_code, $row->name];
            }
        }

        $this->info(($dry ? '[dry run] ' : '') . "Linked {$linked} other-provider banks.");

        if ($unmatched) {
            $this->warn(count($unmatched) . ' need a manual link:');
            $this->table(['Provider', 'Provider code', 'Name'], $unmatched);
        }

        return 0;
    }

    protected function normalise(string $name): string
    {
        $n = strtolower(str_replace('&', 'and', $name));
        $n = preg_replace('/\b(plc|limited|ltd|nigeria|nig|bank|banks|mfb|microfinance|mortgage|of|the)\b/', ' ', $n);

        return preg_replace('/[^a-z0-9]+/', '', $n);
    }
}