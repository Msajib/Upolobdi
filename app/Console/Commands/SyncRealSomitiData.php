<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use App\Services\SomitiService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SyncRealSomitiData extends Command
{
    protected $signature = 'somiti:sync-real-data';
    protected $description = 'Sync real member payment records and official somiti expense records';

    public function handle(SomitiService $somitiService)
    {
        $this->info('Starting sync of real payment and expense records...');

        // 1. Clean existing installment payments while preserving initial 1,700 BDT admission fees
        $existingInstallments = Entry::query()
            ->where('collection', 'payments')
            ->get()
            ->filter(fn($e) => !str_starts_with($e->slug(), 'admission-'));

        foreach ($existingInstallments as $oldEntry) {
            $oldEntry->delete();
        }
        $this->info('Cleaned old installment records.');

        // 2. Member Payment Records
        $payments = [
            [
                'slug' => 'pay-saiful-oct2026',
                'title' => 'নিয়মিত মাসিক কিস্তি - সাইফুল ইসলাম (৮২ মাস)',
                'email' => 'saiful@upolobdi.org',
                'amount' => 80300,
                'months_count' => 82,
                'payment_months' => 'জানুয়ারি ২০২০ - অক্টোবর ২০২৬ (৮২ মাস)',
                'payment_date' => '2026-10-05',
                'reference_number' => 'USS-REF-SAIFUL-82M',
                'receipt_number' => 'USS-REC-2026-OCT82',
                'remarks' => 'সাইফুল ইসলাম (USS-003): ৮২ মাসের কিস্তি (৮২,০০০ - ১,৭০০ ভর্তি ফি = ৮০,৩০০ টাকা পরিশোধিত)',
            ],
            [
                'slug' => 'pay-rubel-sep2026',
                'title' => 'নিয়মিত মাসিক কিস্তি - রুবেল মোল্লা (৮১ মাস)',
                'email' => 'rubel@upolobdi.org',
                'amount' => 79300,
                'months_count' => 81,
                'payment_months' => 'জানুয়ারি ২০২০ - সেপ্টেম্বর ২০২৬ (৮১ মাস)',
                'payment_date' => '2026-09-05',
                'reference_number' => 'USS-REF-RUBEL-81M',
                'receipt_number' => 'USS-REC-2026-SEP81',
                'remarks' => 'রুবেল মোল্লা (USS-002): ৮১ মাসের কিস্তি (৮১,০০০ - ১,৭০০ ভর্তি ফি = ৭৯,৩০০ টাকা পরিশোধিত)',
            ],
            [
                'slug' => 'pay-ferdous-may2026',
                'title' => 'নিয়মিত মাসিক কিস্তি - মোঃ ফেরদৌস (৭৭ মাস)',
                'email' => 'ferdous@upolobdi.org',
                'amount' => 75300,
                'months_count' => 77,
                'payment_months' => 'জানুয়ারি ২০২০ - মে ২০২৬ (৭৭ মাস)',
                'payment_date' => '2026-05-05',
                'reference_number' => 'USS-REF-FERDOUS-77M',
                'receipt_number' => 'USS-REC-2026-MAY77',
                'remarks' => 'মোঃ ফেরদৌস (USS-006): ৭৭ মাসের কিস্তি (৭৭,০০০ - ১,৭০০ ভর্তি ফি = ৭৫,৩০০ টাকা পরিশোধিত)',
            ],
            [
                'slug' => 'pay-sajib-mar2026',
                'title' => 'নিয়মিত মাসিক কিস্তি - সজিব মোল্লা (৭৫ মাস)',
                'email' => 'sajib@upolobdi.org',
                'amount' => 73300,
                'months_count' => 75,
                'payment_months' => 'জানুয়ারি ২০২০ - মার্চ ২০২৬ (৭৫ মাস)',
                'payment_date' => '2026-03-05',
                'reference_number' => 'USS-REF-SAJIB-75M',
                'receipt_number' => 'USS-REC-2026-MAR75',
                'remarks' => 'সজিব মোল্লা (USS-001): ৭৫ মাসের কিস্তি (৭৫,০০০ - ১,৭০০ ভর্তি ফি = ৭৩,৩০০ টাকা পরিশোধিত)',
            ],
            [
                'slug' => 'pay-dawlat-mar2026',
                'title' => 'নিয়মিত মাসিক কিস্তি - দৌলত (৭৫ মাস)',
                'email' => 'doulot@upolobdi.org',
                'amount' => 73300,
                'months_count' => 75,
                'payment_months' => 'জানুয়ারি ২০২০ - মার্চ ২০২৬ (৭৫ মাস)',
                'payment_date' => '2026-03-05',
                'reference_number' => 'USS-REF-DAWLAT-75M',
                'receipt_number' => 'USS-REC-2026-MAR75D',
                'remarks' => 'দৌলত (USS-005): ৭৫ মাসের কিস্তি (৭৫,০০০ - ১,৭০০ ভর্তি ফি = ৭৩,৩০০ টাকা পরিশোধিত)',
            ],
            [
                'slug' => 'pay-kawser-dec2025',
                'title' => 'নিয়মিত মাসিক কিস্তি - কাউসার (৭২ মাস)',
                'email' => 'kawser@upolobdi.org',
                'amount' => 70300,
                'months_count' => 72,
                'payment_months' => 'জানুয়ারি ২০২০ - ডিসেম্বর ২০২৫ (৭২ মাস)',
                'payment_date' => '2025-12-05',
                'reference_number' => 'USS-REF-KAWSER-72M',
                'receipt_number' => 'USS-REC-2025-DEC72',
                'remarks' => 'কাউসার (USS-004): ৭২ মাসের কিস্তি (৭২,০০০ - ১,৭০০ ভর্তি ফি = ৭০,৩০০ টাকা পরিশোধিত)',
            ],
        ];

        foreach ($payments as $pData) {
            $user = User::findByEmail($pData['email']);
            $entry = Entry::make()
                ->collection('payments')
                ->slug($pData['slug'])
                ->data([
                    'title' => $pData['title'],
                    'member_email' => $pData['email'],
                    'user_email' => $pData['email'],
                    'user_id' => $user ? $user->id() : null,
                    'amount' => (int) $pData['amount'],
                    'payment_date' => $pData['payment_date'],
                    'date' => $pData['payment_date'],
                    'status' => 'approved',
                    'payment_status' => 'approved',
                    'payment_method' => 'cash',
                    'months_count' => (int) $pData['months_count'],
                    'payment_months' => $pData['payment_months'],
                    'reference_number' => $pData['reference_number'],
                    'receipt_number' => $pData['receipt_number'],
                    'receipt_generated_at' => $pData['payment_date'] . ' 10:00:00',
                    'receipt_authorized_by' => 'রুবেল মোল্লা (ক্যাশিয়ার)',
                    'remarks' => $pData['remarks'],
                    'entry_type' => 'committee',
                    'entry_created_by' => 'কমিটি অডিট বোর্ড',
                    'reviewed_by' => 'রুবেল মোল্লা (ক্যাশিয়ার)',
                    'reviewed_at' => $pData['payment_date'] . ' 12:00:00',
                ]);
            $entry->save();
            $this->info("Payment created: {$pData['title']} -> {$pData['amount']} BDT");
        }

        // 3. Clean and Sync Real Expense Records
        $existingExpenses = Entry::query()->where('collection', 'expenses')->get();
        foreach ($existingExpenses as $oldExp) {
            $oldExp->delete();
        }
        $this->info('Cleaned old expense records.');

        $expenses = [
            [
                'slug' => 'exp-iftar-party-20250330',
                'title' => 'ইফতার পার্টি',
                'amount' => 2680,
                'expense_date' => '2025-03-30',
                'category' => 'আপ্যায়ন ও অনুষ্ঠান',
                'voucher_number' => 'EXP-20250330-001',
                'description' => '৩০ মার্চ ২০২৫ - সমিতির সদস্যদের বার্ষিক ইফতার পার্টি বাবদ খরচ',
            ],
            [
                'slug' => 'exp-lichu-purchase-20250523',
                'title' => 'লিচু ক্রয়',
                'amount' => 7600,
                'expense_date' => '2025-05-23',
                'category' => 'ফলমূল ও আপ্যায়ন',
                'voucher_number' => 'EXP-20250523-002',
                'description' => '২৩ মে ২০২৫ - সমিতির সদস্যদের জন্য লিচু ক্রয় বাবদ খরচ',
            ],
            [
                'slug' => 'exp-meeting-kacchi-20260323',
                'title' => 'মিটিং এ কাচ্চি',
                'amount' => 2500,
                'expense_date' => '2026-03-23',
                'category' => 'মিটিং ও আপ্যায়ন',
                'voucher_number' => 'EXP-20260323-003',
                'description' => '২৩ মার্চ ২০২৬ - সমিতির সাধারণ সভায় কাচ্চি বিরিয়ানি আপ্যায়ন বাবদ খরচ',
            ],
            [
                'slug' => 'exp-lichu-purchase-20260527',
                'title' => 'লিচু ক্রয়',
                'amount' => 3300,
                'expense_date' => '2026-05-27',
                'category' => 'ফলমূল ও আপ্যায়ন',
                'voucher_number' => 'EXP-20260527-004',
                'description' => '২৭ মে ২০২৬ - সমিতির সদস্যদের জন্য মৌসুমী লিচু ক্রয় বাবদ খরচ',
            ],
            [
                'slug' => 'exp-tshirt-making-20260830',
                'title' => 'টি শার্ট',
                'amount' => 5200,
                'expense_date' => '2026-08-30',
                'category' => 'পোশাক ও এক্সেসরিজ',
                'voucher_number' => 'EXP-20260830-005',
                'description' => '৩০ আগস্ট ২০২৬ - সমিতির অফিশিয়াল টি-শার্ট তৈরি ও সরবরাহ বাবদ খরচ',
            ],
            [
                'slug' => 'exp-meeting-handi-20261012',
                'title' => 'মিটিং হান্ডি',
                'amount' => 2090,
                'expense_date' => '2026-10-12',
                'category' => 'মিটিং ও আপ্যায়ন',
                'voucher_number' => 'EXP-20261012-006',
                'description' => '১২ অক্টোবর ২০২৬ - কার্যকরী সভার নৈশভোজ ও হান্ডি খাবার বাবদ খরচ',
            ],
        ];

        foreach ($expenses as $expData) {
            $entry = Entry::make()
                ->collection('expenses')
                ->slug($expData['slug'])
                ->data([
                    'title' => $expData['title'],
                    'amount' => (int) $expData['amount'],
                    'expense_date' => $expData['expense_date'],
                    'date' => $expData['expense_date'],
                    'category' => $expData['category'],
                    'voucher_number' => $expData['voucher_number'],
                    'voucher_image' => null,
                    'description' => $expData['description'],
                    'recorded_by' => 'রুবেল মোল্লা (ক্যাশিয়ার)',
                    'recorded_by_email' => 'rubel@upolobdi.org',
                    'recorded_at' => $expData['expense_date'] . ' 18:00:00',
                ]);
            $entry->save();
            $this->info("Expense created: {$expData['title']} -> {$expData['amount']} BDT on {$expData['expense_date']}");
        }

        $somitiService->clearCache();
        $this->info('Real somiti data successfully synced!');
        return 0;
    }
}
