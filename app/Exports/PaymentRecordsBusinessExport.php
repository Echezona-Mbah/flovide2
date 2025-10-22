<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Payments;

class PaymentRecordsBusinessExport implements FromCollection
{
    protected int $paymentId;

    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $payment = Payments::with('records')
            ->where('id', $this->paymentId)
            ->where('user_id', $user->id)
            ->first();

        if (!$payment) {
            // Let the HTTP layer handle this as a 404
            throw (new ModelNotFoundException)->setModel(Payments::class, $this->paymentId);
        }

        // Return a collection of arrays suitable for Excel export
        return $payment->records->map(function ($record) {
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
