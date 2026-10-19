<?php

namespace Tests\Feature;

use Tests\TestCase;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use App\Services\SomitiService;

class RealDataAuditTest extends TestCase
{
    public function test_real_member_payments_and_due_calculations(): void
    {
        $service = app(SomitiService::class);
        $service->clearCache();

        $expectedMembers = [
            'saiful@upolobdi.org' => [
                'name' => 'Saiful Islam',
                'months_count' => 82,
                'total_amount' => 82000,
                'due_months' => 0,
                'paid_through' => 'October 2026',
            ],
            'rubel@upolobdi.org' => [
                'name' => 'Rubel Mulla',
                'months_count' => 81,
                'total_amount' => 81000,
                'due_months' => 1,
                'paid_through' => 'September 2026',
            ],
            'ferdous@upolobdi.org' => [
                'name' => 'Md Ferdous',
                'months_count' => 77,
                'total_amount' => 77000,
                'due_months' => 5,
                'paid_through' => 'May 2026',
            ],
            'sajib@upolobdi.org' => [
                'name' => 'Sajib Mulla',
                'months_count' => 75,
                'total_amount' => 75000,
                'due_months' => 7,
                'paid_through' => 'March 2026',
            ],
            'doulot@upolobdi.org' => [
                'name' => 'Doulot',
                'months_count' => 75,
                'total_amount' => 75000,
                'due_months' => 7,
                'paid_through' => 'March 2026',
            ],
            'kawser@upolobdi.org' => [
                'name' => 'Kawser',
                'months_count' => 72,
                'total_amount' => 72000,
                'due_months' => 10,
                'paid_through' => 'December 2025',
            ],
        ];

        foreach ($expectedMembers as $email => $expected) {
            $member = $service->getMemberByEmail($email);
            $this->assertNotNull($member, "Member {$email} must exist.");

            $due = $service->calculateDue($member);

            $this->assertEquals(
                $expected['months_count'],
                $due['total_paid_months'],
                "Member {$email} must have exactly {$expected['months_count']} paid months."
            );

            $this->assertEquals(
                $expected['total_amount'],
                $due['total_paid_amount'],
                "Member {$email} must have total paid amount {$expected['total_amount']} BDT (installment + 1,700 admission)."
            );

            $this->assertEquals(
                $expected['due_months'],
                $due['net_due_months'],
                "Member {$email} must have {$expected['due_months']} net due months in October 2026."
            );
        }
    }

    public function test_real_expenses_records(): void
    {
        $service = app(SomitiService::class);
        $service->clearCache();

        $expenses = $service->getExpenses();
        $this->assertCount(6, $expenses, "Exactly 6 real expenses must be recorded.");

        $expectedExpenses = [
            ['title' => 'ইফতার পার্টি', 'amount' => 2680, 'expense_date' => '2025-03-30'],
            ['title' => 'লিচু ক্রয়', 'amount' => 7600, 'expense_date' => '2025-05-23'],
            ['title' => 'মিটিং এ কাচ্চি', 'amount' => 2500, 'expense_date' => '2026-03-23'],
            ['title' => 'লিচু ক্রয়', 'amount' => 3300, 'expense_date' => '2026-05-27'],
            ['title' => 'টি শার্ট', 'amount' => 5200, 'expense_date' => '2026-08-30'],
            ['title' => 'মিটিং হান্ডি', 'amount' => 2090, 'expense_date' => '2026-10-12'],
        ];

        $totalExp = 0;
        foreach ($expectedExpenses as $exp) {
            $found = collect($expenses)->first(function ($e) use ($exp) {
                return $e['title'] === $exp['title']
                    && (int) $e['amount'] === $exp['amount']
                    && $e['expense_date'] === $exp['expense_date'];
            });
            $this->assertNotNull($found, "Expense '{$exp['title']}' of {$exp['amount']} BDT on {$exp['expense_date']} must exist.");
            $totalExp += $exp['amount'];
        }

        $this->assertEquals(23370, $totalExp, "Total expenses must equal 23,370 BDT.");

        $overview = $service->getFinancialOverview();
        $this->assertEquals(462000, $overview['total_somiti_fund'], "Total approved somiti fund must equal 462,000 BDT.");
        $this->assertEquals(23370, $overview['total_expenses'], "Total somiti expenses must equal 23,370 BDT.");
        $this->assertEquals(438630, $overview['net_balance'], "Net somiti balance must equal 438,630 BDT.");
    }
}
