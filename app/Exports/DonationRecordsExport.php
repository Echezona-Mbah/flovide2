<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\donations;

class DonationRecordsExport implements FromCollection
{

    protected int $donationId;

    public function __construct(int $donationId)
    {
        $this->donationId = $donationId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $user = Auth::user();

        $donation = donations::with('records')
            ->where('id', $this->donationId)
            ->where('user_id', $user->id)
            ->first();

        if (!$donation) {
            // Let the HTTP layer handle this as a 404
            throw (new ModelNotFoundException)->setModel(donations::class, $this->donationId);
        }

        // Return a collection of arrays suitable for Excel export
        return $donation->records->map(function ($record) {
            return [
                'id' => $record->id,
                'name' => $record->name,
                'email' => $record->email,
                'phone' => $record->phone,
                'amount' => $record->amount,
                'currency' => $record->currency,
                'status' => $record->status,
                'reference' => $record->reference,
                'created_at' => $record->created_at->toDateTimeString(),
            ];
        });
    }
}
