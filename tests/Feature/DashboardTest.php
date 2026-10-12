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

    public function test_payment_submission_without_image_succeeds(): void
    {
        $member = User::findByEmail('kawser@upolobdi.org');
        $this->assertNotNull($member);

        $response = $this->actingAs($member)->post('/payment/submit', [
            'amount' => 1000,
            'months_count' => 1,
            'payment_method' => 'bkash',
            'payment_date' => date('Y-m-d'),
            'reference_number' => 'TEST-NO-IMG-1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Cleanup test payment
        $entry = \Statamic\Facades\Entry::query()->where('collection', 'payments')->where('reference_number', 'TEST-NO-IMG-1234')->first();
        if ($entry) $entry->delete();
    }

    public function test_payment_submission_with_image_succeeds(): void
    {
        $member = User::findByEmail('kawser@upolobdi.org');
        $this->assertNotNull($member);

        $file = \Illuminate\Http\UploadedFile::fake()->image('voucher_slip.jpg');

        $response = $this->actingAs($member)->post('/payment/submit', [
            'amount' => 2000,
            'months_count' => 2,
            'payment_method' => 'nagad',
            'payment_date' => date('Y-m-d'),
            'reference_number' => 'TEST-IMG-5678',
            'proof_image' => $file,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Cleanup test payment
        $entry = \Statamic\Facades\Entry::query()->where('collection', 'payments')->where('reference_number', 'TEST-IMG-5678')->first();
        if ($entry) $entry->delete();
    }

    public function test_committee_members_can_access_due_report(): void
    {
        $committee = ['saiful@upolobdi.org', 'rubel@upolobdi.org', 'sajib@upolobdi.org'];
        foreach ($committee as $email) {
            $user = User::findByEmail($email);
            $response = $this->actingAs($user)->get('/ledger/due-report');
            $response->assertStatus(200);
            $response->assertSee('কর্মকর্তা বকেয়া অডিট প্রতিবেদন');
        }
    }

    public function test_committee_can_create_and_manage_expenses(): void
    {
        $cashier = User::findByEmail('saiful@upolobdi.org');
        $this->assertNotNull($cashier);

        $response = $this->actingAs($cashier)->post('/expense/create', [
            'title' => 'অফিস স্টেশনারি ও খাতা ক্রয় টেস্ট',
            'amount' => 500,
            'expense_date' => date('Y-m-d'),
            'category' => 'অফিস ও প্রশাসনিক',
            'description' => 'টেস্টিং খরচ এন্ট্রি',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Check dashboard shows the expense
        $dashResponse = $this->actingAs($cashier)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('অফিস স্টেশনারি ও খাতা ক্রয় টেস্ট');

        // Cleanup test expense
        $exp = \Statamic\Facades\Entry::query()->where('collection', 'expenses')->where('title', 'অফিস স্টেশনারি ও খাতা ক্রয় টেস্ট')->first();
        if ($exp) $exp->delete();
    }

    public function test_admin_can_reset_member_password(): void
    {
        $superadmin = User::findByEmail('sajib@upolobdi.org');
        $this->assertNotNull($superadmin);

        $response = $this->actingAs($superadmin)->post('/member/reset-password/kawser@upolobdi.org', [
            'new_password' => 'secret1234',
            'new_password_confirmation' => 'secret1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Restore original password
        $targetUser = User::findByEmail('kawser@upolobdi.org');
        if ($targetUser) {
            $targetUser->password('password123');
            $targetUser->save();
        }
    }

    public function test_committee_can_create_and_manage_profits(): void
    {
        $cashier = User::findByEmail('saiful@upolobdi.org');
        $this->assertNotNull($cashier);

        // 1. Regular member cannot create profit
        $member = User::findByEmail('kawser@upolobdi.org');
        $unauthResponse = $this->actingAs($member)->post('/profit/create', [
            'title' => 'অননুমোদিত লভ্যাংশ টেস্ট',
            'source_category' => 'business',
            'profit_type' => 'monthly',
            'amount' => 5000,
            'profit_date' => date('Y-m-d'),
        ]);
        $unauthResponse->assertSessionHasErrors('error');

        // 2. Cashier can create a monthly business profit
        $resMonthly = $this->actingAs($cashier)->post('/profit/create', [
            'title' => 'গ্রোসারি ব্যবসা মাসিক মুনাফা টেস্ট',
            'source_category' => 'business',
            'profit_type' => 'monthly',
            'amount' => 15000,
            'profit_date' => date('Y-m-d'),
            'reference_number' => 'TEST-PRF-MONTHLY-01',
            'description' => 'ব্যবসায়িক বিনিয়োগ থেকে চলতি মাসের শেয়ার মুনাফা',
        ]);
        $resMonthly->assertStatus(302);
        $resMonthly->assertSessionHas('success');

        // 3. Cashier can create a one-time land sale profit
        $resOneTime = $this->actingAs($cashier)->post('/profit/create', [
            'title' => 'পূর্বাচল প্লট বিক্রয় এককালীন মুনাফা টেস্ট',
            'source_category' => 'land',
            'profit_type' => 'one_time',
            'amount' => 50000,
            'profit_date' => date('Y-m-d'),
            'reference_number' => 'TEST-PRF-LAND-02',
            'description' => 'জমি বিক্রয় সম্পন্ন ও সমবায় সমিতির লভ্যাংশ অংশ',
        ]);
        $resOneTime->assertStatus(302);
        $resOneTime->assertSessionHas('success');

        // 4. Verify dashboard displays the profit ledger entries and updated figures
        $dashResponse = $this->actingAs($cashier)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('গ্রোসারি ব্যবসা মাসিক মুনাফা টেস্ট');
        $dashResponse->assertSee('পূর্বাচল প্লট বিক্রয় এককালীন মুনাফা টেস্ট');
        $dashResponse->assertSee('ব্যবসা ও বাণিজ্য');
        $dashResponse->assertSee('জমি ক্রয়-বিক্রয়');
        $dashResponse->assertSee('মাসিক লভ্যাংশ');
        $dashResponse->assertSee('এককালীন লভ্যাংশ');
        $dashResponse->assertSee('65,000'); // 15,000 + 50,000 total profit

        // 5. Test deletion and balance adjustment
        $entry1 = \Statamic\Facades\Entry::query()->where('collection', 'profits')->where('reference_number', 'TEST-PRF-MONTHLY-01')->first();
        $this->assertNotNull($entry1);
        $delResponse = $this->actingAs($cashier)->post('/profit/delete/' . $entry1->id());
        $delResponse->assertStatus(302);
        $delResponse->assertSessionHas('success');

        // Cleanup second entry
        $entry2 = \Statamic\Facades\Entry::query()->where('collection', 'profits')->where('reference_number', 'TEST-PRF-LAND-02')->first();
        if ($entry2) {
            $entry2->delete();
        }

        // Clear statamic cache
        app(\App\Services\SomitiService::class)->clearCache();
    }
}

