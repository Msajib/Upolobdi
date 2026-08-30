<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SomitiService;
use Statamic\Facades\User;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected SomitiService $somitiService;

    public function __construct(SomitiService $somitiService)
    {
        $this->somitiService = $somitiService;
    }

    /**
     * Role-Adaptive Main Portal / Dashboard
     */
    public function index()
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $userRole = $this->somitiService->getUserRole($currentUser);

        $settings = $this->somitiService->getSettings();
        $monthlyAmount = (int) ($settings['monthly_installment'] ?? 1000);
        $overview = $this->somitiService->getFinancialOverview();
        $allMembers = $this->somitiService->getAllMembers();

        $currentMemberData = $this->somitiService->getMemberByEmail($currentUser->email());
        $userDue = $currentMemberData ? $this->somitiService->calculateDue($currentMemberData, $monthlyAmount) : null;

        // Rejected payments for current user to show alert
        $rejectedPayments = Entry::query()
            ->where('collection', 'payments')
            ->where('member_email', $currentUser->email())
            ->get()
            ->filter(fn($entry) => ($entry->get('payment_status') ?: $entry->get('status')) === 'rejected')
            ->map(function ($entry) {
                return [
                    'id' => $entry->id(),
                    'title' => $entry->get('title'),
                    'amount' => $entry->get('amount'),
                    'payment_months' => $entry->get('payment_months'),
                    'reference_number' => $entry->get('reference_number'),
                    'rejection_reason' => $entry->get('rejection_reason') ?: 'তথ্য অসম্পূর্ণ বা পেমেন্ট ট্রানজেকশন যাচাই করা যায়নি।',
                    'reviewed_by' => $entry->get('reviewed_by'),
                    'date' => $entry->get('payment_date'),
                ];
            })->values()->all();

        // Ledgers based on role
        $allPayments = $this->somitiService->getPayments();
        $userPayments = $this->somitiService->getPayments($currentUser->email());

        // Pending payments for Cashier & Superadmin
        $pendingPayments = array_filter($allPayments, fn($p) => $p['status'] === 'pending');

        // Overdue members list
        $allMembersDue = [];
        foreach ($allMembers as $m) {
            $allMembersDue[] = $this->somitiService->calculateDue($m, $monthlyAmount);
        }

        $events = $this->somitiService->getEvents();
        $rules = $this->somitiService->getRules();
        $projects = $this->somitiService->getProjects();
        $announcements = $this->somitiService->getAnnouncements();

        return view('dashboard', compact(
            'currentUser',
            'userRole',
            'settings',
            'overview',
            'allMembers',
            'currentMemberData',
            'userDue',
            'rejectedPayments',
            'allPayments',
            'userPayments',
            'pendingPayments',
            'allMembersDue',
            'events',
            'rules',
            'projects',
            'announcements'
        ));
    }

    /**
     * Normalize payment inputs (Bengali digits to English, strip formatting, normalize method & date)
     */
    protected function normalizePaymentInputs(Request $request): void
    {
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $data = [];

        if ($request->has('amount')) {
            $amt = (string) $request->input('amount');
            $amt = str_replace($bn, $en, $amt);
            $amt = preg_replace('/[^0-9.]/', '', $amt);
            $data['amount'] = $amt !== '' ? (float) $amt : null;
        }

        if ($request->has('months_count')) {
            $m = (string) $request->input('months_count');
            $m = str_replace($bn, $en, $m);
            $m = preg_replace('/[^0-9]/', '', $m);
            $data['months_count'] = $m !== '' ? (int) $m : null;
        }

        if ($request->has('payment_method')) {
            $pm = strtolower(trim((string) $request->input('payment_method')));
            $pmMap = [
                'cash' => 'cash',
                'direct cash' => 'cash',
                'নগদ গ্রহণ' => 'cash',
                'bkash' => 'bkash',
                'বিকাশ' => 'bkash',
                'nagad' => 'nagad',
                'নগদ' => 'nagad',
                'rocket' => 'rocket',
                'রকেট' => 'rocket',
                'bank' => 'bank',
                'bank transfer' => 'bank',
                'ব্যাংক' => 'bank',
            ];
            $data['payment_method'] = $pmMap[$pm] ?? $pm;
        }

        if ($request->has('payment_date')) {
            $pDate = (string) $request->input('payment_date');
            $pDate = str_replace($bn, $en, $pDate);
            $parsedDate = date('Y-m-d', strtotime(trim($pDate)));
            if ($parsedDate && $parsedDate !== '1970-01-01') {
                $data['payment_date'] = $parsedDate;
            }
        }

        if ($request->has('reference_number')) {
            $ref = trim((string) $request->input('reference_number'));
            $data['reference_number'] = $ref !== '' ? $ref : ('CASH-REC-' . date('Ymd') . '-' . rand(100, 999));
        } else {
            $data['reference_number'] = 'CASH-REC-' . date('Ymd') . '-' . rand(100, 999);
        }

        if (!empty($data)) {
            $request->merge($data);
        }
    }

    /**
     * Create Direct Payment Entry by Cashier or Super Admin (e.g. Cash Deposit)
     */
    public function createDirectPayment(Request $request)
    {
        $currentUser = Auth::user();
        $userRole = $this->somitiService->getUserRole($currentUser);
        $isSuper = $userRole === 'superadmin';
        $isCashier = $userRole === 'cashier';

        if (! $isSuper && ! $isCashier) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Cashier or President Only)']);
        }

        $this->normalizePaymentInputs($request);

        $validated = $request->validate([
            'member_email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'months_count' => 'nullable|integer|min:1|max:60',
            'payment_method' => 'required|string|in:cash,bkash,nagad,rocket,bank',
            'reference_number' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'proof_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,bmp,heic,heif,pdf|max:20480',
        ], [
            'member_email.required' => 'সদস্যের ইমেইল নির্বাচন করা আবশ্যক।',
            'amount.required' => 'টাকার পরিমাণ প্রদান করা আবশ্যক।',
            'amount.min' => 'টাকার পরিমাণ ন্যূনতম ১ টাকা হতে হবে।',
            'payment_method.required' => 'পেমেন্ট মাধ্যম সিলেক্ট করুন।',
            'payment_date.required' => 'জমার তারিখ প্রদান করুন।',
            'proof_image.file' => 'প্রমাণপত্র অবশ্যই একটি ফাইল (ছবি বা রসিদ) হতে হবে।',
            'proof_image.max' => 'ফাইলের সাইজ সর্বোচ্চ ২০ মেগাবাইট (20MB) হতে পারে।',
        ]);

        $settings = $this->somitiService->getSettings();
        $monthlyRate = (int) ($settings['monthly_installment'] ?? 1000);
        $amount = (int) $validated['amount'];
        $monthsCount = !empty($validated['months_count']) ? (int) $validated['months_count'] : max(1, (int) round($amount / $monthlyRate));

        $memberData = $this->somitiService->getMemberByEmail($validated['member_email']);
        if (! $memberData) {
            return back()->withErrors(['error' => 'নির্দিষ্ট সদস্য পাওয়া যায়নি (Member not found)']);
        }

        $dueInfo = $this->somitiService->calculateDue($memberData, $monthlyRate);
        $monthsDescription = "{$monthsCount} মাসের কিস্তি";
        if (!empty($dueInfo['unpaid_months_bn'])) {
            $coveringBn = array_slice($dueInfo['unpaid_months_bn'], 0, $monthsCount);
            if (count($coveringBn) > 0) {
                $monthsDescription = implode(', ', $coveringBn);
            }
        }

        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = 'pay_proof_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $proofImagePath = '/uploads/payments/' . $filename;
        }

        $entryRoleText = $isSuper ? 'সজিব মোল্লা (সভাপতি)' : 'সাইফুল ইসলাম (ক্যাশিয়ার)';
        $entryTypeKey = $isSuper ? 'superadmin' : 'cashier';

        $slug = 'pay-direct-' . Str::slug($memberData['name']) . '-' . time();
        $title = 'সরাসরি কিস্তি জমা: ' . $memberData['bangla_name'] . " ({$monthsDescription})";

        $receiptNo = 'USS-REC-' . date('Y') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));

        $entry = Entry::make()
            ->collection('payments')
            ->slug($slug)
            ->data([
                'title' => $title,
                'user_id' => $memberData['email'],
                'member_email' => $memberData['email'],
                'amount' => $amount,
                'months_count' => $monthsCount,
                'payment_months' => $monthsDescription,
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
                'payment_date' => $validated['payment_date'],
                'proof_image' => $proofImagePath,
                'payment_status' => 'approved',
                'status' => 'approved',
                'entry_type' => $entryTypeKey,
                'entry_created_by' => $entryRoleText,
                'reviewed_by' => $entryRoleText,
                'reviewed_at' => Carbon::now()->format('Y-m-d H:i'),
                'receipt_number' => $receiptNo,
                'receipt_generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'receipt_authorized_by' => $entryRoleText,
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', "সদস্য {$memberData['bangla_name']}-এর নামে {$amount} টাকার কিস্তি এন্ট্রি ও অনুমোদন সফল হয়েছে!");
    }

    /**
     * Export & Printable Ledger Report with Signatures
     */
    public function exportLedger(Request $request)
    {
        $currentUser = Auth::user();
        $userRole = $this->somitiService->getUserRole($currentUser);
        $isSuper = $userRole === 'superadmin';
        $isAdmin = $userRole === 'admin';
        $isCashier = $userRole === 'cashier';

        if (! $isSuper && ! $isAdmin && ! $isCashier) {
            return redirect()->route('dashboard')->withErrors(['error' => 'লেজার ডাউনলোডের অনুমতি নেই']);
        }

        $fromMonth = $request->query('from_month');
        $toMonth = $request->query('to_month');
        $statusFilter = $request->query('status', 'approved');

        $allPayments = $this->somitiService->getPayments();

        $filteredPayments = array_values(array_filter($allPayments, function ($p) use ($fromMonth, $toMonth, $statusFilter) {
            if ($statusFilter !== 'all' && $p['status'] !== $statusFilter) {
                return false;
            }
            if ($fromMonth && strcmp(substr($p['payment_date'], 0, 7), $fromMonth) < 0) {
                return false;
            }
            if ($toMonth && strcmp(substr($p['payment_date'], 0, 7), $toMonth) > 0) {
                return false;
            }
            return true;
        }));

        $totalAmount = array_sum(array_column($filteredPayments, 'amount'));
        $settings = $this->somitiService->getSettings();
        $members = $this->somitiService->getAllMembers();

        // Pagination: 12 per page
        $perPage = 12;
        $page = max(1, (int) $request->query('page', 1));
        $totalRecords = count($filteredPayments);
        $totalPages = max(1, ceil($totalRecords / $perPage));
        $page = min($page, $totalPages);
        $pagedPayments = array_slice($filteredPayments, ($page - 1) * $perPage, $perPage);

        return view('ledger-export', compact(
            'pagedPayments',
            'filteredPayments',
            'totalAmount',
            'fromMonth',
            'toMonth',
            'statusFilter',
            'settings',
            'members',
            'currentUser',
            'page',
            'totalPages',
            'totalRecords',
            'perPage'
        ));
    }

    /**
     * Update Logo, Favicon, Name - President Only
     */
    public function updateLogo(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser->isSuper() && ! $currentUser->hasRole('superadmin')) {
            return back()->withErrors(['error' => 'শুধুমাত্র সভাপতি লোগো পরিবর্তন করতে পারেন']);
        }

        $updateData = [];

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'user-logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $filename);
            $updateData['custom_logo'] = '/assets/images/' . $filename;
        }

        if ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $filename = 'user-favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $filename);
            $updateData['custom_favicon'] = '/assets/images/' . $filename;
        }

        $textFields = ['somiti_name_bn', 'somiti_name_en', 'motto_bn', 'motto_en', 'est_year', 'contact_phone', 'contact_email', 'contact_address', 'bkash_number', 'nagad_number', 'rocket_number', 'bank_details', 'due_day_of_month'];
        foreach ($textFields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        if (! empty($updateData)) {
            $this->somitiService->updateSettings($updateData);
        }

        return back()->with('success', 'সমিতির লোগো, নাম ও যোগাযোগ তথ্য সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Update Member Committee Roles - President Only
     * Allows President to assign any role (President, VP, Cashier, Member) to any user
     */
    public function updateMemberRoles(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser->isSuper() && ! $currentUser->hasRole('superadmin')) {
            return back()->withErrors(['error' => 'শুধুমাত্র সভাপতি পদবী পরিবর্তন করতে পারেন']);
        }

        $validated = $request->validate([
            'assignments' => 'required|array',
            'assignments.*.email' => 'required|email',
            'assignments.*.role' => 'required|in:superadmin,admin,cashier,member',
            'assignments.*.designation' => 'nullable|string|max:100',
            'assignments.*.bangla_designation' => 'nullable|string|max:100',
        ]);

        foreach ($validated['assignments'] as $assignment) {
            $targetUser = \Statamic\Facades\User::findByEmail($assignment['email']);
            if (! $targetUser) {
                continue;
            }

            // Clear existing roles then set new one
            $existingRoles = $targetUser->roles()->map->handle()->all();
            foreach ($existingRoles as $existingRole) {
                $targetUser->removeRole($existingRole);
            }

            if ($assignment['role'] !== 'superadmin') {
                $targetUser->assignRole($assignment['role']);
            }

            if (! empty($assignment['designation'])) {
                $targetUser->set('designation', $assignment['designation']);
            }
            if (! empty($assignment['bangla_designation'])) {
                $targetUser->set('designation_bn', $assignment['bangla_designation']);
            }

            $targetUser->save();
        }

        return back()->with('success', 'কমিটির পদবী ও দায়িত্ব সফলভাবে পুনর্বিন্যাস করা হয়েছে!');
    }

    /**
     * Update Event (toggle masonry_selected, highlighted)
     */
    public function updateEvent(Request $request, $id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'ইভেন্ট পাওয়া যায়নি']);
        }

        if ($request->has('is_masonry_selected')) {
            $entry->set('is_masonry_selected', $request->boolean('is_masonry_selected'));
        }
        if ($request->has('is_highlighted')) {
            $entry->set('is_highlighted', $request->boolean('is_highlighted'));
        }
        $entry->save();

        return back()->with('success', 'ইভেন্ট সেটিং আপডেট হয়েছে!');
    }

    /**
     * Projects Management: Create
     */
    public function createProject(Request $request)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (President / Vice President Only)']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'title_bn' => 'required|string|max:200',
            'status' => 'required|in:ongoing,planned,completed',
            'target_amount' => 'nullable|string|max:100',
            'timeline' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:150',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
        ], [
            'title.required' => 'প্রকল্পের ইংরেজি নাম দিন।',
            'title_bn.required' => 'প্রকল্পের বাংলা নাম দিন।',
            'status.required' => 'প্রকল্পের অবস্থা নির্বাচন করুন।',
            'image_file.image' => 'প্রকল্পের ফাইল অবশ্যই ছবি হতে হবে।',
            'image_file.mimes' => 'ছবির ফরম্যাট JPG, PNG, WebP বা GIF হতে হবে।',
            'image_file.max' => 'ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে।',
        ]);

        $imagePath = $validated['image_url'] ?: 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&auto=format&fit=crop&q=80';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'project_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects'), $filename);
            $imagePath = '/uploads/projects/' . $filename;
        }

        $slug = 'project-' . Str::slug($validated['title']) . '-' . time();

        $entry = Entry::make()
            ->collection('projects')
            ->slug($slug)
            ->data([
                'title' => $validated['title'],
                'title_bn' => $validated['title_bn'],
                'status' => $validated['status'],
                'target_amount' => $validated['target_amount'] ?: 'বাজেট নির্ধারণাধীন',
                'timeline' => $validated['timeline'] ?: '২০২৫ - ২০২৬',
                'location' => $validated['location'] ?: 'বাংলাদেশ',
                'image' => $imagePath,
                'description' => $validated['description'] ?? '',
                'description_bn' => $validated['description_bn'] ?? '',
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'নতুন বিনিয়োগ প্রকল্প সফলভাবে যোগ করা হয়েছে! (Project Added)');
    }

    /**
     * Projects Management: Update
     */
    public function updateProject(Request $request, $id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'title_bn' => 'required|string|max:200',
            'status' => 'required|in:ongoing,planned,completed',
            'target_amount' => 'nullable|string|max:100',
            'timeline' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:150',
            'image_url' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
        ], [
            'title.required' => 'প্রকল্পের ইংরেজি নাম দিন।',
            'title_bn.required' => 'প্রকল্পের বাংলা নাম দিন।',
            'status.required' => 'প্রকল্পের অবস্থা নির্বাচন করুন।',
            'image_file.image' => 'প্রকল্পের ফাইল অবশ্যই ছবি হতে হবে।',
            'image_file.mimes' => 'ছবির ফরম্যাট JPG, PNG, WebP বা GIF হতে হবে।',
            'image_file.max' => 'ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে।',
        ]);

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'প্রকল্প পাওয়া যায়নি']);
        }

        $imagePath = $validated['image_url'] ?: $entry->get('image');
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'project_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/projects'), $filename);
            $imagePath = '/uploads/projects/' . $filename;
        }

        $entry->set('title', $validated['title']);
        $entry->set('title_bn', $validated['title_bn']);
        $entry->set('status', $validated['status']);
        $entry->set('target_amount', $validated['target_amount']);
        $entry->set('timeline', $validated['timeline']);
        $entry->set('location', $validated['location']);
        $entry->set('image', $imagePath);
        $entry->set('description', $validated['description']);
        $entry->set('description_bn', $validated['description_bn']);
        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'প্রকল্পের তথ্য সফলভাবে হালনাগাদ করা হয়েছে! (Project Updated)');
    }

    /**
     * Projects Management: Delete
     */
    public function deleteProject($id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস']);
        }

        $entry = Entry::find($id);
        if ($entry) {
            $entry->delete();
            $this->somitiService->clearCache();
        }

        return back()->with('info', 'প্রকল্প মুছে ফেলা হয়েছে! (Project Deleted)');
    }

    /**
     * Update Global Settings & Bilingual Content
     */
    public function updateSettings(Request $request)
    {
        $currentUser = Auth::user();
        $userRole = $this->somitiService->getUserRole($currentUser);
        $isSuper = $userRole === 'superadmin';
        $isCashier = $userRole === 'cashier';

        if (! $isSuper && ! $isCashier) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Unauthorized)']);
        }

        $updateData = [];

        if ($request->has('due_warning_modal_enabled')) {
            $updateData['due_warning_modal_enabled'] = $request->boolean('due_warning_modal_enabled');
        }

        // Cashier & Superadmin can update payment credentials
        $paymentAccountFields = [
            'bkash_number',
            'nagad_number',
            'rocket_number',
            'bank_details',
        ];
        foreach ($paymentAccountFields as $f) {
            if ($request->has($f)) {
                $updateData[$f] = $request->input($f);
            }
        }

        if ($isSuper) {
            $fields = [
                'monthly_installment',
                'due_day_of_month',
                'contact_phone',
                'contact_email',
                'contact_address',
                'motto_bn',
                'motto_en',
                'vision_desc_bn',
                'vision_desc_en',
                'footer_desc_bn',
                'footer_desc_en',
            ];

            foreach ($fields as $f) {
                if ($request->has($f)) {
                    $updateData[$f] = $request->input($f);
                }
            }
        }

        $this->somitiService->updateSettings($updateData);

        return back()->with('success', 'সেটিংস ও ওয়েবসাইটের তথ্য সফলভাবে সংরক্ষিত হয়েছে! (Settings & Content Updated)');
    }

    /**
     * Submit Payment Slip
     */
    public function submitPayment(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $this->normalizePaymentInputs($request);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'months_count' => 'nullable|integer|min:1|max:60',
            'payment_method' => 'required|string|in:bkash,nagad,rocket,bank,cash',
            'reference_number' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'proof_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,bmp,heic,heif,pdf|max:20480',
        ], [
            'amount.required' => 'টাকার পরিমাণ প্রদান করা আবশ্যক।',
            'amount.min' => 'টাকার পরিমাণ ন্যূনতম ১ টাকা হতে হবে।',
            'payment_method.required' => 'পেমেন্ট মাধ্যম সিলেক্ট করুন।',
            'payment_date.required' => 'জমার তারিখ প্রদান করুন।',
            'proof_image.file' => 'পেমেন্ট স্লিপ অবশ্যই ছবি বা ফাইল (JPG, PNG, WebP, PDF) হতে হবে।',
            'proof_image.max' => 'ফাইলের সাইজ সর্বোচ্চ ২০ মেগাবাইট (20MB) হতে পারে।',
        ]);

        $settings = $this->somitiService->getSettings();
        $monthlyRate = (int) ($settings['monthly_installment'] ?? 1000);
        $amount = (int) $validated['amount'];
        $monthsCount = !empty($validated['months_count']) ? (int) $validated['months_count'] : max(1, (int) round($amount / $monthlyRate));

        // Calculate member's current due and which unpaid months are being cleared (LIFO / FIFO earliest unpaid)
        $memberData = $this->somitiService->getMemberByEmail($currentUser->email());
        $dueInfo = $memberData ? $this->somitiService->calculateDue($memberData, $monthlyRate) : null;
        
        $monthsDescription = "{$monthsCount} মাসের কিস্তি";
        if ($dueInfo && !empty($dueInfo['unpaid_months_bn'])) {
            $coveringBn = array_slice($dueInfo['unpaid_months_bn'], 0, $monthsCount);
            if (count($coveringBn) > 0) {
                $monthsDescription = implode(', ', $coveringBn);
            }
        }

        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = 'pay_proof_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $proofImagePath = '/uploads/payments/' . $filename;
        }

        $slug = 'pay-' . Str::slug($currentUser->get('name') ?: 'member') . '-' . time();
        $title = 'কিস্তি জমা: ' . ($currentUser->get('bangla_name') ?: $currentUser->get('name') ?: $currentUser->email()) . " ({$monthsDescription})";

        $entry = Entry::make()
            ->collection('payments')
            ->slug($slug)
            ->data([
                'title' => $title,
                'user_id' => $currentUser->id(),
                'member_email' => $currentUser->email(),
                'amount' => $amount,
                'months_count' => $monthsCount,
                'payment_months' => $monthsDescription,
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
                'payment_date' => $validated['payment_date'],
                'proof_image' => $proofImagePath,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'পেমেন্ট ভাউচার সফলভাবে জমা হয়েছে! ক্যাশিয়ার বা সভাপতি যাচাই করে অনুমোদন করবেন।');
    }

    /**
     * Review Payment (Approve or Reject with possible adjustment) - Cashier or Superadmin
     */
    public function reviewPayment(Request $request, $id)
    {
        $currentUser = Auth::user();
        $userRole = $this->somitiService->getUserRole($currentUser);
        $isSuper = $userRole === 'superadmin';
        $isCashier = $userRole === 'cashier';

        if (! $isSuper && ! $isCashier) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Unauthorized action)']);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'adjusted_amount' => 'nullable|numeric|min:100',
            'adjusted_months_count' => 'nullable|integer|min:1|max:36',
            'rejection_reason' => 'nullable|string|max:500',
        ], [
            'action.required' => 'অনুমোদন বা বাতিলের সিদ্ধান্ত নির্ধারণ করুন।',
            'adjusted_amount.min' => 'সংশোধিত পরিমাণ ন্যূনতম ১০০ টাকা হতে হবে।',
        ]);

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'পেমেন্ট রেকর্ড পাওয়া যায়নি (Payment not found)']);
        }

        $reviewerRole = $isSuper ? 'সভাপতি (Super Admin)' : 'ক্যাশিয়ার (Cashier)';
        $reviewerName = ($currentUser->get('bangla_name') ?: $currentUser->get('name')) . " ({$reviewerRole})";

        if ($validated['action'] === 'approve') {
            // Apply adjustments if Cashier/Superadmin modified the amount/months
            if (!empty($validated['adjusted_amount'])) {
                $entry->set('amount', (int) $validated['adjusted_amount']);
            }
            if (!empty($validated['adjusted_months_count'])) {
                $entry->set('months_count', (int) $validated['adjusted_months_count']);
            }

            // Generate immutable receipt number once upon approval
            if (! $entry->get('receipt_number')) {
                $receiptNo = 'USS-REC-' . date('Y') . '-' . strtoupper(substr(md5(uniqid($entry->id(), true)), 0, 6));
                $entry->set('receipt_number', $receiptNo);
                $entry->set('receipt_generated_at', Carbon::now()->format('Y-m-d H:i:s'));
                $entry->set('receipt_authorized_by', $reviewerName);
            }

            $entry->set('payment_status', 'approved');
            $entry->set('status', 'approved');
            $entry->set('reviewed_by', $reviewerName);
            $entry->set('reviewed_at', Carbon::now()->format('Y-m-d H:i'));
            $entry->set('rejection_reason', null);
            $entry->save();
            $this->somitiService->clearCache();

            return back()->with('success', 'পেমেন্ট সফলভাবে অনুমোদিত হয়েছে ও মানি রসিদ তৈরি হয়েছে! (Payment Approved & Receipt Generated)');
        } else {
            $entry->set('payment_status', 'rejected');
            $entry->set('status', 'rejected');
            $entry->set('reviewed_by', $reviewerName);
            $entry->set('reviewed_at', Carbon::now()->format('Y-m-d H:i'));
            $entry->set('rejection_reason', $validated['rejection_reason'] ?: 'সঠিক ট্রানজেকশন প্রমাণ পাওয়া যায়নি। অনুগ্রহ করে সঠিক তথ্য দিয়ে পুনরায় জমা দিন।');
            $entry->save();
            $this->somitiService->clearCache();

            return back()->with('info', 'পেমেন্ট বাতিল করা হয়েছে এবং সদস্যকে কারণ জানানো হয়েছে। (Payment Rejected)');
        }
    }

    /**
     * Grant Due Exemption (Cashier max 6 months, Superadmin max 12 months)
     */
    public function grantExemption(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isCashier = $currentUser->hasRole('cashier');

        if (! $isSuper && ! $isCashier) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Unauthorized action)']);
        }

        $maxMonths = $isSuper ? 12 : 6;

        $validated = $request->validate([
            'exemption_months' => "required|integer|min:0|max:{$maxMonths}",
            'exemption_reason' => 'required|string|max:300',
        ], [
            'exemption_months.required' => 'অবকাশের মাস সংখ্যা উল্লেখ করুন।',
            'exemption_months.max' => "অবকাশের মেয়াদ সর্বোচ্চ {$maxMonths} মাস হতে পারে।",
            'exemption_reason.required' => 'অবকাশ অনুমোদনের কারণ বা বিবরণ প্রদান করুন।',
        ]);

        $targetUser = User::find($userId);
        if (! $targetUser) {
            return back()->withErrors(['error' => 'সদস্য পাওয়া যায়নি (User not found)']);
        }

        $approverRole = $isSuper ? 'সভাপতি (Super Admin)' : 'ক্যাশিয়ার (Cashier)';
        $approverText = ($currentUser->get('bangla_name') ?: $currentUser->get('name')) . " ({$approverRole})";

        $targetUser->set('exemption_months', (int) $validated['exemption_months']);
        $targetUser->set('exemption_approved_by', $approverText);
        $targetUser->set('exemption_reason', $validated['exemption_reason']);
        $targetUser->save();
        $this->somitiService->clearCache();

        return back()->with('success', "বকেয়া অবকাশ সফলভাবে নির্ধারণ করা হয়েছে ({$validated['exemption_months']} মাস)। (Due exemption granted)");
    }

    /**
     * Add New Member - Superadmin Only
     */
    public function addMember(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser->isSuper() && ! $currentUser->hasRole('superadmin')) {
            return back()->withErrors(['error' => 'শুধুমাত্র সভাপতি নতুন সদস্য যুক্ত করতে পারেন (Super Admin Only)']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:superadmin,admin,cashier,member',
            'designation' => 'required|string|max:100',
            'member_id' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'সদস্যের নাম (English) দিন।',
            'bangla_name.required' => 'সদস্যের নাম (বাংলা) দিন।',
            'email.required' => 'ইমেইল অ্যাড্রেস দিন।',
            'email.email' => 'সঠিক ইমেইল ফরম্যাট প্রদান করুন।',
            'phone.required' => 'মোবাইল নম্বর দিন।',
            'role.required' => 'কমিটির পদ নির্ধারণ করুন।',
            'member_id.required' => 'মেম্বার আইডি কোড দিন।',
            'password.required' => 'পাসওয়ার্ড প্রদান করুন।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);

        $existing = User::findByEmail($validated['email']);
        if ($existing) {
            return back()->withErrors(['email' => 'এই ইমেইলটি ইতিমধ্যে নিবন্ধিত আছে (Email already exists)']);
        }

        $user = User::make();
        $user->email($validated['email']);
        $user->data([
            'name' => $validated['name'],
            'bangla_name' => $validated['bangla_name'],
            'phone' => $validated['phone'],
            'designation' => $validated['designation'],
            'member_id' => $validated['member_id'],
            'joined_date' => Carbon::now()->format('Y-m-d'),
            'avatar' => '/assets/images/avatar-default.svg',
            'exemption_months' => 0,
            'is_active' => true,
        ]);

        $user->roles([$validated['role']]);
        if ($validated['role'] === 'superadmin') {
            $user->makeSuper();
        }
        $user->password($validated['password']);
        $user->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'নতুন সদস্য সফলভাবে যুক্ত করা হয়েছে! (New Member Added Successfully)');
    }

    /**
     * Edit Member Status / Password Reset - Super Admin Only
     */
    public function updateMember(Request $request, $userId)
    {
        $currentUser = Auth::user();
        if (! $currentUser->isSuper() && ! $currentUser->hasRole('superadmin')) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Super Admin Only)']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'bangla_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:100',
            'role' => 'required|in:superadmin,admin,cashier,member',
            'new_password' => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'সদস্যের নাম আবশ্যক।',
            'role.required' => 'পদবী নির্ধারণ করুন।',
            'new_password.min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।',
        ]);

        $targetUser = User::find($userId);
        if (! $targetUser) {
            return back()->withErrors(['error' => 'সদস্য পাওয়া যায়নি (User not found)']);
        }

        $targetUser->set('name', $validated['name']);
        if (!empty($validated['bangla_name'])) {
            $targetUser->set('bangla_name', $validated['bangla_name']);
        }
        if (!empty($validated['phone'])) {
            $targetUser->set('phone', $validated['phone']);
        }
        if (!empty($validated['designation'])) {
            $targetUser->set('designation', $validated['designation']);
        }
        $targetUser->set('is_active', $request->boolean('is_active', true));

        $targetUser->roles([$validated['role']]);
        if ($validated['role'] === 'superadmin') {
            $targetUser->makeSuper();
        }

        if (!empty($validated['new_password'])) {
            $targetUser->password($validated['new_password']);
        }

        $targetUser->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'সদস্যের তথ্য সফলভাবে হালনাগাদ করা হয়েছে! (Member details updated)');
    }

    /**
     * Add / Edit Rules - Super Admin & Admin
     */
    public function createRule(Request $request)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:200',
            'title_en' => 'required|string|max:200',
            'rule_number' => 'required|string|max:50',
            'order' => 'required|integer',
            'icon' => 'nullable|string|max:50',
            'description_bn' => 'required|string|max:2000',
            'description_en' => 'required|string|max:2000',
        ], [
            'title_bn.required' => 'ধারার বাংলা শিরোনাম দিন।',
            'title_en.required' => 'ধারার ইংরেজি শিরোনাম দিন।',
            'rule_number.required' => 'ধারার ক্রম/নম্বর দিন।',
            'order.required' => 'ধারার সাজানোর ক্রম দিন।',
            'description_bn.required' => 'ধারার বিস্তারিত বিবরণ (বাংলা) দিন।',
            'description_en.required' => 'ধারার বিস্তারিত বিবরণ (English) দিন।',
        ]);

        $slug = 'rule-' . time() . '-' . Str::slug($validated['title_en']);

        $entry = Entry::make()
            ->collection('rules')
            ->slug($slug)
            ->data([
                'title_bn' => $validated['title_bn'],
                'title_en' => $validated['title_en'],
                'rule_number' => $validated['rule_number'],
                'order' => (int) $validated['order'],
                'icon' => $validated['icon'] ?: 'fa-shield-halved',
                'description_bn' => $validated['description_bn'],
                'description_en' => $validated['description_en'],
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'নতুন ধারা/নীতিমালা সফলভাবে যুক্ত করা হয়েছে! (New Rule Added)');
    }

    public function updateRule(Request $request, $id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:200',
            'title_en' => 'required|string|max:200',
            'rule_number' => 'required|string|max:50',
            'order' => 'required|integer',
            'icon' => 'nullable|string|max:50',
            'description_bn' => 'required|string|max:2000',
            'description_en' => 'required|string|max:2000',
        ], [
            'title_bn.required' => 'ধারার বাংলা শিরোনাম দিন।',
            'title_en.required' => 'ধারার ইংরেজি শিরোনাম দিন।',
            'rule_number.required' => 'ধারার ক্রম/নম্বর দিন।',
            'description_bn.required' => 'ধারার বিস্তারিত বিবরণ (বাংলা) দিন।',
            'description_en.required' => 'ধারার বিস্তারিত বিবরণ (English) দিন।',
        ]);

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'ধারা পাওয়া যায়নি (Rule not found)']);
        }

        $entry->data(array_merge($entry->data()->all(), [
            'title_bn' => $validated['title_bn'],
            'title_en' => $validated['title_en'],
            'rule_number' => $validated['rule_number'],
            'order' => (int) $validated['order'],
            'icon' => $validated['icon'] ?: 'fa-shield-halved',
            'description_bn' => $validated['description_bn'],
            'description_en' => $validated['description_en'],
        ]));

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'নীতিমালা সফলভাবে আপডেট করা হয়েছে! (Rule updated)');
    }

    public function deleteRule($id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $entry = Entry::find($id);
        if ($entry) {
            $entry->delete();
            $this->somitiService->clearCache();
        }

        return back()->with('info', 'ধারা মুছে ফেলা হয়েছে! (Rule Deleted)');
    }

    /**
     * Add Event & Activity - Admin or Super Admin
     */
    public function createEvent(Request $request)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'title_bn' => 'required|string|max:200',
            'event_date' => 'required|date',
            'location' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'description_bn' => 'nullable|string|max:1000',
            'is_highlighted' => 'nullable|boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ], [
            'title.required' => 'ইভেন্টের ইংরেজি নাম দিন।',
            'title_bn.required' => 'ইভেন্টের বাংলা নাম দিন।',
            'event_date.required' => 'ইভেন্টের তারিখ নির্বাচন করুন।',
            'location.required' => 'ইভেন্টের স্থান দিন।',
            'image_file.image' => 'ইভেন্টের কভার ছবি অবশ্যই ছবি ফাইল হতে হবে।',
            'image_file.mimes' => 'ছবির ফরম্যাট JPG, PNG, WebP বা GIF হতে হবে।',
            'image_file.max' => 'ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে।',
            'gallery_files.*.image' => 'গ্যালারির প্রতিটি ফাইল ছবি হতে হবে।',
            'gallery_files.*.max' => 'গ্যালারির প্রতিটি ছবির সাইজ সর্বোচ্চ ১০ মেগাবাইট (10MB) হতে পারে।',
        ]);

        $imagePath = '/assets/images/events/annual_meetup.jpg';
        $galleryImages = [];

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'event_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/events'), $filename);
            $imagePath = '/uploads/events/' . $filename;
            $galleryImages[] = $imagePath;
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $gFile) {
                $gName = 'event_g_' . time() . '_' . Str::random(5) . '.' . $gFile->getClientOriginalExtension();
                $gFile->move(public_path('uploads/events'), $gName);
                $galleryImages[] = '/uploads/events/' . $gName;
            }
        }

        if (empty($galleryImages)) {
            $galleryImages = [$imagePath];
        }

        $slug = 'event-' . Str::slug($validated['title']) . '-' . time();

        $entry = Entry::make()
            ->collection('events')
            ->slug($slug)
            ->data([
                'title' => $validated['title'],
                'title_bn' => $validated['title_bn'],
                'event_date' => $validated['event_date'],
                'location' => $validated['location'],
                'image' => $imagePath,
                'is_highlighted' => $request->boolean('is_highlighted', true),
                'gallery_images' => $galleryImages,
                'description' => $validated['description'] ?? '',
                'description_bn' => $validated['description_bn'] ?? '',
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'নতুন ইভেন্ট/কার্যক্রম সফলভাবে প্রকাশ করা হয়েছে! (Event published)');
    }

    public function deleteEvent($id)
    {
        $currentUser = Auth::user();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');
        $isAdmin = $currentUser->hasRole('admin');

        if (! $isSuper && ! $isAdmin) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস (Admin Only)']);
        }

        $entry = Entry::find($id);
        if ($entry) {
            $entry->delete();
            $this->somitiService->clearCache();
        }

        return back()->with('info', 'ইভেন্ট মুছে ফেলা হয়েছে! (Event Deleted)');
    }

    /**
     * Announcement Management: Create (President, Cashier, VP)
     */
    public function createAnnouncement(Request $request)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $roles = $currentUser->roles()->map->handle()->all();
        $userRole = $currentUser->isSuper() ? 'superadmin' : ($roles[0] ?? 'member');

        if (! in_array($userRole, ['superadmin', 'cashier', 'admin'])) {
            return back()->withErrors(['error' => 'শুধুমাত্র সভাপতি, ক্যাশিয়ার বা সহ-সভাপতি ঘোষণাপত্র জারি করতে পারেন।']);
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'body_bn' => 'required|string',
            'body_en' => 'nullable|string',
            'announcement_date' => 'nullable|date',
        ], [
            'title_bn.required' => 'ঘোষণাপত্রের বাংলা শিরোনাম দিন।',
            'body_bn.required' => 'ঘোষণাপত্রের মূল বক্তব্য (বাংলা) দিন।',
        ]);

        // Determine priority and royal designation
        $priority = 99;
        $designationBn = 'কার্যনির্বাহী সদস্য';
        $designationEn = 'Executive Member';

        if ($userRole === 'superadmin') {
            $priority = 1; // President is highest priority
            $designationBn = 'সভাপতি';
            $designationEn = 'President';
        } elseif ($userRole === 'cashier') {
            $priority = 2; // Cashier is 2nd priority
            $designationBn = 'ক্যাশিয়ার';
            $designationEn = 'Cashier & Treasurer';
        } elseif ($userRole === 'admin') {
            $priority = 3; // Vice President is 3rd priority
            $designationBn = 'সহ-সভাপতি';
            $designationEn = 'Vice President';
        }

        $slug = 'notice-' . Str::slug($validated['title_bn']) . '-' . time();
        $authorName = $currentUser->get('bangla_name') ?: $currentUser->get('name') ?: $currentUser->email();

        $entry = Entry::make()
            ->collection('announcements')
            ->slug($slug)
            ->data([
                'title' => $validated['title_bn'],
                'title_bn' => $validated['title_bn'],
                'title_en' => $validated['title_en'] ?: $validated['title_bn'],
                'body_bn' => $validated['body_bn'],
                'body_en' => $validated['body_en'] ?: $validated['body_bn'],
                'announcement_date' => $validated['announcement_date'] ?: Carbon::now()->format('Y-m-d'),
                'published_by_email' => $currentUser->email(),
                'published_by_name' => $authorName,
                'published_by_designation_bn' => $designationBn,
                'published_by_designation_en' => $designationEn,
                'published_by_role' => $userRole,
                'priority' => $priority,
                'allow_sharing' => true,
                'is_active' => true,
            ]);

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', '📜 রাজকীয় সমবায় ফরমান/ঘোষণাপত্র সফলভাবে জারি ও প্রকাশ করা হয়েছে!');
    }

    /**
     * Announcement Management: Update (Creator or Superadmin)
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'ঘোষণাপত্র পাওয়া যায়নি']);
        }

        $isCreator = $entry->get('published_by_email') === $currentUser->email();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');

        if (! $isCreator && ! $isSuper) {
            return back()->withErrors(['error' => 'শুধুমাত্র ফরমান প্রদানকারী বা সভাপতি এটি সম্পাদনা করতে পারেন।']);
        }

        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'body_bn' => 'required|string',
            'body_en' => 'nullable|string',
            'announcement_date' => 'nullable|date',
        ], [
            'title_bn.required' => 'ঘোষণাপত্রের বাংলা শিরোনাম দিন।',
            'body_bn.required' => 'ঘোষণাপত্রের মূল বক্তব্য (বাংলা) দিন।',
        ]);

        $entry->set('title', $validated['title_bn']);
        $entry->set('title_bn', $validated['title_bn']);
        $entry->set('title_en', $validated['title_en'] ?: $validated['title_bn']);
        $entry->set('body_bn', $validated['body_bn']);
        $entry->set('body_en', $validated['body_en'] ?: $validated['body_bn']);
        if (! empty($validated['announcement_date'])) {
            $entry->set('announcement_date', $validated['announcement_date']);
        }
        if ($request->has('is_active')) {
            $entry->set('is_active', $request->boolean('is_active'));
        }

        $entry->save();
        $this->somitiService->clearCache();

        return back()->with('success', 'ঘোষণাপত্র সফলভাবে হালনাগাদ করা হয়েছে!');
    }

    /**
     * Announcement Management: Delete (Creator or Superadmin)
     */
    public function deleteAnnouncement($id)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $entry = Entry::find($id);
        if (! $entry) {
            return back()->withErrors(['error' => 'ঘোষণাপত্র পাওয়া যায়নি']);
        }

        $isCreator = $entry->get('published_by_email') === $currentUser->email();
        $isSuper = $currentUser->isSuper() || $currentUser->hasRole('superadmin');

        if (! $isCreator && ! $isSuper) {
            return back()->withErrors(['error' => 'অননুমোদিত এক্সেস']);
        }

        $entry->delete();
        $this->somitiService->clearCache();

        return back()->with('info', 'ঘোষণাপত্র প্রত্যাহার ও মুছে ফেলা হয়েছে!');
    }

    /**
     * View / Print / Download Official Money Receipt for Payment
     */
    public function viewReceipt($id)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('home');
        }

        $allPayments = $this->somitiService->getPayments();
        $targetPayment = null;
        foreach ($allPayments as $p) {
            if ($p['id'] == $id || $p['slug'] == $id) {
                $targetPayment = $p;
                break;
            }
        }

        if (! $targetPayment) {
            return redirect()->route('dashboard')->withErrors(['error' => 'মানি রসিদ পাওয়া যায়নি']);
        }

        $userRole = $this->somitiService->getUserRole($currentUser);
        $isSuper = $userRole === 'superadmin';
        $isAdmin = $userRole === 'admin';
        $isCashier = $userRole === 'cashier';
        $isOwner = ($targetPayment['member_email'] === $currentUser->email());

        if (! $isSuper && ! $isAdmin && ! $isCashier && ! $isOwner) {
            return redirect()->route('dashboard')->withErrors(['error' => 'অননুমোদিত এক্সেস']);
        }

        $settings = $this->somitiService->getSettings();

        return view('receipt', compact('targetPayment', 'settings', 'currentUser', 'userRole'));
    }
}

