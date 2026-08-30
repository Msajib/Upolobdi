<?php

namespace App\Services;

use Statamic\Facades\User;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\YAML;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SomitiService
{
    protected ?array $cachedSettings = null;
    protected ?array $cachedMembers = null;
    protected ?array $cachedPayments = null;
    protected ?array $cachedEvents = null;
    protected ?array $cachedRules = null;
    protected ?array $cachedProjects = null;
    protected ?array $cachedAnnouncements = null;
    protected ?array $cachedOverview = null;
    protected array $cachedDue = [];

    /**
     * Invalidate all in-memory caches
     */
    public function clearCache(): void
    {
        $this->cachedSettings = null;
        $this->cachedMembers = null;
        $this->cachedPayments = null;
        $this->cachedEvents = null;
        $this->cachedRules = null;
        $this->cachedProjects = null;
        $this->cachedAnnouncements = null;
        $this->cachedOverview = null;
        $this->cachedDue = [];
    }

    /**
     * Get global somiti settings
     */
    public function getSettings(): array
    {
        if ($this->cachedSettings !== null) {
            return $this->cachedSettings;
        }

        $path = base_path('content/globals/somiti_settings.yaml');
        if (File::exists($path)) {
            $parsed = YAML::parse(File::get($path));
            $this->cachedSettings = $parsed['data'] ?? [
                'monthly_installment' => 1000,
                'due_warning_modal_enabled' => true,
                'somiti_name_bn' => 'উপলব্ধি সমবায় সমিতি',
                'somiti_name_en' => 'Upolobdi Somobay Somiti (USS)',
                'motto_bn' => 'সত্যের পথে স্বপ্নের অভিযান',
                'motto_en' => 'Journey of Dreams on the Path of Truth',
                'est_year' => '2020',
                'bkash_number' => '01712-345678 (Personal / Send Money)',
                'nagad_number' => '01812-345678 (Personal)',
                'rocket_number' => '01912-345678-9',
                'bank_details' => 'Islami Bank Bangladesh Ltd, A/C: 20501234567890, Branch: Dhanmondi',
                'contact_phone' => '+880 1712-345678',
                'contact_email' => 'info@upolobdi-somiti.org',
            ];
            return $this->cachedSettings;
        }

        $this->cachedSettings = [
            'monthly_installment' => 1000,
            'due_warning_modal_enabled' => true,
            'somiti_name_bn' => 'উপলব্ধি সমবায় সমিতি',
            'somiti_name_en' => 'Upolobdi Somobay Somiti (USS)',
        ];
        return $this->cachedSettings;
    }

    /**
     * Update global somiti settings
     */
    public function updateSettings(array $data): void
    {
        $path = base_path('content/globals/somiti_settings.yaml');
        $current = $this->getSettings();
        $updated = array_merge($current, $data);

        $yamlData = [
            'title' => 'Somiti Global Settings',
            'data' => $updated,
        ];

        File::put($path, YAML::dump($yamlData));
        $this->clearCache();
    }

    /**
     * Get primary role of a user (superadmin, admin, cashier, member)
     */
    public function getUserRole($user): string
    {
        if (! $user) {
            return 'member';
        }

        $email = strtolower($user->email() ?? '');
        $roles = method_exists($user, 'roles') ? $user->roles()->map->handle()->all() : [];
        $rawRoles = (array) ($user->get('roles') ?? []);
        $designation = strtolower($user->get('designation') ?? '');

        if ($user->isSuper() || in_array('superadmin', $roles) || in_array('superadmin', $rawRoles) || str_contains($email, 'sajib@') || (str_contains($designation, 'president') && !str_contains($designation, 'vice'))) {
            return 'superadmin';
        }

        if (in_array('admin', $roles) || in_array('admin', $rawRoles) || str_contains($email, 'rubel@') || str_contains($designation, 'vice president') || str_contains($designation, 'সহ-সভাপতি')) {
            return 'admin';
        }

        if (in_array('cashier', $roles) || in_array('cashier', $rawRoles) || str_contains($email, 'saiful@') || str_contains($designation, 'cashier') || str_contains($designation, 'ক্যাশিয়ার')) {
            return 'cashier';
        }

        return 'member';
    }

    /**
     * Get all somiti members
     */
    public function getAllMembers(): array
    {
        if ($this->cachedMembers !== null) {
            return $this->cachedMembers;
        }

        $users = User::all();
        $list = [];

        foreach ($users as $user) {
            // Exclude dedicated CP superadmin account from somiti member list
            $email = strtolower($user->email() ?? '');
            if (str_contains($email, 'superadmin') || $user->get('is_system_admin')) {
                continue;
            }

            $primaryRole = $this->getUserRole($user);
            $avatar = $user->get('avatar') ?: '/assets/images/avatar-default.svg';

            $list[] = [
                'id' => $user->id(),
                'email' => $user->email(),
                'name' => $user->get('name') ?: $user->email(),
                'bangla_name' => $user->get('bangla_name') ?: $user->get('name') ?: $user->email(),
                'role' => $primaryRole,
                'role_label_en' => $this->getRoleTitle($primaryRole, 'en'),
                'role_label_bn' => $this->getRoleTitle($primaryRole, 'bn'),
                'designation' => $user->get('designation') ?: 'কার্যনির্বাহী সদস্য',
                'member_id' => $user->get('member_id') ?: ('USS-' . substr($user->id(), 0, 4)),
                'phone' => $user->get('phone') ?: 'N/A',
                'joined_date' => $user->get('joined_date') ?: '2020-01-01',
                'exemption_months' => (int) ($user->get('exemption_months') ?: 0),
                'exemption_approved_by' => $user->get('exemption_approved_by'),
                'exemption_reason' => $user->get('exemption_reason'),
                'avatar' => $avatar,
                'is_active' => $user->get('is_active') ?? true,
            ];
        }

        // Sort: Superadmin (Sajib Mulla), Admin (Rubel Mulla), Cashier (Saiful Islam), Members (Kawser, Doulot, Ferdous)
        usort($list, function ($a, $b) {
            $weights = ['superadmin' => 1, 'admin' => 2, 'cashier' => 3, 'member' => 4];
            $wA = $weights[$a['role']] ?? 5;
            $wB = $weights[$b['role']] ?? 5;
            if ($wA === $wB) {
                return strcmp($a['member_id'], $b['member_id']);
            }
            return $wA <=> $wB;
        });

        $this->cachedMembers = $list;
        return $this->cachedMembers;
    }

    /**
     * Get single member details
     */
    public function getMemberByEmail(string $email): ?array
    {
        $all = $this->getAllMembers();
        foreach ($all as $m) {
            if ($m['email'] === $email) {
                return $m;
            }
        }
        return null;
    }

    /**
     * Calculate monthly due for a member (Fast in-memory calculation)
     */
    public function calculateDue(array $member, ?int $monthlyAmount = null): array
    {
        if ($monthlyAmount === null) {
            $settings = $this->getSettings();
            $monthlyAmount = (int) ($settings['monthly_installment'] ?? 1000);
        }

        $cacheKey = $member['email'] . '_' . $monthlyAmount;
        if (isset($this->cachedDue[$cacheKey])) {
            return $this->cachedDue[$cacheKey];
        }

        $startDate = Carbon::parse('2025-01-01');
        $now = Carbon::now();
        if ($now->lessThan($startDate)) {
            $now = Carbon::parse('2025-04-01');
        }

        $totalMonthsActive = max(1, ($now->year - $startDate->year) * 12 + ($now->month - $startDate->month) + 1);

        // Get approved payments in-memory
        $allUserPayments = $this->getPayments($member['email']);
        $totalPaidMonths = 0;
        $totalPaidAmount = 0;
        foreach ($allUserPayments as $pay) {
            if ($pay['status'] === 'approved') {
                $monthsCount = (int) ($pay['months_count'] ?: 1);
                $totalPaidMonths += $monthsCount;
                $totalPaidAmount += (int) ($pay['amount'] ?: 0);
            }
        }

        $exemptionMonths = (int) ($member['exemption_months'] ?? 0);

        $grossDueMonths = max(0, $totalMonthsActive - $totalPaidMonths);
        $netDueMonths = max(0, $grossDueMonths - $exemptionMonths);
        $totalDueAmount = $netDueMonths * $monthlyAmount;

        $unpaidMonthsListEn = [];
        $unpaidMonthsListBn = [];

        $bnMonths = [
            1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
            5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
            9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
        ];

        for ($i = 0; $i < $grossDueMonths; $i++) {
            $monthIndex = $totalPaidMonths + $i;
            $targetDate = (clone $startDate)->addMonths($monthIndex);
            
            $unpaidMonthsListEn[] = $targetDate->format('F Y');
            $unpaidMonthsListBn[] = ($bnMonths[$targetDate->month] ?? '') . ' ' . $this->bnNumber($targetDate->year);
        }

        $isExempted = false;
        if ($exemptionMonths > 0 && $netDueMonths === 0) {
            $isExempted = true;
        }

        $rangeEn = 'All payments are up to date';
        $rangeBn = 'সব কিস্তি পরিশোধিত রয়েছে';

        if ($netDueMonths > 0 && count($unpaidMonthsListEn) > 0) {
            $firstMonthEn = $unpaidMonthsListEn[0];
            $lastMonthEn = end($unpaidMonthsListEn);
            $firstMonthBn = $unpaidMonthsListBn[0];
            $lastMonthBn = end($unpaidMonthsListBn);

            if ($firstMonthEn === $lastMonthEn) {
                $rangeEn = "{$firstMonthEn} (1 Month)";
                $rangeBn = "{$firstMonthBn} (১ মাস)";
            } else {
                $rangeEn = "{$firstMonthEn} to {$lastMonthEn} ({$netDueMonths} Months)";
                $rangeBn = "{$firstMonthBn} থেকে {$lastMonthBn} ({$this->bnNumber($netDueMonths)} মাস)";
            }
        }

        $res = [
            'member_id' => $member['member_id'],
            'email' => $member['email'],
            'name' => $member['name'],
            'bangla_name' => $member['bangla_name'],
            'avatar' => $member['avatar'] ?? '/assets/images/avatar-default.svg',
            'role' => $member['role'],
            'total_active_cycle_months' => $totalMonthsActive,
            'total_paid_months' => $totalPaidMonths,
            'total_paid_amount' => $totalPaidAmount,
            'gross_due_months' => $grossDueMonths,
            'exemption_months' => $exemptionMonths,
            'exemption_approved_by' => $member['exemption_approved_by'] ?? null,
            'exemption_reason' => $member['exemption_reason'] ?? null,
            'is_exempted' => $isExempted,
            'net_due_months' => $netDueMonths,
            'total_due_amount' => $totalDueAmount,
            'monthly_amount' => $monthlyAmount,
            'range_en' => $rangeEn,
            'range_bn' => $rangeBn,
            'unpaid_months_en' => $unpaidMonthsListEn,
            'unpaid_months_bn' => $unpaidMonthsListBn,
            'has_due' => $netDueMonths > 0,
            'is_overdue_2plus' => ($netDueMonths >= 2 && !$isExempted),
        ];

        $this->cachedDue[$cacheKey] = $res;
        return $res;
    }

    /**
     * Get list of overdue members (with president-set due day and exemption logic)
     */
    public function getOverdueMembers(int $threshold = 1): array
    {
        $members = $this->getAllMembers();
        $settings = $this->getSettings();
        $monthly = (int) ($settings['monthly_installment'] ?? 1000);
        $dueDay = (int) ($settings['due_day_of_month'] ?? 15);

        // Check if current month's due date has passed
        $now = Carbon::now();
        $currentMonthDueDate = Carbon::create($now->year, $now->month, min($dueDay, $now->daysInMonth));
        $currentMonthDuePassed = $now->greaterThanOrEqualTo($currentMonthDueDate);

        $overdue = [];
        foreach ($members as $m) {
            $due = $this->calculateDue($m, $monthly);

            // Skip if exempted (handles future exemption months too)
            if ($due['is_exempted']) {
                continue;
            }

            $netDue = $due['net_due_months'];
            if ($netDue <= 0) {
                continue;
            }

            // If only 1 month due (current month), only show after due date has passed
            if ($netDue === 1 && !$currentMonthDuePassed) {
                continue;
            }

            if ($netDue >= $threshold) {
                $due['avatar'] = $m['avatar'];
                $due['exemption_approved_by'] = $m['exemption_approved_by'];
                $due['exemption_reason'] = $m['exemption_reason'];
                $overdue[] = $due;
            }
        }

        return $overdue;
    }

    /**
     * Get Masonry Gallery Images - Selected by Admin/President from events
     * Returns only images that are marked is_masonry_selected=true
     */
    public function getMasonryGalleryImages(): array
    {
        $events = $this->getEvents();
        $gallery = [];

        foreach ($events as $ev) {
            if (!empty($ev['is_masonry_selected'])) {
                foreach ($ev['gallery_images'] as $img) {
                    $gallery[] = [
                        'image' => $img,
                        'event_id' => $ev['id'],
                    ];
                }
            }
        }

        // Fall back to all highlighted events if none specifically selected
        if (empty($gallery)) {
            foreach ($events as $ev) {
                if (!empty($ev['is_highlighted'])) {
                    foreach ($ev['gallery_images'] as $img) {
                        $gallery[] = [
                            'image' => $img,
                            'event_id' => $ev['id'],
                        ];
                    }
                }
            }
        }

        // Fall back to all events if still empty
        if (empty($gallery)) {
            foreach ($events as $ev) {
                foreach ($ev['gallery_images'] as $img) {
                    $gallery[] = [
                        'image' => $img,
                        'event_id' => $ev['id'],
                    ];
                }
            }
        }

        return $gallery;
    }

    /**
     * Get All Payments
     */
    public function getPayments(?string $userEmail = null): array
    {
        if ($this->cachedPayments === null) {
            $entries = Entry::query()->where('collection', 'payments')->get();
            $list = [];

            foreach ($entries as $entry) {
                $email = $entry->get('member_email');
                $member = $this->getMemberByEmail($email ?: '');
                $pStatus = $entry->get('payment_status') ?: $entry->get('status') ?: 'pending';

                $entryType = $entry->get('entry_type') ?: 'own';
                $entryCreatedBy = $entry->get('entry_created_by');
                if (!$entryCreatedBy) {
                    $entryCreatedBy = ($entryType === 'own') ? 'Self (সদস্য নিজে)' : 'Cashier / Admin';
                }

                $receiptNumber = $entry->get('receipt_number');
                $receiptGeneratedAt = $entry->get('receipt_generated_at');
                $receiptAuthorizedBy = $entry->get('receipt_authorized_by');

                $reviewedBy = $entry->get('reviewed_by');
                if ($pStatus === 'approved') {
                    if (!$reviewedBy) {
                        $reviewedBy = ($entryType === 'superadmin') ? 'সজিব মোল্লা (সভাপতি)' : 'সাইফুল ইসলাম (ক্যাশিয়ার)';
                    }
                    if (!$receiptNumber) {
                        // Deterministic permanent receipt number based on entry id
                        $hash = strtoupper(substr(md5($entry->id() . $entry->get('payment_date')), 0, 6));
                        $year = date('Y', strtotime($entry->get('payment_date') ?: 'now'));
                        $receiptNumber = "USS-REC-{$year}-{$hash}";
                        $receiptGeneratedAt = $entry->get('reviewed_at') ?: $entry->get('payment_date');
                        $receiptAuthorizedBy = $reviewedBy;
                    }
                }

                $amount = (int) ($entry->get('amount') ?: 0);

                $list[] = [
                    'id' => $entry->id(),
                    'slug' => $entry->slug(),
                    'title' => $entry->get('title'),
                    'member_email' => $email,
                    'member_name' => $member['name'] ?? $email,
                    'member_bangla_name' => $member['bangla_name'] ?? $email,
                    'member_id' => $member['member_id'] ?? 'USS-000',
                    'member_phone' => $member['phone'] ?? 'N/A',
                    'member_role_bn' => $member['role_label_bn'] ?? 'সদস্য',
                    'amount' => $amount,
                    'amount_bn' => $this->bnNumber(number_format($amount)),
                    'amount_words' => $this->amountInBnWords($amount),
                    'months_count' => (int) ($entry->get('months_count') ?: 1),
                    'payment_months' => $entry->get('payment_months') ?: '',
                    'payment_method' => $entry->get('payment_method') ?: 'bkash',
                    'payment_method_label' => match($entry->get('payment_method')) {
                        'bkash' => 'বিকাশ (bKash)',
                        'nagad' => 'নগদ (Nagad)',
                        'rocket' => 'রকেট (Rocket)',
                        'bank' => 'ব্যাংক ডিপোজিট (Bank)',
                        'cash' => 'সরাসরি নগদ (Cash)',
                        default => strtoupper($entry->get('payment_method') ?: 'Cash')
                    },
                    'reference_number' => $entry->get('reference_number') ?: '',
                    'payment_date' => $entry->get('payment_date') ?: $entry->date()->format('Y-m-d'),
                    'status' => $pStatus,
                    'payment_status' => $pStatus,
                    'entry_type' => $entryType,
                    'entry_created_by' => $entryCreatedBy,
                    'proof_image' => $entry->get('proof_image') ?: null,
                    'rejection_reason' => $entry->get('rejection_reason'),
                    'reviewed_by' => $reviewedBy,
                    'reviewed_at' => $entry->get('reviewed_at') ?: $entry->get('payment_date'),
                    'receipt_number' => $receiptNumber,
                    'receipt_generated_at' => $receiptGeneratedAt ?: $entry->get('reviewed_at') ?: $entry->get('payment_date'),
                    'receipt_authorized_by' => $receiptAuthorizedBy ?: $reviewedBy,
                    'has_receipt' => ($pStatus === 'approved' && !empty($receiptNumber)),
                ];
            }

            usort($list, function ($a, $b) {
                return strcmp($b['payment_date'], $a['payment_date']);
            });

            $this->cachedPayments = $list;
        }

        if ($userEmail) {
            return array_values(array_filter($this->cachedPayments, fn($p) => $p['member_email'] === $userEmail));
        }

        return $this->cachedPayments;
    }


    /**
     * Get All Investment Projects
     */
    public function getProjects(): array
    {
        if ($this->cachedProjects !== null) {
            return $this->cachedProjects;
        }

        if (! \Statamic\Facades\Collection::findByHandle('projects')) {
            return [];
        }

        $entries = Entry::query()->where('collection', 'projects')->get();
        $list = [];

        foreach ($entries as $entry) {
            $list[] = [
                'id' => $entry->id(),
                'slug' => $entry->slug(),
                'title' => $entry->get('title'),
                'title_bn' => $entry->get('title_bn') ?: $entry->get('title'),
                'status' => $entry->get('status') ?: 'planned',
                'target_amount' => $entry->get('target_amount') ?: 'বাজেট নির্ধারণাধীন',
                'timeline' => $entry->get('timeline') ?: '২০২৫ - ২০২৬',
                'location' => $entry->get('location') ?: 'বাংলাদেশ',
                'image' => $entry->get('image') ?: 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&auto=format&fit=crop&q=80',
                'description' => $entry->get('description') ?: '',
                'description_bn' => $entry->get('description_bn') ?: $entry->get('description'),
            ];
        }

        $this->cachedProjects = $list;
        return $this->cachedProjects;
    }

    /**
     * Get Active Announcements (sorted by priority: President=1, Cashier=2, VP=3, Member=4)
     */
    public function getAnnouncements(): array
    {
        if ($this->cachedAnnouncements !== null) {
            return $this->cachedAnnouncements;
        }

        if (! \Statamic\Facades\Collection::findByHandle('announcements')) {
            return [];
        }

        $entries = Entry::query()->where('collection', 'announcements')->get();
        $list = [];

        foreach ($entries as $entry) {
            if (! $entry->get('is_active')) {
                continue;
            }

            $list[] = [
                'id'                       => $entry->id(),
                'slug'                     => $entry->slug(),
                'title_bn'                 => $entry->get('title_bn') ?: $entry->get('title'),
                'title_en'                 => $entry->get('title_en') ?: $entry->get('title'),
                'body_bn'                  => $entry->get('body_bn') ?: '',
                'body_en'                  => $entry->get('body_en') ?: '',
                'announcement_date'        => $entry->get('announcement_date') ?: $entry->date()->format('Y-m-d'),
                'published_by_email'       => $entry->get('published_by_email') ?: '',
                'published_by_name'        => $entry->get('published_by_name') ?: 'সভাপতি',
                'published_by_designation_bn' => $entry->get('published_by_designation_bn') ?: 'সভাপতি',
                'published_by_designation_en' => $entry->get('published_by_designation_en') ?: 'President',
                'published_by_role'        => $entry->get('published_by_role') ?: 'superadmin',
                'priority'                 => (int) ($entry->get('priority') ?: 99),
                'allow_sharing'            => (bool) $entry->get('allow_sharing'),
                'is_active'                => true,
            ];
        }

        // Sort: President(priority=1) first, then Cashier(2), then VP(3)
        usort($list, fn($a, $b) => $a['priority'] <=> $b['priority']);

        $this->cachedAnnouncements = $list;
        return $this->cachedAnnouncements;
    }


    public function getEvents(): array
    {
        if ($this->cachedEvents !== null) {
            return $this->cachedEvents;
        }

        $entries = Entry::query()->where('collection', 'events')->get();
        $list = [];

        foreach ($entries as $entry) {
            $gallery = $entry->get('gallery_images') ?: [$entry->get('image') ?: '/assets/images/events/annual_meetup.jpg'];
            if (!is_array($gallery)) {
                $gallery = [$gallery];
            }

            $list[] = [
                'id' => $entry->id(),
                'slug' => $entry->slug(),
                'title' => $entry->get('title'),
                'title_bn' => $entry->get('title_bn') ?: $entry->get('title'),
                'event_date' => $entry->get('event_date') ?: $entry->date()->format('Y-m-d'),
                'location' => $entry->get('location') ?: 'ঢাকা, বাংলাদেশ',
                'image' => $entry->get('image') ?: '/assets/images/events/annual_meetup.jpg',
                'is_highlighted' => (bool) $entry->get('is_highlighted'),
                'is_masonry_selected' => (bool) $entry->get('is_masonry_selected'),
                'gallery_images' => array_values(array_filter($gallery)),
                'description' => $entry->get('description') ?: '',
                'description_bn' => $entry->get('description_bn') ?: $entry->get('description'),
            ];
        }

        usort($list, function ($a, $b) {
            return strcmp($b['event_date'], $a['event_date']);
        });

        $this->cachedEvents = $list;
        return $this->cachedEvents;
    }

    /**
     * Get Highlighted Images for the Featured Moments Section
     */
    public function getHighlightImages(): array
    {
        $events = $this->getEvents();
        $highlights = [];

        foreach ($events as $ev) {
            if (!empty($ev['is_highlighted'])) {
                foreach ($ev['gallery_images'] as $idx => $img) {
                    $highlights[] = [
                        'image' => $img,
                        'event_title' => $ev['title'],
                        'event_title_bn' => $ev['title_bn'],
                        'date' => $ev['event_date'],
                        'location' => $ev['location'],
                    ];
                }
            }
        }

        if (empty($highlights) && count($events) > 0) {
            foreach ($events as $ev) {
                $highlights[] = [
                    'image' => $ev['image'],
                    'event_title' => $ev['title'],
                    'event_title_bn' => $ev['title_bn'],
                    'date' => $ev['event_date'],
                    'location' => $ev['location'],
                ];
            }
        }

        return $highlights;
    }

    /**
     * Get Somiti Rules & Bylaws
     */
    public function getRules(): array
    {
        if ($this->cachedRules !== null) {
            return $this->cachedRules;
        }

        if (! \Statamic\Facades\Collection::findByHandle('rules')) {
            return [];
        }

        $entries = Entry::query()->where('collection', 'rules')->get();
        $list = [];

        foreach ($entries as $entry) {
            $list[] = [
                'id' => $entry->id(),
                'slug' => $entry->slug(),
                'rule_number' => $entry->get('rule_number') ?: 'ধারা',
                'order' => (int) ($entry->get('order') ?: 1),
                'icon' => $entry->get('icon') ?: 'fa-shield-halved',
                'title_bn' => $entry->get('title_bn') ?: 'সমিতির নিয়ম',
                'title_en' => $entry->get('title_en') ?: 'Somiti Rule',
                'description_bn' => $entry->get('description_bn') ?: '',
                'description_en' => $entry->get('description_en') ?: '',
            ];
        }

        usort($list, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        $this->cachedRules = $list;
        return $this->cachedRules;
    }

    /**
     * Overall Financial & Somiti Statistics
     */
    public function getFinancialOverview(): array
    {
        if ($this->cachedOverview !== null) {
            return $this->cachedOverview;
        }

        $settings = $this->getSettings();
        $members = $this->getAllMembers();
        $payments = $this->getPayments();

        $totalApprovedFund = 0;
        $totalPendingAmount = 0;
        $totalApprovedTransactions = 0;
        $totalPendingTransactions = 0;

        foreach ($payments as $p) {
            if ($p['status'] === 'approved') {
                $totalApprovedFund += $p['amount'];
                $totalApprovedTransactions++;
            } elseif ($p['status'] === 'pending') {
                $totalPendingAmount += $p['amount'];
                $totalPendingTransactions++;
            }
        }

        $totalDueAmountAll = 0;
        $totalOverdueMembers = 0;
        foreach ($members as $m) {
            $due = $this->calculateDue($m, (int) ($settings['monthly_installment'] ?? 1000));
            $totalDueAmountAll += $due['total_due_amount'];
            if ($due['is_overdue_2plus']) {
                $totalOverdueMembers++;
            }
        }

        $this->cachedOverview = [
            'total_members' => count($members),
            'total_approved_fund' => $totalApprovedFund,
            'total_pending_amount' => $totalPendingAmount,
            'total_approved_transactions' => $totalApprovedTransactions,
            'total_pending_transactions' => $totalPendingTransactions,
            'total_due_amount_all' => $totalDueAmountAll,
            'total_overdue_members' => $totalOverdueMembers,
            'monthly_installment' => (int) ($settings['monthly_installment'] ?? 1000),
            'est_year' => $settings['est_year'] ?? '2020',
        ];

        return $this->cachedOverview;
    }

    public function bnNumber($number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($en, $bn, (string) $number);
    }

    public function getRoleTitle(string $role, string $lang = 'bn'): string
    {
        $titles = [
            'superadmin' => [
                'bn' => 'সভাপতি (President)',
                'en' => 'President (Super Admin)',
            ],
            'admin' => [
                'bn' => 'সহ-সভাপতি (Vice President)',
                'en' => 'Vice President (Admin)',
            ],
            'cashier' => [
                'bn' => 'ক্যাশিয়ার (Cashier)',
                'en' => 'Treasurer (Cashier)',
            ],
            'member' => [
                'bn' => 'কার্যনির্বাহী সদস্য',
                'en' => 'Executive Member',
            ],
        ];

        return $titles[$role][$lang] ?? $role;
    }

    /**
     * Convert currency integer amount into Bengali words
     */
    public function amountInBnWords(int $amount): string
    {
        if ($amount <= 0) {
            return 'শূন্য টাকা মাত্র';
        }

        $ones = [
            0 => '', 1 => 'এক', 2 => 'দুই', 3 => 'তিন', 4 => 'চার', 5 => 'পাঁচ',
            6 => 'ছয়', 7 => 'সাত', 8 => 'আট', 9 => 'নয়', 10 => 'দশ',
            11 => 'এগারো', 12 => 'বারো', 13 => 'তেরো', 14 => 'চৌদ্দ', 15 => 'পনেরো',
            16 => 'ষোলো', 17 => 'সতেরো', 18 => 'আঠারো', 19 => 'উনিশ', 20 => 'বিশ',
            21 => 'একুশ', 22 => 'বাইশ', 23 => 'তেইশ', 24 => 'চব্বিশ', 25 => 'পঁচিশ',
            26 => 'ছাব্বিশ', 27 => 'সাতাশ', 28 => 'আঠাশ', 29 => 'উনত্রিশ', 30 => 'ত্রিশ',
            31 => 'একত্রিশ', 32 => 'বত্রিশ', 33 => 'তেত্রিশ', 34 => 'চৌত্রিশ', 35 => 'পঁয়ত্রিশ',
            36 => 'ছত্রিশ', 37 => 'সাঁইত্রিশ', 38 => 'আটত্রিশ', 39 => 'উনচল্লিশ', 40 => 'চল্লিশ',
            41 => 'একচল্লিশ', 42 => 'বিয়াল্লিশ', 43 => 'তেতাল্লিশ', 44 => 'চুয়াল্লিশ', 45 => 'পঁয়তাল্লিশ',
            46 => 'ছেচল্লিশ', 47 => 'সাতচল্লিশ', 48 => 'আটচল্লিশ', 49 => 'উনপঞ্চাশ', 50 => 'পঞ্চাশ',
            51 => 'একান্ন', 52 => 'বায়ান্ন', 53 => 'তিপ্পান্ন', 54 => 'চুয়ান্ন', 55 => 'পঞ্চান্ন',
            56 => 'ছাপ্পান্ন', 57 => 'সাতান্ন', 58 => 'আটান্ন', 59 => 'উনষাট', 60 => 'ষাট',
            61 => 'একষট্টি', 62 => 'বাষট্টি', 63 => 'তেষট্টি', 64 => 'চৌষট্টি', 65 => 'পঁয়ষট্টি',
            66 => 'ছেষট্টি', 67 => 'সাতষট্টি', 68 => 'আটষট্টি', 69 => 'উনসত্তর', 70 => 'সত্তর',
            71 => 'একাত্তর', 72 => 'বাহাত্তর', 73 => 'তিয়াত্তর', 74 => 'চুয়াত্তর', 75 => 'পঁচাত্তর',
            76 => 'ছিয়াত্তর', 77 => 'সাতাত্তর', 78 => 'আটাত্তর', 79 => 'উনআশি', 80 => 'আশি',
            81 => 'একাশি', 82 => 'বিরাশি', 83 => 'তিরাশি', 84 => 'চুরাশি', 85 => 'পঁচাশি',
            86 => 'ছিয়াশি', 87 => 'সাতাশি', 88 => 'অষ্টআশি', 89 => 'উননব্বই', 90 => 'নব্বই',
            91 => 'একানব্বই', 92 => 'বানব্বই', 93 => 'তিরানব্বই', 94 => 'চুরানব্বই', 95 => 'পঁচানব্বই',
            96 => 'ছিয়ানব্বই', 97 => 'সাতানব্বই', 98 => 'আটানব্বই', 99 => 'নিরানব্বই'
        ];

        $words = [];

        $crore = (int) ($amount / 10000000);
        $amount %= 10000000;

        $lakh = (int) ($amount / 100000);
        $amount %= 100000;

        $thousand = (int) ($amount / 1000);
        $amount %= 1000;

        $hundred = (int) ($amount / 100);
        $amount %= 100;

        if ($crore > 0) {
            $words[] = ($ones[$crore] ?? $crore) . ' কোটি';
        }
        if ($lakh > 0) {
            $words[] = ($ones[$lakh] ?? $lakh) . ' লাখ';
        }
        if ($thousand > 0) {
            $words[] = ($ones[$thousand] ?? $thousand) . ' হাজার';
        }
        if ($hundred > 0) {
            $words[] = ($ones[$hundred] ?? $hundred) . ' শত';
        }
        if ($amount > 0) {
            $words[] = $ones[$amount] ?? (string) $amount;
        }

        return trim(implode(' ', $words)) . ' টাকা মাত্র';
    }
}

