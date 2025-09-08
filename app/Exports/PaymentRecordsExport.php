<?php

namespace App\Exports;

use App\Models\PaymentRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentRecordsExport implements FromCollection, WithHeadings
{

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PaymentRecord::where('personal_id', $this->userId)
            ->select(
                'id',
                'payment_id',
                'name',
                'email',
                'phone',
                'amount',
                'currency',
                'status',
                'reference',
                'created_at'
            ) ->get();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Payment ID',
            'Name',
            'Email',
            'Phone',
            'Amount',
            'Currency',
            'Status',
            'Reference',
            'Date Created'
        ];
    }
}
