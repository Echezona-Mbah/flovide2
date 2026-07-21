<?php

namespace App\Exports;

use App\Models\SubscriptionRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionRecordsExport implements FromCollection, WithHeadings
{
    protected $subscriptionId;

    public function __construct($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function collection()
    {
        return SubscriptionRecord::where('subscription_id', $this->subscriptionId)
            ->get([
                'name',
                'email',
                'phone',
                'amount',
                'currency',
                'status',
                'reference',
                'user_id',
                'subscription_id',
                'start_date',
                'end_date',
                'is_expired'
            ]);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Amount',
            'Currency',
            'Status',
            'Reference',
            'User ID',
            'Subscription ID',
            'Start Date',
            'End Date',
            'Is Expired'
        ];
    }
}
