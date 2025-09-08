<?php

namespace App\Exports;

use App\Models\RemitaPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RemitaRecordsExport implements FromCollection, WithHeadings
{
    protected $remitaId;

    public function __construct($remitaId)
    {
        $this->remitaId = $remitaId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RemitaPayment::where('remita_id', $this->remitaId)
            ->select(
                'id',
                'remita_id',
                'name',
                'email',
                'transaction_reference',
                'amount_paid',
                'currency',
                'channel',
                'status',
                'response_code',
                'response_message',
                'paid_at',
                'created_at'
            ) ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Remita ID',
            'Name',
            'Email',
            'Transaction Reference',
            'Amount Paid',
            'Currency',
            'Channel',
            'Status',
            'Response Code',
            'Response Message',
            'Paid At',
            'Created At'
        ];
    }
}
