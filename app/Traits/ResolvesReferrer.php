<?php

namespace App\Traits;

use App\Models\Personal;
use App\Models\User;

trait ResolvesReferrer
{
    /**
     * Find the referrer account (personal or business) by ID.
     * $referredAccountType tells us which table to check first, since
     * referred_by is only ever populated from a same-type lookup during
     * registration (Personal → Personal, User → User).
     *
     * Returns null if no referrer, or an array
     * ['model' => Personal|User, 'type' => 'personal'|'business'].
     */
    public function resolveReferrerById(?int $referrerId, string $referredAccountType): ?array
    {
        if (!$referrerId) {
            return null;
        }

        if ($referredAccountType === 'personal') {
            if ($personal = Personal::find($referrerId)) {
                return ['model' => $personal, 'type' => 'personal'];
            }
            return null;
        }

        if ($business = User::find($referrerId)) {
            return ['model' => $business, 'type' => 'business'];
        }

        return null;
    }
}