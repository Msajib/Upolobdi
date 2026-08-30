<?php

namespace Tests\Feature;

use Tests\TestCase;
use Statamic\Facades\User;

class DashboardTest extends TestCase
{
    public function test_all_users_can_access_dashboard_without_errors(): void
    {
        $emails = [
            'sajib@upolobdi.org',
            'rubel@upolobdi.org',
            'saiful@upolobdi.org',
            'kawser@upolobdi.org',
            'doulot@upolobdi.org',
            'ferdous@upolobdi.org'
        ];

        foreach ($emails as $email) {
            $user = User::findByEmail($email);
            $this->assertNotNull($user, "User {$email} must exist.");

            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);
            
            if ($email === 'saiful@upolobdi.org') {
                $response->assertViewHas('userRole', 'cashier');
            }
        }
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_ledger_export_download_works_for_cashier_and_admin(): void
    {
        $cashier = User::findByEmail('saiful@upolobdi.org');
        $this->assertNotNull($cashier);

        $response = $this->actingAs($cashier)->get('/ledger/export');
        $response->assertStatus(200);
        $response->assertSee('উপলব্ধি সমবায় সমিতি');
    }
}

