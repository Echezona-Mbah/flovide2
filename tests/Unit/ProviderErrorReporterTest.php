<?php

use App\Services\ProviderErrorReporter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class);

it('logs provider errors and emails configured admins with account context', function () {
    config()->set('mail.admin_email', 'ops@example.com, alerts@example.com');

    Schema::create('admin_notifications', function (Blueprint $table) {
        $table->id();
        $table->string('type')->default('referral_bonus');
        $table->string('title');
        $table->text('body');
        $table->json('data')->nullable();
        $table->timestamp('read_at')->nullable();
        $table->timestamps();
    });

    Log::shouldReceive('critical')
        ->once()
        ->with('[Payment Provider] Operation failed', \Mockery::on(
            fn ($context) => $context['provider'] === 'Swappr'
                && $context['error'] === 'SWAPPR_COLLECTION_NOT_CONFIGURED'
                && $context['account_id'] === 123
        ));

    Mail::shouldReceive('raw')
        ->once()
        ->withArgs(function ($body, $callback) {
            return str_contains($body, 'SWAPPR_COLLECTION_NOT_CONFIGURED')
                && str_contains($body, 'payer@example.com')
                && is_callable($callback);
        });

    app(ProviderErrorReporter::class)->report(
        'Swappr',
        'CAD Interac collection',
        'SWAPPR_COLLECTION_NOT_CONFIGURED',
        [
            'account_id' => 123,
            'payer_email' => 'payer@example.com',
        ]
    );

    $notification = DB::table('admin_notifications')->first();
    expect($notification->type)->toBe('provider_error')
        ->and($notification->title)->toBe('Swappr: CAD Interac collection failed')
        ->and(json_decode($notification->data, true)['payer_email'])->toBe('payer@example.com');
});
