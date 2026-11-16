<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminLoginSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('email');
            $table->string('ip_address');
            $table->text('device');
            $table->boolean('success')->default(false);
            $table->timestamp('attempted_at');
            $table->timestamps();
        });
    }

    public function test_repeated_bad_passwords_do_not_permanently_lock_the_admin_account(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('CorrectPassword123!'),
        ]);

        foreach (range(1, 3) as $attempt) {
            $this->from('/admin/login')
                ->post('/admin/login', [
                    'email' => 'admin@example.com',
                    'password' => 'incorrect-password',
                ])
                ->assertRedirect('/admin/login');
        }

        $admin->refresh();
        $this->assertSame(3, $admin->failed_attempts);
        $this->assertNull($admin->locked_until);
        $this->assertSame(3, DB::table('admin_login_logs')->where('success', false)->count());
    }

    public function test_successful_login_rotates_the_session_and_clears_failed_attempts(): void
    {
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('CorrectPassword123!'),
            'failed_attempts' => 2,
        ]);

        $this->post('/admin/login', [
            'email' => 'ADMIN@example.com',
            'password' => 'CorrectPassword123!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertSame(0, $admin->fresh()->failed_attempts);
        $this->assertSame(1, DB::table('admin_login_logs')->where('success', true)->count());
    }
}
