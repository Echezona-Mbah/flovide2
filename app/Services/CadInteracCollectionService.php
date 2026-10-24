<?php

namespace App\Services;

use App\Models\PaymentProvider;
use Illuminate\Support\Str;

class CadInteracCollectionService
{
    public function resolveProvider(): ?PaymentProvider
    {
        return app(ProviderRoutingService::class)->resolveForCollection('CAD');
    }

    public function swapprRecipientEmail(): ?string
    {
        $email = trim((string) config('services.swappr.cad_interac_email'));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    public function newReference(string $prefix = 'SWP'): string
    {
        return $prefix . '-' . strtoupper((string) Str::uuid());
    }
}
