<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class GeneralNotification extends BaseDatabaseNotification
{
    public $incrementing = true;   // ✅ Auto-increment IDs
    protected $keyType = 'int';    // ✅ integer IDs
}
