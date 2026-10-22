<?php

namespace App\Console\Commands;

use App\Models\Beneficia;
use App\Services\BankCodeResolver;
use Illuminate\Console\Command;

class BackfillBeneficiaryFlovideCodes extends Command
{
    protected $signature = 'beneficiaries:backfill-flovide-codes {--currency=}';
    protected $description = 'Fill flovide_bank_code on existing beneficiaries';

    public function handle(BankCodeResolver $resolver)
    {
        $filled = 0;
        $missed = [];

        Beneficia::whereNull('flovide_bank_code')
            ->whereNotNull('bank_code')
            ->when($this->option('currency'), fn ($q, $c) => $q->where('currency', strtoupper($c)))
            ->chunkById(200, function ($rows) use ($resolver, &$filled, &$missed) {
                foreach ($rows as $b) {
                    $code = $resolver->toFlovideCode((string) $b->currency, (string) $b->bank_code, $b->provider);

                    if ($code) {
                        $b->flovide_bank_code = $code;
                        $b->saveQuietly();
                        $filled++;
                    } else {
                        $missed[] = [$b->id, $b->currency, $b->bank, $b->bank_code];
                    }
                }
            });

        $this->info("Filled {$filled} beneficiaries.");

        if ($missed) {
            $this->warn(count($missed) . ' could not be resolved (bank not linked yet):');
            $this->table(['ID', 'Currency', 'Bank', 'Code'], $missed);
        }

        return 0;
    }
}