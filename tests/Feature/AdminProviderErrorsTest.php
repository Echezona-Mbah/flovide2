<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProviderErrorController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminProviderErrorsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('referral_bonus');
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        DB::table('admin_notifications')->insert([
            [
                'type' => 'provider_error',
                'title' => 'Swappr failed',
                'body' => 'SWAPPR_COLLECTION_NOT_CONFIGURED',
                'data' => json_encode(['provider' => 'Swappr', 'account_id' => 22]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'provider_error',
                'title' => 'Blaaiz failed',
                'body' => 'INTERAC_REQUEST_FAILED',
                'data' => json_encode(['provider' => 'Blaaiz']),
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'referral_bonus',
                'title' => 'Referral',
                'body' => 'Not a provider error',
                'data' => null,
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function test_it_lists_provider_error_counts_and_filters_open_incidents(): void
    {
        $view = app(ProviderErrorController::class)->index(Request::create('/admin/provider-errors', 'GET'));

        $this->assertSame('admin.provider_errors', $view->name());
        $this->assertSame(['total' => 2, 'open' => 1, 'resolved' => 1], $view->getData()['counts']);
        $this->assertSame(1, $view->getData()['providerErrors']->total());
        $this->assertSame('Swappr failed', $view->getData()['providerErrors']->first()->title);
    }

    public function test_it_can_mark_a_provider_error_resolved(): void
    {
        $id = DB::table('admin_notifications')->where('title', 'Swappr failed')->value('id');

        app(ProviderErrorController::class)->resolve($id);

        $this->assertNotNull(DB::table('admin_notifications')->where('id', $id)->value('read_at'));
    }
}
