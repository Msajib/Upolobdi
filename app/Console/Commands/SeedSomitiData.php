<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Statamic\Facades\User;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SeedSomitiData extends Command
{
    protected $signature = 'somiti:seed';
    protected $description = 'Seeds the initial 6 Somiti members with photos, rules, events, and sample payment records';

    public function handle()
    {
        $this->info('Seeding Upolobdi Somobay Somiti (USS) Full Data...');

        // 1. Initial 6 Members with the Exact Names Requested
        $members = [
            [
                'email' => 'president@upolobdi.org',
                'name' => 'Sajib Mulla',
                'bangla_name' => 'সজিব মোল্লা',
                'role' => 'superadmin',
                'super' => true,
                'designation' => 'সভাপতি (President)',
                'member_id' => 'USS-001',
                'phone' => '01711-000001',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/sajib.jpg',
                'exemption_months' => 0,
            ],
            [
                'email' => 'vp@upolobdi.org',
                'name' => 'Rubel Mulla',
                'bangla_name' => 'রুবেল মোল্লা',
                'role' => 'admin',
                'super' => false,
                'designation' => 'সহ-সভাপতি (Vice President)',
                'member_id' => 'USS-002',
                'phone' => '01711-000002',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/rubel.jpg',
                'exemption_months' => 0,
            ],
            [
                'email' => 'cashier@upolobdi.org',
                'name' => 'Saiful Islam',
                'bangla_name' => 'সাইফুল ইসলাম',
                'role' => 'cashier',
                'super' => false,
                'designation' => 'ক্যাশিয়ার (Cashier)',
                'member_id' => 'USS-003',
                'phone' => '01711-000003',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/saiful.jpg',
                'exemption_months' => 0,
            ],
            [
                'email' => 'kawser@upolobdi.org',
                'name' => 'Kawser',
                'bangla_name' => 'কাউসার',
                'role' => 'member',
                'super' => false,
                'designation' => 'কার্যনির্বাহী সদস্য (Member)',
                'member_id' => 'USS-004',
                'phone' => '01711-000004',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/kawser.jpg',
                'exemption_months' => 0,
            ],
            [
                'email' => 'doulot@upolobdi.org',
                'name' => 'Doulot',
                'bangla_name' => 'দৌলত',
                'role' => 'member',
                'super' => false,
                'designation' => 'কার্যনির্বাহী সদস্য (Member)',
                'member_id' => 'USS-005',
                'phone' => '01711-000005',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/doulot.jpg',
                'exemption_months' => 0,
            ],
            [
                'email' => 'ferdous@upolobdi.org',
                'name' => 'Ferdous',
                'bangla_name' => 'ফেরদৌস',
                'role' => 'member',
                'super' => false,
                'designation' => 'কার্যনির্বাহী সদস্য (Member)',
                'member_id' => 'USS-006',
                'phone' => '01711-000006',
                'joined_date' => '2020-01-01',
                'avatar' => '/assets/images/avatars/ferdous.jpg',
                'exemption_months' => 3, // Exemption example
                'exemption_approved_by' => 'সাইফুল ইসলাম (ক্যাশিয়ার)',
                'exemption_reason' => 'ব্যবসায়িক ভ্রমণ ও উচ্চশিক্ষা জনিত ছুটি',
            ],
        ];

        foreach ($members as $data) {
            $user = User::findByEmail($data['email']);
            if (! $user) {
                $user = User::make();
                $user->email($data['email']);
            }

            $user->data([
                'name' => $data['name'],
                'bangla_name' => $data['bangla_name'],
                'designation' => $data['designation'],
                'member_id' => $data['member_id'],
                'phone' => $data['phone'],
                'joined_date' => $data['joined_date'],
                'avatar' => $data['avatar'],
                'exemption_months' => $data['exemption_months'] ?? 0,
                'exemption_approved_by' => $data['exemption_approved_by'] ?? null,
                'exemption_reason' => $data['exemption_reason'] ?? null,
                'is_active' => true,
            ]);

            $user->roles([$data['role']]);
            if (!empty($data['super'])) {
                $user->makeSuper();
            }
            $user->password('password123');
            $user->save();

            $this->info("User synced: {$data['name']} ({$data['designation']})");
        }

        // 2. Official Somiti Rules & Bylaws
        if (! \Statamic\Facades\Collection::findByHandle('rules')) {
            $col = \Statamic\Facades\Collection::make('rules');
            $col->title('Rules (সমিতির নীতিমালা)');
            $col->save();
        }

        $rules = [
            [
                'slug' => 'rule-1-monthly-installment',
                'rule_number' => 'ধারা ১',
                'order' => 1,
                'icon' => 'fa-money-bill-transfer',
                'title_bn' => 'নিয়মিত মাসিক কিস্তি প্রদান',
                'title_en' => 'Monthly Installment Obligation',
                'description_bn' => 'প্রত্যেক সদস্য প্রতি মাসের ১ থেকে ১০ তারিখের মধ্যে নির্ধারিত ১,০০০ টাকা (সভাপতি কর্তৃক পরিবর্তনযোগ্য) মাসিক কিস্তি নির্ধারিত ব্যাংক/বিকাশ/নগদ অ্যাকাউন্টে জমা দিয়ে পোর্টালে ট্রানজেকশন স্লিপ সাবমিট করবেন।',
                'description_en' => 'Every member is obligated to deposit the designated monthly installment of 1,000 BDT between the 1st and 10th of every month and submit transaction proof via the member portal.',
            ],
            [
                'slug' => 'rule-2-payment-approval',
                'rule_number' => 'ধারা ২',
                'order' => 2,
                'icon' => 'fa-circle-check',
                'title_bn' => 'ক্যাশিয়ার ও সভাপতি কর্তৃক যাচাই ও অনুমোদন',
                'title_en' => 'Payment Verification & Audit',
                'description_bn' => 'সদস্য কর্তৃক জমাকৃত কিস্তির প্রমাণপত্র ও TrxID ক্যাশিয়ার যাচাইপূর্বক অনুমোদন করবেন। ক্যাশিয়ারের অবর্তমানে বা যেকোনো প্রয়োজনে সভাপতিও সরাসরি অনুমোদন বা বাতিল করতে পারবেন।',
                'description_en' => 'Submitted transaction slips must be verified and approved by the Cashier. The President is also authorized to approve or reject payments on behalf of the somiti.',
            ],
            [
                'slug' => 'rule-3-due-warning-system',
                'rule_number' => 'ধারা ৩',
                'order' => 3,
                'icon' => 'fa-triangle-exclamation',
                'title_bn' => '২+ মাস বকেয়া পাবলিক সতর্কতা ব্যবস্থা',
                'title_en' => '2+ Months Overdue Public Alert Board',
                'description_bn' => 'কোনো সদস্যের পরপর ২ বা ততোধিক মাসের কিস্তি বকেয়া থাকলে ওয়েবসাইটে স্বয়ংক্রিয় সতর্কবার্তা তালিকায় নাম ও বকেয়া পরিমাণ প্রদর্শিত হবে। এটি সভাপতি ও ক্যাশিয়ার কর্তৃক নিয়ন্ত্রণযোগ্য।',
                'description_en' => 'Members with 2 or more unpaid installment months will automatically appear on the website overdue warning board, controlled by the President & Cashier.',
            ],
            [
                'slug' => 'rule-4-exemption-limits',
                'rule_number' => 'ধারা ৪',
                'order' => 4,
                'icon' => 'fa-hand-holding-heart',
                'title_bn' => 'বকেয়া অবকাশ ও মওকুফের সর্বোচ্চ সময়সীমা',
                'title_en' => 'Due Exemption & Grace Thresholds',
                'description_bn' => 'বিশেষ যৌক্তিক কারণে ক্যাশিয়ার সর্বোচ্চ ৬ মাস এবং সভাপতি সর্বোচ্চ ১২ মাস পর্যন্ত বকেয়া অবকাশ/মওকুফ মঞ্জুর করতে পারেন। অবকাশ কার্যকর থাকলে উক্ত সদস্য সতর্কবার্তা তালিকা থেকে অব্যাহতি পাবেন।',
                'description_en' => 'The Cashier can grant up to 6 months of due grace/exemption, while the President can grant up to 12 months. Exempted members are excluded from public due alerts.',
            ],
            [
                'slug' => 'rule-5-fund-utilization',
                'rule_number' => 'ধারা ৫',
                'order' => 5,
                'icon' => 'fa-chart-line',
                'title_bn' => 'তহবিল সংরক্ষণ ও যৌথ ভবিষ্যৎ বিনিয়োগ',
                'title_en' => 'Fund Accumulation & Investment Vision',
                'description_bn' => 'সমিতির মোট সংগৃহীত তহবিল কোনো একক ব্যক্তির জন্য নয়; এটি ৬ বন্ধুর যৌথ ভবিষ্যৎ লাভজনক ব্যবসা ও দীর্ঘমেয়াদী বিনিয়োগ প্রকল্প বাস্তবায়নের জন্য সংরক্ষিত থাকবে।',
                'description_en' => 'The accumulated somiti capital is permanently safeguarded for future collective commercial investments and profitable business projects of the 6 founding friends.',
            ],
        ];

        foreach ($rules as $rData) {
            $existing = Entry::whereCollection('rules')->where('slug', $rData['slug'])->first();
            if (! $existing) {
                $entry = Entry::make()
                    ->collection('rules')
                    ->slug($rData['slug'])
                    ->data($rData);
                $entry->save();
                $this->info("Rule seeded: {$rData['title_bn']}");
            }
        }

        // 3. Somiti Events with Multi-Photo Masonry & Highlights
        $events = [
            [
                'slug' => 'somiti-annual-meetup-2025',
                'title' => 'Annual Somiti Strategic Meetup & 5th Anniversary',
                'title_bn' => 'বার্ষিক সমিতি সম্মিলন ও ৫ম বর্ষপূর্তি উদযাপন ২০২৫',
                'event_date' => '2025-01-15',
                'location' => 'Adda Cafe, Dhanmondi, Dhaka',
                'image' => '/assets/images/events/annual_meetup.jpg',
                'is_highlighted' => true,
                'gallery_images' => [
                    '/assets/images/events/annual_meetup.jpg',
                    '/assets/images/events/annual_tour.jpg',
                    '/assets/images/events/iftar_gathering.jpg',
                ],
                'description' => 'A memorable evening where all 6 founding friends gathered to celebrate 5 years of our bond and discuss our collective future investment portfolio.',
                'description_bn' => 'আমাদের ৬ বন্ধুর আন্তরিক সমবায় পথচলার ৫ম বর্ষপূর্তি উদযাপন ও ভবিষ্যৎ স্থায়ী সম্পদ গঠন ও পারস্পরিক বিনিয়োগ নিয়ে চমৎকার আলোচনা ও মিলনমেলা।',
            ],
            [
                'slug' => 'somiti-eid-milestone-iftar',
                'title' => 'Eid Reunion & Somiti Milestone Iftar Party',
                'title_bn' => 'পবিত্র মাহে রমজান উপলক্ষে বার্ষিক ইফতার ও ঈদ পুনর্মিলনী',
                'event_date' => '2025-03-22',
                'location' => 'Banani Club, Dhaka',
                'image' => '/assets/images/events/iftar_gathering.jpg',
                'is_highlighted' => true,
                'gallery_images' => [
                    '/assets/images/events/iftar_gathering.jpg',
                    '/assets/images/events/annual_meetup.jpg',
                ],
                'description' => 'A heartfelt reunion filled with brotherhood, delicious food, and setting the new year goals for our monthly fund increment.',
                'description_bn' => 'আন্তরিক ভ্রাতৃত্বের বন্ধন সুদৃঢ় করতে সকল সদস্যদের উপস্থিতিতে বার্ষিক ইফতার মাহফিল ও সমিতির নতুন লক্ষ্যমাত্রা নির্ধারণ।',
            ],
            [
                'slug' => 'somiti-annual-friendship-tour',
                'title' => 'Somiti Friendship Tour & Green Adventure',
                'title_bn' => 'সমিতির বার্ষিক বন্ধুত্ব ভ্রমণ ও প্রাকৃতিক এডভেঞ্চার ট্যুর',
                'event_date' => '2025-05-10',
                'location' => 'Sreemangal Tea Gardens, Sylhet',
                'image' => '/assets/images/events/annual_tour.jpg',
                'is_highlighted' => true,
                'gallery_images' => [
                    '/assets/images/events/annual_tour.jpg',
                    '/assets/images/events/annual_meetup.jpg',
                    '/assets/images/events/iftar_gathering.jpg',
                ],
                'description' => 'An adventurous annual getaway among lush green hills celebrating our unbroken bond of friendship and shared dreams.',
                'description_bn' => 'সবুজে ঘেরা পাহাড় ও চা বাগানের মনোরম পরিবেশে ৬ বন্ধুর মিলনমেলা ও দীর্ঘমেয়াদী যৌথ বিনিয়োগের আনন্দঘন আলোচনা।',
            ],
        ];

        foreach ($events as $eventData) {
            $existing = Entry::whereCollection('events')->where('slug', $eventData['slug'])->first();
            if (! $existing) {
                $entry = Entry::make()
                    ->collection('events')
                    ->slug($eventData['slug'])
                    ->data($eventData);
                $entry->save();
            } else {
                $existing->data(array_merge($existing->data()->all(), $eventData));
                $existing->save();
            }
            $this->info("Event synced: {$eventData['title']}");
        }

        // 4. Sample Payments with Active Due Scenarios
        $samplePayments = [
            [
                'slug' => 'pay-sajib-2025-01',
                'title' => 'Installment - Sajib Mulla (Jan 2025)',
                'user_email' => 'president@upolobdi.org',
                'amount' => 1000,
                'months_count' => 1,
                'payment_months' => 'January 2025',
                'payment_method' => 'bkash',
                'reference_number' => 'BK897216345',
                'payment_date' => '2025-01-05',
                'status' => 'approved',
                'reviewed_by' => 'সাইফুল ইসলাম (ক্যাশিয়ার)',
                'reviewed_at' => '2025-01-06',
            ],
            [
                'slug' => 'pay-rubel-2025-01',
                'title' => 'Installment - Rubel Mulla (Jan 2025)',
                'user_email' => 'vp@upolobdi.org',
                'amount' => 1000,
                'months_count' => 1,
                'payment_months' => 'January 2025',
                'payment_method' => 'nagad',
                'reference_number' => 'NGD993182741',
                'payment_date' => '2025-01-07',
                'status' => 'approved',
                'reviewed_by' => 'সাইফুল ইসলাম (ক্যাশিয়ার)',
                'reviewed_at' => '2025-01-08',
            ],
            [
                'slug' => 'pay-saiful-2025-01',
                'title' => 'Installment - Saiful Islam (Jan 2025)',
                'user_email' => 'cashier@upolobdi.org',
                'amount' => 1000,
                'months_count' => 1,
                'payment_months' => 'January 2025',
                'payment_method' => 'bank',
                'reference_number' => 'IBBL-928174',
                'payment_date' => '2025-01-02',
                'status' => 'approved',
                'reviewed_by' => 'সজিব মোল্লা (সভাপতি)',
                'reviewed_at' => '2025-01-03',
            ],
            [
                'slug' => 'pay-kawser-2025-01',
                'title' => 'Installment - Kawser (Jan-Feb 2025)',
                'user_email' => 'kawser@upolobdi.org',
                'amount' => 2000,
                'months_count' => 2,
                'payment_months' => 'January 2025 - February 2025',
                'payment_method' => 'bkash',
                'reference_number' => 'BK826354110',
                'payment_date' => '2025-01-10',
                'status' => 'approved',
                'reviewed_by' => 'সাইফুল ইসলাম (ক্যাশিয়ার)',
                'reviewed_at' => '2025-01-11',
            ],
            [
                'slug' => 'pay-doulot-2025-pending',
                'title' => 'Installment - Doulot (February 2025)',
                'user_email' => 'doulot@upolobdi.org',
                'amount' => 1000,
                'months_count' => 1,
                'payment_months' => 'February 2025',
                'payment_method' => 'bkash',
                'reference_number' => 'BK994827160',
                'payment_date' => '2025-02-14',
                'status' => 'pending',
                'proof_image' => '/assets/images/events/annual_meetup.jpg',
            ],
        ];

        foreach ($samplePayments as $payData) {
            $user = User::findByEmail($payData['user_email']);
            $payData['user_id'] = $user ? $user->id() : null;
            $payData['member_email'] = $payData['user_email'];
            $payData['payment_status'] = $payData['status'];

            $existing = Entry::query()->where('collection', 'payments')->where('slug', $payData['slug'])->first();
            if (! $existing) {
                $entry = Entry::make()
                    ->collection('payments')
                    ->slug($payData['slug'])
                    ->data($payData);
                $entry->save();
            } else {
                $existing->data(array_merge($existing->data()->all(), $payData));
                $existing->save();
            }
            $this->info("Payment synced: {$payData['title']}");
        }

        $this->info('Seeding and sync completed successfully!');
    }
}
