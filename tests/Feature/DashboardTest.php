<?php

namespace Tests\Feature;

use Tests\TestCase;
use Statamic\Facades\User;
use Statamic\Facades\Entry;

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
            
            if ($email === 'rubel@upolobdi.org') {
                $response->assertViewHas('userRole', 'cashier');
            } elseif ($email === 'kawser@upolobdi.org') {
                $response->assertViewHas('userRole', 'admin');
            } elseif ($email === 'sajib@upolobdi.org') {
                $response->assertViewHas('userRole', 'superadmin');
            }
        }
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('সমিতির বর্তমান কার্যকর নীতিমালা');
    }

    public function test_16_rules_are_loaded(): void
    {
        $rules = Entry::query()->where('collection', 'rules')->get();
        $this->assertCount(16, $rules, "Must have exactly 16 rules from the constitution images.");
    }

    public function test_admission_fees_seeded(): void
    {
        $payments = Entry::query()
            ->where('collection', 'payments')
            ->get()
            ->filter(fn($e) => (int)$e->get('amount') === 1700 && str_starts_with($e->get('payment_date') ?? '', '2020-01-20'));
        $this->assertCount(6, $payments, "All 6 founding members must have initial 1,700 BDT payment on 2020-01-20.");
    }

    public function test_payment_submission_without_image_succeeds(): void
    {
        $member = User::findByEmail('saiful@upolobdi.org');
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
        $entry = Entry::query()->where('collection', 'payments')->where('reference_number', 'TEST-NO-IMG-1234')->first();
        if ($entry) $entry->delete();
    }

    public function test_payment_submission_with_image_succeeds(): void
    {
        $member = User::findByEmail('saiful@upolobdi.org');
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
        $entry = Entry::query()->where('collection', 'payments')->where('reference_number', 'TEST-IMG-5678')->first();
        if ($entry) $entry->delete();
    }

    public function test_committee_members_can_access_due_report(): void
    {
        $committee = ['rubel@upolobdi.org', 'kawser@upolobdi.org', 'sajib@upolobdi.org'];
        foreach ($committee as $email) {
            $user = User::findByEmail($email);
            $response = $this->actingAs($user)->get('/ledger/due-report');
            $response->assertStatus(200);
            $response->assertSee('কর্মকর্তা বকেয়া অডিট প্রতিবেদন');
        }
    }

    public function test_committee_can_create_and_manage_expenses(): void
    {
        $cashier = User::findByEmail('rubel@upolobdi.org');
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
        $exp = Entry::query()->where('collection', 'expenses')->where('title', 'অফিস স্টেশনারি ও খাতা ক্রয় টেস্ট')->first();
        if ($exp) $exp->delete();
    }

    public function test_admin_can_reset_member_password(): void
    {
        $superadmin = User::findByEmail('sajib@upolobdi.org');
        $this->assertNotNull($superadmin);

        $response = $this->actingAs($superadmin)->post('/member/reset-password/ferdous@upolobdi.org', [
            'new_password' => 'secret1234',
            'new_password_confirmation' => 'secret1234',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Restore original password
        $targetUser = User::findByEmail('ferdous@upolobdi.org');
        if ($targetUser) {
            $targetUser->password('password123');
            $targetUser->save();
        }
    }

    public function test_committee_can_create_and_manage_profits(): void
    {
        $cashier = User::findByEmail('rubel@upolobdi.org');
        $this->assertNotNull($cashier);

        // 1. Regular member cannot create profit
        $member = User::findByEmail('saiful@upolobdi.org');
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

        // 5. Test deletion and balance adjustment
        $entry1 = Entry::query()->where('collection', 'profits')->where('reference_number', 'TEST-PRF-MONTHLY-01')->first();
        $this->assertNotNull($entry1);
        $delResponse = $this->actingAs($cashier)->post('/profit/delete/' . $entry1->id());
        $delResponse->assertStatus(302);
        $delResponse->assertSessionHas('success');

        // Cleanup second entry
        $entry2 = Entry::query()->where('collection', 'profits')->where('reference_number', 'TEST-PRF-LAND-02')->first();
        if ($entry2) {
            $entry2->delete();
        }

        // Clear statamic cache
        app(\App\Services\SomitiService::class)->clearCache();
    }

    public function test_meeting_decision_lifecycle_and_permissions(): void
    {
        $president = User::findByEmail('sajib@upolobdi.org');
        $member = User::findByEmail('saiful@upolobdi.org');

        // 1. Regular member cannot create decision
        $unauth = $this->actingAs($member)->post('/decision/create', [
            'title' => 'অবৈধ সিদ্ধান্ত',
            'meeting_date' => date('Y-m-d'),
            'decision_text' => 'মেম্বার কর্তৃক সিদ্ধান্ত তৈরির চেষ্টা',
        ]);
        $unauth->assertSessionHasErrors('error');

        // 2. President can create decision
        $response = $this->actingAs($president)->post('/decision/create', [
            'title' => '২০২৬ সালের ৪র্থ কার্যনির্বাহী সভার রেজুলেশন টেস্ট',
            'meeting_date' => date('Y-m-d'),
            'decision_number' => 'TEST-RES-2026-99',
            'attendees' => 'সকল সদস্য উপস্থিত',
            'decision_text' => 'সর্বসম্মতিক্রমে নতুন প্রকল্প গ্রহণের সিদ্ধান্ত অনুমোদন করা হলো।',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 3. Verify in dashboard for member
        $memberDash = $this->actingAs($member)->get('/dashboard');
        $memberDash->assertStatus(200);
        $memberDash->assertSee('২০২৬ সালের ৪র্থ কার্যনির্বাহী সভার রেজুলেশন টেস্ট');

        // 4. Verify NOT on public landing page per user requirement (only dashboard)
        $landing = $this->get('/');
        $landing->assertStatus(200);
        $landing->assertDontSee('২০২৬ সালের ৪র্থ কার্যনির্বাহী সভার রেজুলেশন টেস্ট');

        // Cleanup test decision
        $dec = Entry::query()->where('collection', 'decisions')->where('title', '২০২৬ সালের ৪র্থ কার্যনির্বাহী সভার রেজুলেশন টেস্ট')->first();
        if ($dec) {
            $dec->delete();
        }
        app(\App\Services\SomitiService::class)->clearCache();
    }

    public function test_announcement_requires_end_date_and_expires(): void
    {
        $president = User::findByEmail('sajib@upolobdi.org');

        // 1. Missing end_date should fail validation
        $invalid = $this->actingAs($president)->post('/announcement/create', [
            'title_bn' => 'শেষ তারিখ ছাড়া নোটিশ',
            'body_bn' => 'বিবরণ',
        ]);
        $invalid->assertSessionHasErrors('end_date');

        // 2. Valid announcement creation with future end_date
        $valid = $this->actingAs($president)->post('/announcement/create', [
            'title_bn' => 'ভবিষ্যতের জরুরি নোটিশ টেস্ট',
            'body_bn' => 'এই নোটিশটি দৃশ্যমান থাকবে',
            'announcement_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+10 days')),
        ]);
        $valid->assertStatus(302);
        $valid->assertSessionHas('success');

        // Verify it appears on landing page
        $landing = $this->get('/');
        $landing->assertSee('ভবিষ্যতের জরুরি নোটিশ টেস্ট');

        // Cleanup
        $ann = Entry::query()->where('collection', 'announcements')->where('title_bn', 'ভবিষ্যতের জরুরি নোটিশ টেস্ট')->first();
        if ($ann) {
            $ann->delete();
        }
        app(\App\Services\SomitiService::class)->clearCache();
    }

    public function test_president_can_update_member_details(): void
    {
        $president = User::findByEmail('sajib@upolobdi.org');
        $member = User::findByEmail('ferdous@upolobdi.org');

        // Regular member cannot update user details
        $unauth = $this->actingAs($member)->post('/member/update/ferdous@upolobdi.org', [
            'name' => 'Ferdous Test',
            'role' => 'member',
        ]);
        $unauth->assertSessionHasErrors('error');

        // President can update user details
        $response = $this->actingAs($president)->post('/member/update/ferdous@upolobdi.org', [
            'name' => 'Md Ferdous',
            'bangla_name' => 'মোঃ ফেরদৌস',
            'phone' => '01700112233',
            'designation' => 'কার্যনির্বাহী সদস্য',
            'role' => 'member',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $updated = User::findByEmail('ferdous@upolobdi.org');
        $this->assertEquals('Md Ferdous', $updated->get('name'));
    }

    public function test_all_users_can_download_rules_with_receipt_design(): void
    {
        $member = User::findByEmail('saiful@upolobdi.org');
        $response = $this->actingAs($member)->get('/rules/download');
        $response->assertStatus(200);
        $response->assertSee('সমিতির কোনো টাকা সদস্য কেউ হাওলাদ নিতে পারবে না');
        $response->assertSee('অফিসিয়াল গঠনতন্ত্র ও নীতিমালা');
    }

    public function test_all_users_can_download_members_summary(): void
    {
        $member = User::findByEmail('doulot@upolobdi.org');
        $response = $this->actingAs($member)->get('/ledger/members-summary');
        $response->assertStatus(200);
        $response->assertSee('সদস্য অডিট খতিয়ান বিবরণী');
        $response->assertSee('মোট সংগৃহীত কিস্তি');
    }

    public function test_all_users_can_download_full_financial_audit(): void
    {
        $member = User::findByEmail('rubel@upolobdi.org');
        $response = $this->actingAs($member)->get('/finance/full-audit');
        $response->assertStatus(200);
        $response->assertSee('সমন্বিত আর্থিক অডিট');
        $response->assertSee('সমিতির নীট বর্তমান তহবিল');
    }

    public function test_president_can_create_and_delete_event_with_image(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $president = User::findByEmail('sajib@upolobdi.org');

        $image = \Illuminate\Http\UploadedFile::fake()->image('event_test.jpg', 600, 400);

        $response = $this->actingAs($president)->post('/event/create', [
            'title_bn' => 'বার্ষিক মিলনমেলা ও ফ্যামিলি ডে ২০২৬',
            'title' => 'Annual Meetup and Family Day 2026',
            'event_date' => '2026-11-20',
            'location' => 'গাজীপুর রিসোর্ট, ঢাকা',
            'description_bn' => 'সমিতির বার্ষিক সাধারণ সভা ও পিকনিক অনুষ্ঠিত হবে।',
            'is_highlighted' => '1',
            'is_masonry_selected' => '1',
            'image_file' => $image,
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $eventEntry = Entry::query()->where('collection', 'events')->where('title_bn', 'বার্ষিক মিলনমেলা ও ফ্যামিলি ডে ২০২৬')->first();
        $this->assertNotNull($eventEntry);
        $this->assertStringContainsString('/uploads/events/', $eventEntry->get('image'));

        // Delete event
        $delResponse = $this->actingAs($president)->post("/event/delete/{$eventEntry->id()}");
        $delResponse->assertStatus(302);
        $delResponse->assertSessionHas('info');

        $deleted = Entry::find($eventEntry->id());
        $this->assertNull($deleted);
    }

    public function test_president_can_create_and_delete_meeting_decision(): void
    {
        $president = User::findByEmail('sajib@upolobdi.org');

        $response = $this->actingAs($president)->post('/decision/create', [
            'title' => 'বিশেষ সাধারণ সভার সিদ্ধান্ত - নতুন বিনিয়োগ প্রকল্প অনুমোদন',
            'meeting_date' => '2026-10-15',
            'decision_number' => 'RES-2026-TEST-99',
            'attendees' => 'সভাপতি সজিব সহ সকল ৬ জন সদস্য',
            'decision_text' => 'সভায় সর্বসম্মতিক্রমে নতুন প্রকল্প বাস্তবায়নের অনুমোদন দেওয়া হল।',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $decEntry = Entry::query()->where('collection', 'decisions')->where('decision_number', 'RES-2026-TEST-99')->first();
        $this->assertNotNull($decEntry);

        // Delete decision
        $delResponse = $this->actingAs($president)->post("/decision/delete/{$decEntry->id()}");
        $delResponse->assertStatus(302);
        $delResponse->assertSessionHas('info');

        $deleted = Entry::find($decEntry->id());
        $this->assertNull($deleted);
    }

    public function test_dashboard_renders_all_modals_and_js_for_president(): void
    {
        $president = User::findByEmail('sajib@upolobdi.org');
        $response = $this->actingAs($president)->get('/dashboard');
        $response->assertStatus(200);

        // Verify buttons and modals are present
        $response->assertSee('id="addEventModal"', false);
        $response->assertSee('id="decisionModal"', false);
        $response->assertSee('id="expenseModal"', false);
        $response->assertSee('id="profitModal"', false);
        $response->assertSee('id="editMemberModal"', false);
        $response->assertSee('openAddEventModal', false);
        $response->assertSee('openDecisionModal', false);
        $response->assertSee('openExpenseModal', false);
        $response->assertSee('openProfitModal', false);
    }
}

