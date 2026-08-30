@extends('layouts.app')

@section('title', 'সদস্য ড্যাশবোর্ড ও নিয়ন্ত্রণ প্যানেল | Upolobdi Somiti')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Top Welcome Banner & Organization Rotating Circular Emblem -->
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-900 to-brand-navy border border-white/15 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        
        <div class="flex items-center gap-5 relative z-10">
            <!-- 3D / Animated Glowing Rotating Circle with User's Given Logo -->
            <div class="relative w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-brand-gold via-brand-green to-emerald-400 animate-spin-slow opacity-80 blur-xs p-1"></div>
                <div class="relative w-full h-full rounded-full p-1 bg-slate-950 border-2 border-brand-gold/60 overflow-hidden shadow-2xl flex items-center justify-center group">
                    <img src="/assets/images/user-logo.jpg" alt="USS Official Emblem" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500 bg-white">
                </div>
            </div>

            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    @if($userRole === 'superadmin')
                        <span class="px-3 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-extrabold font-bangla">👑 সভাপতি (President)</span>
                    @elseif($userRole === 'admin')
                        <span class="px-3 py-0.5 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/40 text-xs font-extrabold font-bangla">🎖️ সহ-সভাপতি (Vice President)</span>
                    @elseif($userRole === 'cashier')
                        <span class="px-3 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs font-extrabold font-bangla">💼 ক্যাশিয়ার (Cashier)</span>
                    @else
                        <span class="px-3 py-0.5 rounded-full bg-slate-700 text-slate-200 text-xs font-extrabold font-bangla">👤 কার্যনির্বাহী সদস্য</span>
                    @endif
                </div>

                <h2 class="text-2xl sm:text-3xl font-black text-white font-bangla">
                    স্বাগতম, {{ $currentUser->get('bangla_name') ?: $currentUser->get('name') }}!
                </h2>
                <p class="text-xs text-slate-400 font-mono">
                    <i class="fa-solid fa-envelope mr-1 text-slate-500"></i>{{ $currentUser->email() }} &bull; 
                    <i class="fa-solid fa-phone mr-1 text-slate-500"></i>{{ $currentUser->get('phone') ?: 'N/A' }}
                </p>
            </div>
        </div>


        <!-- Action Buttons: Reallocated & Responsive Group -->
        <div class="relative z-10 w-full lg:w-auto flex flex-col gap-2.5">
            <!-- Primary Action (Top) -->
            <button onclick="openPaymentModal()" class="w-full px-5 py-3.5 rounded-2xl bg-gradient-to-r from-brand-gold via-amber-400 to-amber-500 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-amber-950/50 hover:scale-[1.02] transition-all flex items-center justify-center gap-2 cursor-pointer border border-amber-300/40">
                <i class="fa-solid fa-plus-circle text-base"></i>
                <span data-lang-bn>কিস্তি জমা দিন (Submit Payment)</span>
                <span data-lang-en style="display:none;">Submit Payment</span>
            </button>

            <!-- Secondary Actions Grid -->
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2">
                <!-- Download / Print Official Ledger (Cashier, President, Admin) -->
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier')
                    <a href="{{ route('ledger.export') }}" target="_blank" class="px-3 sm:px-4 py-2.5 rounded-xl bg-emerald-600/90 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg flex items-center justify-center gap-1.5 hover:scale-105 transition-all text-center">
                        <i class="fa-solid fa-file-pdf text-sm"></i>
                        <span>লেজার প্রিন্ট</span>
                    </a>
                @endif

                <!-- Direct Cash Entry button for Cashier & President -->
                @if($userRole === 'superadmin' || $userRole === 'cashier')
                    <button onclick="openDirectPaymentModal()" class="px-3 sm:px-4 py-2.5 rounded-xl bg-purple-600/90 hover:bg-purple-500 text-white font-bold text-xs shadow-lg flex items-center justify-center gap-1.5 hover:scale-105 transition-all cursor-pointer text-center">
                        <i class="fa-solid fa-hand-holding-dollar text-sm"></i>
                        <span>নগদ কিস্তি</span>
                    </button>
                @endif

                <!-- Royal Announcements Button for President, Cashier & VP -->
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier')
                    <button onclick="openAddAnnouncementModal()" class="px-3 sm:px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-800 hover:from-amber-600 hover:to-amber-700 border border-amber-400/40 text-amber-100 font-bold text-xs shadow-lg flex items-center justify-center gap-1.5 hover:scale-105 transition-all cursor-pointer text-center">
                        <i class="fa-solid fa-scroll text-sm"></i>
                        <span>নতুন নোটিশ</span>
                    </button>
                @endif

                <!-- Profile Settings -->
                <button onclick="openProfileModal()" class="px-3 sm:px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer text-center">
                    <i class="fa-solid fa-gear"></i>
                    <span data-lang-bn>প্রোফাইল</span>
                    <span data-lang-en style="display:none;">Settings</span>
                </button>
            </div>
        </div>

        <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- 1. Due Alert Banner (If User has Unpaid Months and NOT Exempted) -->
    @if($userDue && $userDue['has_due'] && !$userDue['is_exempted'])
    <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-r from-amber-950/90 via-slate-900 to-rose-950/80 border-2 border-amber-500/60 shadow-2xl flex flex-col items-center text-center gap-5 animate-pulse" style="animation-duration: 4s;">
        <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left w-full justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/40 flex items-center justify-center text-2xl flex-shrink-0 mx-auto sm:mx-0 shadow-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="space-y-1">
                    <h4 class="text-lg sm:text-xl font-black text-white font-bangla flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                        <span>⚠️ আপনার মাসিক কিস্তি বকেয়া রয়েছে</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 text-xs font-mono font-bold">{{ $userDue['net_due_months'] }} মাস বকেয়া</span>
                    </h4>
                    <p class="text-xs sm:text-sm text-amber-200/90 font-bangla">
                        <strong>বকেয়া কিস্তির সময়সীমা:</strong> {{ $userDue['range_bn'] }} &bull; 
                        <strong>মোট প্রদেয় পরিমাণ:</strong> <span class="font-mono font-black text-white bg-rose-500/40 px-2.5 py-0.5 rounded-md text-sm sm:text-base">{{ number_format($userDue['total_due_amount']) }} BDT</span>
                    </p>
                    <p class="text-[11px] text-slate-400 font-bangla">
                        সমিতির নিয়ম অনুযায়ী মাসিক কিস্তি পরিশোধ করে ট্রানজেকশন স্লিপ জমা দিন (সিস্টেম স্বয়ংক্রিয়ভাবে পুরাতন বকেয়া থেকে সমন্বয় করবে)।
                    </p>
                </div>
            </div>
        </div>

        <!-- Centered Action Button in Second Card -->
        <div class="w-full flex justify-center pt-1">
            <button onclick="openPaymentModalWithDue({{ $userDue['total_due_amount'] }}, {{ $userDue['net_due_months'] }})" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-amber-400 via-brand-gold to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-2xl shadow-amber-950/60 hover:scale-105 transition-all flex items-center justify-center gap-2 cursor-pointer border border-amber-300/50">
                <i class="fa-solid fa-wallet text-base sm:text-lg"></i>
                <span>এখনই বকেয়া কিস্তি পরিশোধ করুন</span>
            </button>
        </div>
    </div>
    @elseif($userDue && $userDue['is_exempted'])
    <div class="p-4 rounded-2xl bg-emerald-950/40 border border-emerald-500/40 flex items-center justify-between gap-4 text-xs font-bangla">
        <div class="flex items-center gap-3 text-emerald-300">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span>
                <strong>বকেয়া অবকাশ মঞ্জুরকৃত:</strong> আপনার জন্য {{ $userDue['exemption_months'] }} মাসের কিস্তি অবকাশ অনুমোদন করা হয়েছে (অনুমোদনকারী: {{ $userDue['exemption_approved_by'] }})। কারণ: {{ $userDue['exemption_reason'] }}
            </span>
        </div>
    </div>
    @endif

    <!-- 2. Payment Rejection Alerts -->
    @if(count($rejectedPayments) > 0)
    <div class="space-y-3">
        @foreach($rejectedPayments as $rej)
        <div class="p-5 rounded-2xl bg-rose-950/50 border border-rose-500/50 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3 text-rose-300">
                <i class="fa-solid fa-circle-xmark text-2xl mt-0.5 text-rose-400"></i>
                <div class="space-y-1">
                    <h5 class="text-sm font-bold text-white font-bangla">
                        পেমেন্ট আবেদন বাতিল করা হয়েছে ({{ $rej['title'] }})
                    </h5>
                    <p class="text-xs text-rose-200 font-bangla">
                        <strong>বাতিলের কারণ:</strong> {{ $rej['rejection_reason'] }} &bull; (যাচাইকারী: {{ $rej['reviewed_by'] ?? 'ক্যাশিয়ার' }})
                    </p>
                </div>
            </div>
            <button onclick="openPaymentModal()" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition-colors flex-shrink-0">
                <i class="fa-solid fa-redo mr-1"></i> পুনরায় জমা দিন
            </button>
        </div>
        @endforeach
    </div>
    @endif

    <!-- 3. Financial Stats Summary Cards (Visible in Full to Logged-in Members) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="p-5 rounded-3xl bg-slate-900 border border-white/10 space-y-2">
            <span class="text-xs text-slate-400 font-bangla block">আমার পরিশোধিত মোট কিস্তি</span>
            <h4 class="text-2xl font-black text-emerald-400 font-mono">{{ number_format($userDue['total_paid_amount'] ?? 0) }} BDT</h4>
            <span class="text-[11px] text-slate-500 font-bangla">মোট {{ $userDue['total_paid_months'] ?? 0 }} মাস সম্পন্ন</span>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900 border border-white/10 space-y-2">
            <span class="text-xs text-slate-400 font-bangla block">আমার বর্তমান বকেয়া</span>
            <h4 class="text-2xl font-black {{ ($userDue['total_due_amount'] ?? 0) > 0 ? 'text-rose-400' : 'text-slate-400' }} font-mono">
                {{ number_format($userDue['total_due_amount'] ?? 0) }} BDT
            </h4>
            <span class="text-[11px] text-slate-500 font-bangla">{{ ($userDue['net_due_months'] ?? 0) }} মাস প্রদেয়</span>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900 border border-white/10 space-y-2">
            <span class="text-xs text-slate-400 font-bangla block">সমিতির সর্বমোট সংরক্ষিত ফান্ড</span>
            <h4 class="text-2xl font-black text-amber-400 font-mono">{{ number_format($overview['total_approved_fund']) }} BDT</h4>
            <span class="text-[11px] text-slate-500 font-bangla">{{ $overview['total_approved_transactions'] }} টি সফল ট্রানজেকশন</span>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900 border border-white/10 space-y-2">
            <span class="text-xs text-slate-400 font-bangla block">নির্ধারিত মাসিক কিস্তি হার</span>
            <h4 class="text-2xl font-black text-white font-mono">{{ number_format($settings['monthly_installment'] ?? 1000) }} BDT</h4>
            <span class="text-[11px] text-slate-500 font-bangla">সদস্য সংখ্যা: {{ $overview['total_members'] }} জন</span>
        </div>
    </div>

    <!-- 4. Role Specific Management Panels -->
    
    <!-- A. Cashier & Super Admin: Pending Payment Queue & Approvals -->
    @if($userRole === 'superadmin' || $userRole === 'cashier')
    <div class="p-6 rounded-3xl bg-slate-900 border border-amber-500/30 shadow-2xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-xs font-bold font-mono">
                        {{ count($pendingPayments) }} টি অপেক্ষমাণ
                    </span>
                    <h3 class="text-lg font-black text-white font-bangla">
                        পেমেন্ট যাচাই, সমন্বয় ও অনুমোদন কিউ (Payment Review & Adjustment)
                    </h3>
                </div>
                <p class="text-xs text-slate-400 font-bangla mt-1">সদস্যদের প্রেরিত স্লিপ দেখে প্রয়োজন অনুযায়ী মাস/টাকার পরিমাণ সমন্বয় করে অনুমোদন করুন</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Payment Accounts Settings Button for Cashier & Superadmin -->
                <button type="button" onclick="openModal('editPaymentAccountsModal')" class="px-3.5 py-2 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/40 text-xs font-bold font-bangla flex items-center gap-1.5 transition-colors cursor-pointer shadow-md">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>পেমেন্ট অ্যাকাউন্ট নম্বর সেটিং</span>
                </button>

                <!-- Master Toggle for Public Due Warning Modal -->
                <form action="{{ route('settings.update') }}" method="POST" class="flex items-center gap-3 bg-slate-800/80 p-2 rounded-2xl border border-white/10">
                    @csrf
                    <span class="text-xs font-bold text-slate-300 font-bangla">ওয়েবসাইট বকেয়া বোর্ড:</span>
                    <input type="hidden" name="due_warning_modal_enabled" value="0">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="due_warning_modal_enabled" value="1" {{ !empty($settings['due_warning_modal_enabled']) ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </form>
            </div>
        </div>


        @if(count($pendingPayments) === 0)
            <div class="py-8 text-center text-slate-500 text-xs font-bangla">
                <i class="fa-solid fa-clipboard-check text-3xl mb-2 text-slate-600"></i>
                <p>বর্তমানে কোনো অনুমোদনের অপেক্ষমাণ পেমেন্ট স্লিপ নেই।</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-400 font-bangla">
                            <th class="pb-3">সদস্য</th>
                            <th class="pb-3">দাবিকৃত পরিমাণ ও মাস</th>
                            <th class="pb-3">পেমেন্ট মাধ্যম</th>
                            <th class="pb-3">ট্রানজেকশন আইডি</th>
                            <th class="pb-3">তারিখ</th>
                            <th class="pb-3">রসিদ / প্রমাণ</th>
                            <th class="pb-3 text-right">যাচাই ও অনুমোদন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($pendingPayments as $p)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3.5">
                                <span class="font-bold text-white font-bangla">{{ $p['member_bangla_name'] }}</span>
                                <span class="block text-[11px] text-slate-400 font-mono">{{ $p['member_email'] }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="font-bold text-amber-300 font-mono text-sm">{{ number_format($p['amount']) }} BDT</span>
                                <span class="block text-[11px] text-slate-400 font-bangla">{{ $p['payment_months'] ?: ($p['months_count'].' মাস') }}</span>
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-md bg-white/10 font-bold uppercase text-[10px]">{{ $p['payment_method'] }}</span>
                            </td>
                            <td class="py-3.5 font-mono text-slate-300 font-bold">
                                {{ $p['reference_number'] }}
                            </td>
                            <td class="py-3.5 text-slate-400 font-mono">
                                {{ $p['payment_date'] }}
                            </td>
                            <td class="py-3.5">
                                @if($p['proof_image'])
                                    <button type="button" onclick="viewProofModal('{{ $p['proof_image'] }}', '{{ $p['member_name'] }}', '{{ $p['reference_number'] }}')" class="px-2.5 py-1 rounded-lg bg-sky-500/20 text-sky-300 hover:bg-sky-500/30 text-xs font-bold transition-colors">
                                        <i class="fa-solid fa-image mr-1"></i> রসিদ দেখুন
                                    </button>
                                @else
                                    <span class="text-slate-500 text-[11px]">সংযুক্ত নেই</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right space-x-1.5">
                                <button type="button" onclick="openApproveModal('{{ $p['id'] }}', '{{ $p['member_name'] }}', {{ $p['amount'] }}, {{ $p['months_count'] }}, '{{ $p['reference_number'] }}', '{{ $p['proof_image'] ?? '' }}')" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-colors shadow-lg">
                                    <i class="fa-solid fa-check mr-1"></i> অনুমোদন / সমন্বয়
                                </button>

                                <button type="button" onclick="openRejectModal('{{ $p['id'] }}', '{{ $p['member_name'] }}', '{{ $p['amount'] }}')" class="px-3 py-1.5 rounded-xl bg-rose-600/30 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/40 font-bold text-xs transition-colors">
                                    <i class="fa-solid fa-xmark mr-1"></i> বাতিল
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endif

    <!-- B. Investment Projects Management Panel (President & Vice President) -->
    @if($userRole === 'superadmin' || $userRole === 'admin')
    <div class="p-6 rounded-3xl bg-slate-900 border border-brand-green/40 shadow-2xl space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <h3 class="text-lg font-black text-white font-bangla flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-emerald-400"></i>
                    <span>বিনিয়োগ প্রকল্প ব্যবস্থাপনা (Investment Projects Panel)</span>
                </h3>
                <p class="text-xs text-slate-400 font-bangla mt-0.5">সমিতির চলমান ও ভবিষ্যৎ বিনিয়োগ উদ্যোগসমূহ যোগ ও সম্পাদনা করুন</p>
            </div>
            <button onclick="openAddProjectModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg">
                <i class="fa-solid fa-plus"></i>
                <span>নতুন প্রকল্প যোগ করুন</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($projects as $pr)
            <div class="p-4 rounded-2xl bg-slate-800/80 border border-white/10 space-y-3 flex flex-col justify-between">
                <div>
                    <img src="{{ $pr['image'] }}" alt="{{ $pr['title'] }}" class="w-full h-36 rounded-xl object-cover mb-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold font-mono">
                            {{ $pr['status'] }}
                        </span>
                        <span class="text-[11px] text-amber-300 font-mono font-bold">{{ $pr['target_amount'] }}</span>
                    </div>
                    <h5 class="text-sm font-bold text-white font-bangla mt-1">{{ $pr['title_bn'] }}</h5>
                    <p class="text-xs text-slate-400 line-clamp-2 font-bangla mt-1">{{ $pr['description_bn'] }}</p>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-white/10">
                    <button type="button" onclick="handleEditProject(this)" data-project="{{ json_encode($pr) }}" class="text-amber-300 hover:text-amber-200 text-xs font-bold flex items-center gap-1 cursor-pointer">
                        <i class="fa-solid fa-pen mr-1"></i> এডিট
                    </button>

                    <form action="{{ route('project.delete', $pr['id']) }}" method="POST" onsubmit="return confirm('এই প্রকল্প মুছে ফেলতে চান?')">
                        @csrf
                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs cursor-pointer">
                            <i class="fa-solid fa-trash mr-1"></i> ডিলিট
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-6 text-slate-500 text-xs">কোনো প্রকল্প পাওয়া যায়নি</div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- C. Super Admin & Admin: Somiti Bylaws & Rules Management Panel -->
    @if($userRole === 'superadmin' || $userRole === 'admin')
    <div class="p-6 rounded-3xl bg-slate-900 border border-brand-gold/30 shadow-2xl space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <h3 class="text-lg font-black text-white font-bangla">
                    📜 সমিতির বর্তমান নীতিমালা ও ধারা ব্যবস্থাপনা
                </h3>
                <p class="text-xs text-slate-400 font-bangla">ওয়েবসাইটে প্রদর্শিত সকল ধারা ও নিয়মাবলী যোগ, পরিবর্তন বা ডিলিট করুন</p>
            </div>
            <button onclick="openAddRuleModal()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-slate-950 font-black text-xs flex items-center gap-1.5 shadow-lg cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>নতুন ধারা যোগ করুন</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($rules as $r)
            <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-800/90 to-slate-900/90 border border-white/10 hover:border-brand-gold/40 space-y-3 font-bangla flex flex-col justify-between shadow-lg">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-brand-gold/20 text-brand-gold font-mono font-bold text-xs">
                            {{ $r['rule_number'] }}
                        </span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="handleEditRule(this)" data-rule="{{ json_encode($r) }}" class="px-2.5 py-1 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-pen"></i> <span>এডিট</span>
                            </button>
                            <form action="{{ route('rule.delete', $r['id']) }}" method="POST" class="inline" onsubmit="return confirm('এই ধারাটি মুছে ফেলতে চান?')">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white text-xs cursor-pointer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <h5 class="text-sm font-bold text-white">{{ $r['title_bn'] }}</h5>
                    <p class="text-xs text-slate-300 leading-relaxed">{{ $r['description_bn'] }}</p>
                </div>

            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- D. Super Admin Exclusive: Members & Bilingual Content Settings -->
    @if($userRole === 'superadmin')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Members Management -->
        <div class="lg:col-span-7 p-6 rounded-3xl bg-slate-900 border border-white/10 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h3 class="text-lg font-black text-white font-bangla">সদস্য ব্যবস্থাপনা ও বকেয়া অবকাশ</h3>
                    <p class="text-xs text-slate-400 font-bangla">সদস্যদের হিসাব পর্যবেক্ষণ ও সর্বোচ্চ ১২ মাস অবকাশ মঞ্জুর</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-400 font-bangla">
                            <th class="pb-3">সদস্য ও ছবি</th>
                            <th class="pb-3">পদবী ও রোল</th>
                            <th class="pb-3">বকেয়া অবস্থা</th>
                            <th class="pb-3">মওকুফ/অবকাশ</th>
                            <th class="pb-3 text-right">কার্যক্রম</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 font-bangla">
                        @foreach($allMembersDue as $md)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-white/20 flex-shrink-0">
                                    <img src="{{ $md['avatar'] ?? '/assets/images/avatar-default.svg' }}" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="font-bold text-white block">{{ $md['bangla_name'] }}</span>
                                    <span class="text-[11px] text-slate-500 font-mono">{{ $md['email'] }}</span>
                                </div>

                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/10 text-slate-200">
                                    {{ $md['role'] }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($md['is_exempted'])
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">অবকাশ মঞ্জুরকৃত</span>
                                @elseif($md['has_due'])
                                    <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-300 text-[10px] font-bold font-mono">{{ number_format($md['total_due_amount']) }} BDT ({{ $md['net_due_months'] }} মাস)</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">পরিশোধিত</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <button type="button" onclick="openExemptionModal('{{ $md['email'] }}', '{{ $md['name'] }}', {{ $md['exemption_months'] ?? 0 }}, 12)" class="px-2.5 py-1 rounded-lg bg-purple-500/20 text-purple-300 hover:bg-purple-500/30 text-[11px] font-bold">
                                    <i class="fa-solid fa-gift mr-1"></i> {{ $md['exemption_months'] > 0 ? $md['exemption_months'].' মাস অবকাশ' : 'অবকাশ দিন' }}
                                </button>
                            </td>
                            <td class="py-3 text-right">
                                <button type="button" onclick="openEditMemberModal('{{ $md['email'] }}', '{{ $md['name'] }}', '{{ $md['bangla_name'] }}', '{{ $md['role'] }}')" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bilingual Content & Footer Customization (President Only) -->
        <div class="lg:col-span-5 p-6 rounded-3xl bg-slate-900 border border-white/10 shadow-2xl space-y-5">
            <div class="border-b border-white/10 pb-3">
                <h3 class="text-lg font-black text-white font-bangla">ওয়েবসাইট কনটেন্ট ও সেটিংস এডিটর</h3>
                <p class="text-xs text-slate-400 font-bangla">ওয়েবসাইটের মূল স্লোগান, ফুটার, ফোন/ইমেইল ও বকেয়া তারিখ পরিবর্তন</p>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" class="space-y-3.5 text-xs font-bangla">
                @csrf
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">মাসিক কিস্তি (BDT)</label>
                        <input type="number" name="monthly_installment" value="{{ $settings['monthly_installment'] ?? 1000 }}" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">বকেয়া দেখানোর তারিখ (মাসের কততম দিন)</label>
                        <input type="number" name="due_day_of_month" min="1" max="28" value="{{ $settings['due_day_of_month'] ?? 15 }}" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold" placeholder="e.g. 15">
                        <span class="text-[10px] text-slate-500 mt-0.5 block">এই তারিখের পরে চলতি মাসের বকেয়া দেখাবে</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">যোগাযোগ মোবাইল</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+880 1712-345678' }}" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">যোগাযোগ ইমেইল</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'info@upolobdi-somiti.org' }}" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">মূল স্লোগান / Motto (বাংলা)</label>
                    <input type="text" name="motto_bn" value="{{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }}" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Motto (English)</label>
                    <input type="text" name="motto_en" value="{{ $settings['motto_en'] ?? 'Journey of Dreams on the Path of Truth' }}" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">ফুটার বিবরণ (বাংলা)</label>
                    <textarea name="footer_desc_bn" rows="5" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed">{{ $settings['footer_desc_bn'] ?? '৬ বন্ধুর আন্তরিকতা ও পারস্পরিক আর্থিক সহযোগিতায় ভবিষ্যতের বড় কোনো স্বপ্ন বাস্তবায়নে আমাদের এই সমবায় পদযাত্রা।' }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Footer Description (English)</label>
                    <textarea name="footer_desc_en" rows="5" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed">{{ $settings['footer_desc_en'] ?? 'A cooperative initiative of 6 lifelong friends pooling monthly installments towards ambitious future investments.' }}</textarea>
                </div>


                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-gold hover:bg-amber-400 text-slate-950 font-black text-xs transition-colors shadow-lg">
                    কনটেন্ট সংরক্ষণ করুন
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- E. Events Publisher -->
    @if($userRole === 'superadmin' || $userRole === 'admin')
    <div class="p-6 rounded-3xl bg-slate-900 border border-white/10 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <div>
                <h3 class="text-lg font-black text-white font-bangla">সমিতি ইভেন্ট ও ফটো গল্প প্রকাশনা</h3>
                <p class="text-xs text-slate-400 font-bangla">সমিতির ভ্রমণ, সভা ও কার্যক্রমের ছবি আপলোড ও হাইলাইট করুন</p>
            </div>
            <button onclick="openAddEventModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-lg">
                <i class="fa-solid fa-camera"></i>
                <span>নতুন ইভেন্ট যোগ</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($events as $ev)
            <div class="p-4 rounded-2xl bg-slate-800/80 border {{ !empty($ev['is_masonry_selected']) ? 'border-emerald-500/50' : 'border-white/10' }} space-y-3 flex flex-col justify-between">
                <div>
                    <img src="{{ $ev['image'] }}" alt="{{ $ev['title'] }}" class="w-full h-36 rounded-xl object-cover mb-3">
                    <div class="flex items-center gap-1.5 flex-wrap mb-1">
                        <span class="text-[11px] text-brand-gold font-mono"><i class="fa-regular fa-calendar mr-1"></i>{{ $ev['event_date'] }}</span>
                        @if(!empty($ev['is_highlighted']))
                            <span class="px-2 py-0.5 rounded bg-brand-gold/20 text-brand-gold text-[10px] font-bold">🌟 Highlighted</span>
                        @endif
                        @if(!empty($ev['is_masonry_selected']))
                            <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">🖼 Masonry</span>
                        @endif
                    </div>
                    <h5 class="text-sm font-bold text-white font-bangla mt-1">{{ $ev['title_bn'] }}</h5>
                    <p class="text-xs text-slate-400 line-clamp-2 font-bangla mt-1">{{ $ev['description_bn'] }}</p>
                </div>

                <div class="space-y-2 pt-2 border-t border-white/10">
                    <!-- Toggle Masonry Selection -->
                    <form action="{{ route('event.update', $ev['id']) }}" method="POST" class="flex items-center justify-between">
                        @csrf
                        <span class="text-[11px] text-slate-400 font-bangla">মেসনারি গ্যালারি</span>
                        <input type="hidden" name="is_masonry_selected" value="{{ !empty($ev['is_masonry_selected']) ? '0' : '1' }}">
                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ !empty($ev['is_masonry_selected']) ? 'bg-emerald-500/30 text-emerald-300 hover:bg-rose-500/30 hover:text-rose-300' : 'bg-slate-700 text-slate-400 hover:bg-emerald-500/30 hover:text-emerald-300' }}">
                            <i class="fa-solid {{ !empty($ev['is_masonry_selected']) ? 'fa-check-square' : 'fa-square' }} mr-1"></i>
                            {{ !empty($ev['is_masonry_selected']) ? 'নির্বাচিত (সক্রিয়)' : 'যোগ করুন' }}
                        </button>
                    </form>
                    <!-- Toggle Highlighted -->
                    <form action="{{ route('event.update', $ev['id']) }}" method="POST" class="flex items-center justify-between">
                        @csrf
                        <span class="text-[11px] text-slate-400 font-bangla">হাইলাইটস সেকশন</span>
                        <input type="hidden" name="is_highlighted" value="{{ !empty($ev['is_highlighted']) ? '0' : '1' }}">
                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ !empty($ev['is_highlighted']) ? 'bg-amber-500/30 text-amber-300 hover:bg-slate-700 hover:text-slate-400' : 'bg-slate-700 text-slate-400 hover:bg-amber-500/30 hover:text-amber-300' }}">
                            <i class="fa-solid {{ !empty($ev['is_highlighted']) ? 'fa-star' : 'fa-star' }} mr-1"></i>
                            {{ !empty($ev['is_highlighted']) ? '🌟 হাইলাইটেড' : 'হাইলাইট করুন' }}
                        </button>
                    </form>

                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-slate-500 font-mono">{{ count($ev['gallery_images']) }} Photos</span>
                        <form action="{{ route('event.delete', $ev['id']) }}" method="POST" onsubmit="return confirm('এই ইভেন্টটি মুছে ফেলতে চান?')">
                            @csrf
                            <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs">
                                <i class="fa-solid fa-trash mr-1"></i> ডিলিট
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Royal Announcements & Proclamations Management Section (President, Cashier, VP) -->
    @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier')

    <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 border border-amber-500/30 shadow-2xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-md bg-amber-500/20 text-amber-300 text-xs font-bold font-mono">
                        {{ count($announcements) }} টি প্রকাশিত
                    </span>
                    <h3 class="text-lg font-black text-white font-bangla flex items-center gap-2">
                        <i class="fa-solid fa-scroll text-amber-400"></i>
                        <span>রাজকীয় সমবায় ফরমান ও নোটিশবোর্ড (Announcements & Timeline)</span>
                    </h3>
                </div>
                <p class="text-xs text-slate-400 font-bangla mt-1">সভাপতি, ক্যাশিয়ার ও সহ-সভাপতি কর্তৃক জারিকৃত নোটিশ ও ফরমান পরিচালনা করুন। ক্রমানুসারে ওয়েবসাইটে প্রদর্শিত হবে।</p>
            </div>
            <button onclick="openAddAnnouncementModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 text-white font-bold text-xs flex items-center gap-2 shadow-lg hover:scale-105 transition-all cursor-pointer">
                <i class="fa-solid fa-feather-pointed"></i>
                <span>নতুন ফরমান জারি করুন</span>
            </button>
        </div>

        @if(count($announcements) === 0)
            <div class="py-12 text-center text-slate-500 text-xs font-bangla">
                <i class="fa-solid fa-scroll text-3xl mb-2 text-slate-600"></i>
                <p>বর্তমানে কোনো প্রকাশিত ফরমান নেই।</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($announcements as $ann)
                @php
                    $isCreator = ($currentUser->email() === $ann['published_by_email']);
                    $isSuper = ($userRole === 'superadmin');
                    $shareUrl = url('/');
                    $shareText = urlencode("📜 " . $ann['title_bn'] . " — " . $ann['body_bn'] . "\n\nউপলব্ধি সমবায় সমিতি (USS)");
                @endphp
                <div class="p-5 rounded-2xl bg-slate-800/80 border {{ $ann['published_by_role'] === 'superadmin' ? 'border-rose-500/40' : ($ann['published_by_role'] === 'cashier' ? 'border-emerald-500/40' : 'border-blue-500/40') }} flex flex-col justify-between space-y-4 shadow-xl">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ann['published_by_role'] === 'superadmin' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : ($ann['published_by_role'] === 'cashier' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30') }}">
                                {{ $ann['published_by_designation_bn'] }} ({{ $ann['published_by_name'] }})
                            </span>
                            <span class="text-[11px] text-amber-300 font-mono"><i class="fa-regular fa-calendar mr-1"></i>{{ $ann['announcement_date'] }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-white font-bangla">{{ $ann['title_bn'] }}</h4>
                        <p class="text-xs text-slate-300 line-clamp-3 font-bangla leading-relaxed">{{ $ann['body_bn'] }}</p>
                    </div>

                    <div class="pt-3 border-t border-white/10 space-y-3">
                        <!-- Creator-Only Social Sharing Buttons -->
                        @if($isCreator)
                        <div>
                            <span class="text-[10px] text-amber-300 font-bold block mb-1.5 font-bangla"><i class="fa-solid fa-share-nodes mr-1"></i>প্রচার করুন (শুধুমাত্র প্রদানকারী):</span>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}&quote={{ $shareText }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-bold flex items-center gap-1 shadow transition-transform hover:scale-105">
                                    <i class="fa-brands fa-facebook"></i> <span>FB</span>
                                </a>
                                <a href="fb-messenger://share/?link={{ urlencode($shareUrl) }}&app_id=291494419142" target="_blank" class="px-2.5 py-1 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-400 hover:to-purple-500 text-white text-[11px] font-bold flex items-center gap-1 shadow transition-transform hover:scale-105">
                                    <i class="fa-brands fa-facebook-messenger"></i> <span>Messenger</span>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ urlencode($shareUrl) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold flex items-center gap-1 shadow transition-transform hover:scale-105">
                                    <i class="fa-brands fa-whatsapp"></i> <span>WhatsApp</span>
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[10px] text-slate-500 font-mono">অগ্রাধিকার: {{ $ann['priority'] === 1 ? '১ম (সভাপতি)' : ($ann['priority'] === 2 ? '২য় (ক্যাশিয়ার)' : '৩য় (সহ-সভাপতি)') }}</span>
                            
                            @if($isCreator || $isSuper)
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="handleEditAnnouncement(this)" data-announcement="{{ json_encode($ann) }}" class="text-amber-400 hover:text-amber-300 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square"></i> <span>এডিট</span>
                                </button>
                                <form action="{{ route('announcement.delete', $ann['id']) }}" method="POST" onsubmit="return confirm('এই ফরমানটি প্রত্যাহার/মুছে ফেলতে চান?')">
                                    @csrf
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs cursor-pointer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    <!-- 5. Payment Transaction Ledger Table (Personal or Global) with Entry Source -->
    <div class="p-6 rounded-3xl bg-slate-900 border border-white/10 shadow-2xl space-y-5">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <h3 class="text-lg font-black text-white font-bangla">
                    @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier')
                        সমিতির সর্বমোট পেমেন্ট অডিট খতিয়ান (Global Ledger)
                    @else
                        আমার ব্যক্তিগত কিস্তি জমার খতিয়ান (Personal Ledger)
                    @endif
                </h3>
                <p class="text-xs text-slate-400 font-bangla">সকল অনুমোদিত, অপেক্ষমাণ ও বাতিলকৃত কিস্তির বিস্তারিত তালিকা</p>
            </div>

            <div class="flex items-center gap-2">
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier')
                    <a href="{{ route('ledger.export') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-emerald-500/30 text-xs font-bold flex items-center gap-1.5 shadow">
                        <i class="fa-solid fa-print"></i>
                        <span>রিপোর্ট প্রিন্ট</span>
                    </a>
                @endif

                <button onclick="openPaymentModal()" class="px-4 py-2 rounded-xl bg-brand-gold hover:bg-amber-400 text-slate-950 font-black text-xs flex items-center gap-1.5 shadow-lg">
                    <i class="fa-solid fa-plus"></i>
                    <span>নতুন কিস্তি জমা</span>
                </button>
            </div>
        </div>

        @php
            $displayPayments = ($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'cashier') ? $allPayments : $userPayments;
        @endphp

        @if(count($displayPayments) === 0)
            <div class="py-12 text-center text-slate-500 text-xs font-bangla">
                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-600"></i>
                <p>কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি।</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-400 font-bangla">
                            <th class="pb-3">সদস্য</th>
                            <th class="pb-3">কিস্তির মাস</th>
                            <th class="pb-3">পরিমাণ</th>
                            <th class="pb-3">মাধ্যম ও TrxID</th>
                            <th class="pb-3">তারিখ</th>
                            <th class="pb-3">এন্ট্রি উৎস</th>
                            <th class="pb-3">অবস্থা (Status)</th>
                            <th class="pb-3">যাচাইকারী (Verifier)</th>
                            <th class="pb-3 text-right">রসিদ ও ভাউচার</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($displayPayments as $pay)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3.5">
                                <span class="font-bold text-white font-bangla">{{ $pay['member_bangla_name'] }}</span>
                                <span class="block text-[10px] text-slate-500 font-mono">{{ $pay['member_email'] }}</span>
                            </td>

                            <td class="py-3.5 text-slate-300 font-bangla">
                                {{ $pay['payment_months'] }}
                            </td>
                            <td class="py-3.5 font-bold text-white font-mono text-sm">
                                {{ number_format($pay['amount']) }} BDT
                            </td>
                            <td class="py-3.5">
                                <span class="px-1.5 py-0.5 rounded bg-white/10 text-[10px] font-bold uppercase">{{ $pay['payment_method'] }}</span>
                                <span class="block text-[11px] text-slate-400 font-mono mt-0.5">{{ $pay['reference_number'] }}</span>
                            </td>
                            <td class="py-3.5 text-slate-400 font-mono">
                                {{ $pay['payment_date'] }}
                            </td>
                            <td class="py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pay['entry_type'] === 'cashier' ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : ($pay['entry_type'] === 'superadmin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-slate-700 text-slate-300') }}">
                                    {{ $pay['entry_created_by'] }}
                                </span>
                            </td>
                            <td class="py-3.5">
                                @if($pay['status'] === 'approved')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold font-bangla inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> অনুমোদিত
                                    </span>
                                @elseif($pay['status'] === 'pending')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold font-bangla inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> পর্যালোচনায়
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold font-bangla inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-xmark"></i> বাতিলকৃত
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 text-xs font-bangla">
                                @if($pay['status'] === 'approved')
                                    <div class="flex items-center gap-1 text-emerald-300 font-bold text-[11px]">
                                        <i class="fa-solid fa-user-check text-emerald-400 text-xs"></i>
                                        <span>{{ $pay['reviewed_by'] ?: 'সাইফুল ইসলাম (ক্যাশিয়ার)' }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono block mt-0.5">{{ $pay['reviewed_at'] ?: $pay['payment_date'] }}</span>
                                @elseif($pay['status'] === 'rejected')
                                    <div class="text-rose-300 font-bold text-[11px]">
                                        <i class="fa-solid fa-ban text-rose-400 mr-1"></i>{{ $pay['reviewed_by'] ?: 'ক্যাশিয়ার' }}
                                    </div>
                                    <span class="text-[10px] text-rose-400/80 block mt-0.5">কারণ: {{ $pay['rejection_reason'] }}</span>
                                @else
                                    <span class="text-amber-400/90 font-mono text-[11px] flex items-center gap-1">
                                        <i class="fa-solid fa-hourglass-half text-[10px]"></i> অপেক্ষমাণ
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    @if($pay['status'] === 'approved')
                                    <button type="button" onclick="handleOpenReceipt(this)" data-receipt="{{ base64_encode(json_encode($pay)) }}" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/40 text-emerald-300 hover:text-emerald-200 text-[11px] font-bold inline-flex items-center gap-1 shadow transition-all hover:scale-105 cursor-pointer" title="অফিসিয়াল মানি রসিদ দেখুন">
                                        <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                                        <span>মানি রসিদ</span>
                                    </button>
                                    <a href="{{ route('payment.receipt', $pay['id']) }}" target="_blank" class="p-1 sm:px-2 sm:py-1 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/40 text-amber-300 hover:text-amber-200 text-[11px] font-bold inline-flex items-center gap-1 shadow transition-all hover:scale-105 cursor-pointer" title="রসিদ ডাউনলোড / প্রিন্ট">
                                        <i class="fa-solid fa-download text-xs"></i>
                                        <span class="hidden sm:inline">ডাউনলোড</span>
                                    </a>
                                    @endif

                                    @if($pay['proof_image'])
                                        <button type="button" onclick="viewProofModal('{{ $pay['proof_image'] }}', '{{ $pay['member_name'] }}', '{{ $pay['reference_number'] }}')" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 text-xs shadow transition-colors cursor-pointer" title="আসল ভাউচার প্রমাণ">
                                            <i class="fa-solid fa-receipt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<!-- MODALS -->

<!-- 1. Submit Payment Slip Modal (Member Self Submission) -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-white/15 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative animate-float max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('paymentModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-money-bill-wave text-brand-gold"></i>
                <span>কিস্তি পরিশোধের ভাউচার সাবমিট</span>
            </h3>
            <p class="text-xs text-slate-400 font-bangla mt-1">
                ম্যানুয়ালি টাকা পাঠিয়ে ট্রানজেকশন আইডি ও স্লিপ আপলোড করুন। পুরাতন বকেয়া থেকে সমন্বয় হবে।
            </p>
        </div>

        <!-- Top Button to View Deposit Accounts -->
        <div class="mb-5">
            <button type="button" onclick="togglePaymentAccountsGuide()" class="w-full py-2.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500/20 via-brand-gold/20 to-amber-500/20 border border-brand-gold/50 hover:bg-brand-gold/30 text-amber-300 font-bold text-xs flex items-center justify-between transition-all cursor-pointer shadow-md">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-brand-gold"></i>
                    <span>💳 টাকা পাঠানোর অ্যাকাউন্ট ও নম্বর দেখুন (View Accounts)</span>
                </span>
                <i id="pay_guide_icon" class="fa-solid fa-chevron-down text-xs transition-transform"></i>
            </button>

            <!-- Collapsible Interactive Deposit Accounts Card -->
            <div id="paymentAccountsGuideCard" class="hidden mt-3 p-4 rounded-2xl bg-slate-950/95 border border-amber-500/40 space-y-3 text-xs">
                <div class="space-y-2">
                    <!-- bKash -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-pink-950/40 border border-pink-500/30">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-pink-600/30 text-pink-400 font-black text-xs flex items-center justify-center">bK</span>
                            <div>
                                <span class="text-[10px] text-pink-300 font-bold block font-bangla">বিকাশ পার্সোনাল (Send Money)</span>
                                <span class="font-mono font-black text-white text-xs">{{ $settings['bkash_number'] ?? '01712-345678' }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('{{ $settings['bkash_number'] ?? '01712-345678' }}', this)" class="px-2.5 py-1 rounded-lg bg-pink-600/30 hover:bg-pink-600/50 text-pink-200 text-[10px] font-bold transition-colors">
                            <i class="fa-solid fa-copy mr-1"></i> কপি
                        </button>
                    </div>

                    <!-- Nagad -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-orange-950/40 border border-orange-500/30">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-orange-600/30 text-orange-400 font-black text-xs flex items-center justify-center font-bangla">নগদ</span>
                            <div>
                                <span class="text-[10px] text-orange-300 font-bold block font-bangla">নগদ পার্সোনাল (Send Money)</span>
                                <span class="font-mono font-black text-white text-xs">{{ $settings['nagad_number'] ?? '01812-345678' }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('{{ $settings['nagad_number'] ?? '01812-345678' }}', this)" class="px-2.5 py-1 rounded-lg bg-orange-600/30 hover:bg-orange-600/50 text-orange-300 text-[10px] font-bold transition-colors">
                            <i class="fa-solid fa-copy mr-1"></i> কপি
                        </button>
                    </div>

                    <!-- Rocket -->
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-purple-950/40 border border-purple-500/30">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-purple-600/30 text-purple-400 font-black text-xs flex items-center justify-center">🚀</span>
                            <div>
                                <span class="text-[10px] text-purple-300 font-bold block font-bangla">রকেট (Send Money)</span>
                                <span class="font-mono font-black text-white text-xs">{{ $settings['rocket_number'] ?? '01912-345678-9' }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="copyToClipboard('{{ $settings['rocket_number'] ?? '01912-345678-9' }}', this)" class="px-2.5 py-1 rounded-lg bg-purple-600/30 hover:bg-purple-600/50 text-purple-200 text-[10px] font-bold transition-colors">
                            <i class="fa-solid fa-copy mr-1"></i> কপি
                        </button>
                    </div>

                    <!-- Bank Account -->
                    <div class="p-2.5 rounded-xl bg-blue-950/40 border border-blue-500/30 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-blue-300 font-bold flex items-center gap-1.5 font-bangla">
                                <i class="fa-solid fa-building-columns"></i> ব্যাংক হিসাব বিবরণ
                            </span>
                            <button type="button" onclick="copyToClipboard('{{ $settings['bank_details'] ?? '' }}', this)" class="px-2.5 py-1 rounded-lg bg-blue-600/30 hover:bg-blue-600/50 text-blue-200 text-[10px] font-bold transition-colors">
                                <i class="fa-solid fa-copy mr-1"></i> বিবরণ কপি
                            </button>
                        </div>
                        <p class="text-[11px] font-mono text-slate-200 leading-snug">{{ $settings['bank_details'] ?? 'Islami Bank Bangladesh Ltd, A/C: 20501234567890, Branch: Dhanmondi' }}</p>
                    </div>
                </div>

                <div class="p-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-200 font-bangla leading-relaxed">
                    <i class="fa-solid fa-hand-holding-dollar mr-1 text-amber-400"></i>
                    সকল কিস্তি ম্যানুয়ালি উপরোক্ত নম্বরে পাঠাতে হবে এবং প্রমাণের জন্য ট্রানজেকশন আইডি ও স্ক্রিনশট নিচে সংযুক্ত করে জমা দিতে হবে।
                </div>
            </div>
        </div>

        <form action="{{ route('payment.submit') }}" method="POST" enctype="multipart/form-data" onsubmit="showPreloader('পেমেন্ট ভাউচার স্লিপ জমা হচ্ছে...', 'উপলব্ধি সমবায় সমিতি')" class="space-y-4 text-xs font-bangla">
            @csrf
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">মোট টাকার পরিমাণ (BDT)</label>
                    <input type="number" id="pay_amount" name="amount" value="1000" min="100" step="500" required oninput="updateMonthsFromAmount(this.value)" class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold focus:border-brand-gold">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">কত মাসের কিস্তি</label>
                    <input type="number" id="pay_months_count" name="months_count" value="1" min="1" max="36" required oninput="updateAmountFromMonths(this.value)" class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold focus:border-brand-gold">
                </div>
            </div>

            <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] leading-relaxed">
                <i class="fa-solid fa-circle-info mr-1"></i>
                মাস নির্বাচন করার প্রয়োজন নেই। ক্যাশিয়ার আপনার জমা অনুযায়ী সবচেয়ে পুরাতন বকেয়া থেকে শুরু করে কিস্তি নিষ্পত্তি করবেন।
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">পেমেন্ট মাধ্যম</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-bold">
                        <option value="bkash">bKash (বিকাশ)</option>
                        <option value="nagad">Nagad (নগদ)</option>
                        <option value="rocket">Rocket (রকেট)</option>
                        <option value="bank">Bank Transfer (ব্যাংক)</option>
                        <option value="cash">Direct Cash (নগদ গ্রহণ)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">পরিশোধের তারিখ</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ট্রানজেকশন রেফারেন্স নম্বর (TrxID / Receipt)</label>
                <input type="text" name="reference_number" placeholder="যেমন: BK92837164 বা Bank Slip No" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono focus:border-brand-gold">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">পেমেন্ট রসিদ / স্ক্রিনশটের ছবি</label>
                <input type="file" name="proof_image" accept="image/*" class="w-full text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-gold file:text-slate-950 hover:file:bg-amber-400">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-600 text-slate-950 font-black text-sm shadow-xl transition-all cursor-pointer">
                পেমেন্ট ভাউচার জমা দিন
            </button>
        </form>
    </div>
</div>

<!-- Edit Payment Accounts Modal (Cashier & President) -->
<div id="editPaymentAccountsModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative animate-float">
        <button onclick="closeModal('editPaymentAccountsModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-amber-400"></i>
                <span>পেমেন্ট অ্যাকাউন্ট ও নম্বর ব্যবস্থাপনা</span>
            </h3>
            <p class="text-xs text-slate-400 font-bangla mt-1">
                ক্যাশিয়ার ও সভাপতি এখান থেকে বিকাশ, নগদ, রকেট ও ব্যাংক অ্যাকাউন্টের বিবরণ পরিবর্তন করতে পারবেন
            </p>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-pink-400 mb-1">bKash Personal Number (বিকাশ নম্বর)</label>
                <input type="text" name="bkash_number" value="{{ $settings['bkash_number'] ?? '01712-345678' }}" required placeholder="01712-345678" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold focus:border-pink-500">
            </div>

            <div>
                <label class="block font-bold text-orange-400 mb-1">Nagad Personal Number (নগদ নম্বর)</label>
                <input type="text" name="nagad_number" value="{{ $settings['nagad_number'] ?? '01812-345678' }}" required placeholder="01812-345678" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold focus:border-orange-500">
            </div>

            <div>
                <label class="block font-bold text-purple-400 mb-1">Rocket Number (রকেট নম্বর)</label>
                <input type="text" name="rocket_number" value="{{ $settings['rocket_number'] ?? '01912-345678-9' }}" required placeholder="01912-345678-9" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold focus:border-purple-500">
            </div>

            <div>
                <label class="block font-bold text-blue-400 mb-1">Bank Account Details (ব্যাংক হিসাব বিবরণ)</label>
                <textarea name="bank_details" rows="5" required placeholder="ব্যাংক নাম, অ্যাকাউন্ট নাম, অ্যাকাউন্ট নম্বর, শাখা..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono text-xs leading-relaxed focus:border-blue-500">{{ $settings['bank_details'] ?? 'Islami Bank Bangladesh Ltd, A/C: 20501234567890, Branch: Dhanmondi' }}</textarea>
            </div>


            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-sm shadow-xl transition-all cursor-pointer">
                পেমেন্ট অ্যাকাউন্ট তথ্য সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>

<!-- 2. Direct Cash Payment Entry Modal (Cashier & President Only) -->
<div id="directPaymentModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">

    <div class="bg-slate-900 border border-purple-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative animate-float">
        <button onclick="closeModal('directPaymentModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-dollar text-purple-400"></i>
                <span>সরাসরি নগদ কিস্তি এন্ট্রি (Cashier / President)</span>
            </h3>
            <p class="text-xs text-purple-200/80 font-bangla mt-1">
                কোনো সদস্য সরাসরি নগদ টাকা দিলে তার নামে কিস্তি এন্ট্রি করুন (স্বয়ংক্রিয়ভাবে অনুমোদিত হবে)
            </p>
        </div>

        <form action="{{ route('payment.direct') }}" method="POST" enctype="multipart/form-data" onsubmit="showPreloader('সরাসরি নগদ জমা প্রসেসিং হচ্ছে...', 'উপলব্ধি সমবায় সমিতি')" class="space-y-3.5 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-300 mb-1">কোন সদস্যের কিস্তি</label>
                <select name="member_email" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-bold">
                    @foreach($allMembers as $mem)
                    <option value="{{ $mem['email'] }}">{{ $mem['bangla_name'] }} ({{ $mem['role_label_bn'] }})</option>
                    @endforeach

                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">জমার পরিমাণ (BDT)</label>
                    <input type="number" name="amount" value="1000" min="100" step="500" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">মাসের সংখ্যা</label>
                    <input type="number" name="months_count" value="1" min="1" max="36" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">পেমেন্ট মাধ্যম</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-bold">
                        <option value="cash" selected>Direct Cash (নগদ গ্রহণ)</option>
                        <option value="bkash">bKash (বিকাশ)</option>
                        <option value="nagad">Nagad (নগদ)</option>
                        <option value="bank">Bank Transfer (ব্যাংক)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">তারিখ</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">রসিদ / মানি রিসিট নম্বর</label>
                <input type="text" name="reference_number" value="CASH-REC-{{ date('Ymd') }}-{{ rand(100,999) }}" required class="w-full px-3 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-black text-sm shadow-xl transition-all">
                নগদ কিস্তি সংরক্ষণ ও অনুমোদন করুন
            </button>
        </form>
    </div>
</div>

<!-- 3. Approve & Adjust Modal for Cashier / Super Admin -->
<div id="approveModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('approveModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                <span>পেমেন্ট যাচাই ও অনুমোদন</span>
            </h3>
            <p id="approve_member_info" class="text-xs text-emerald-300 font-bangla mt-1"></p>
        </div>

        <div id="approve_proof_container" class="hidden mb-4 p-2 rounded-2xl bg-slate-950 border border-white/10 flex flex-col items-center">
            <img id="approve_proof_img" src="" alt="Proof" class="max-h-48 object-contain rounded-xl">
            <span class="text-[10px] text-slate-400 mt-1 font-mono">সংযুক্ত পেমেন্ট রসিদ / স্ক্রিনশট</span>
        </div>

        <form id="approveForm" method="POST" class="space-y-4 text-xs font-bangla">
            @csrf
            <input type="hidden" name="action" value="approve">

            <div class="p-3 rounded-xl bg-white/5 border border-white/10 space-y-2">
                <p class="text-slate-300 text-[11px]">
                    রসিদ অনুযায়ী প্রয়োজনে টাকার পরিমাণ ও কিস্তির মাস সমন্বয় করুন:
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">অনুমোদিত টাকা (BDT)</label>
                        <input type="number" id="approve_adjusted_amount" name="adjusted_amount" min="100" step="500" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-300 mb-1">অনুমোদিত মাসের সংখ্যা</label>
                        <input type="number" id="approve_adjusted_months" name="adjusted_months_count" min="1" max="36" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-colors shadow-lg">
                অনুমোদন ও লেজারে অন্তর্ভুক্ত করুন
            </button>
        </form>
    </div>
</div>

<!-- 4. Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-rose-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
        <button onclick="closeModal('rejectModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4 text-rose-400">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>পেমেন্ট বাতিলকরণ</span>
            </h3>
            <p class="text-xs text-slate-400 font-bangla mt-1">পেমেন্ট বাতিলের সুনির্দিষ্ট কারণ উল্লেখ করুন যা সদস্য দেখতে পারবেন</p>
        </div>

        <form id="rejectForm" method="POST" class="space-y-4 text-xs font-bangla">
            @csrf
            <input type="hidden" name="action" value="reject">

            <div>
                <label class="block font-bold text-slate-300 mb-1">বাতিল করার কারণ (Rejection Reason)</label>
                <textarea name="rejection_reason" rows="5" required placeholder="যেমন: ট্রানজেকশন আইডি মেলেনি বা রসিদ অস্পষ্ট।" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed focus:border-rose-500"></textarea>
            </div>


            <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition-colors shadow-lg">
                পেমেন্ট বাতিল নিশ্চিত করুন
            </button>
        </form>
    </div>
</div>

<!-- 5. Add / Edit Project Modals (President & Vice President) -->
<div id="addProjectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('addProjectModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-briefcase text-emerald-400"></i>
                <span>নতুন বিনিয়োগ প্রকল্প যোগ করুন</span>
            </h3>
        </div>

        <form action="{{ route('project.create') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-300 mb-1">প্রকল্পের নাম (বাংলা)</label>
                <input type="text" name="title_bn" required placeholder="যেমন: উপলব্ধি এগ্রো ও ডেইরি ফার্ম" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Project Title (English)</label>
                <input type="text" name="title" required placeholder="e.g. USS Agro & Dairy Project" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">অবস্থা (Status)</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-bold">
                        <option value="ongoing">চলমান (Ongoing)</option>
                        <option value="planned">পরিকল্পনাধীন (Planned)</option>
                        <option value="completed">সম্পন্ন (Completed)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">বিনিয়োগ বাজেট</label>
                    <input type="text" name="target_amount" placeholder="যেমন: ৫,০০,০০০ ৳" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">মেয়াদকাল (Timeline)</label>
                    <input type="text" name="timeline" value="২০২৫ - ২০২৬" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">স্থান (Location)</label>
                    <input type="text" name="location" value="ঢাকা, বাংলাদেশ" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">অনলাইন ছবির লিংক (Image URL)</label>
                <input type="text" name="image_url" placeholder="https://images.unsplash.com/photo-..." class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">অথবা ছবি আপলোড করুন</label>
                <input type="file" name="image_file" accept="image/*" class="w-full text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-600 file:text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">বিবরণ (বাংলা)</label>
                <textarea name="description_bn" rows="5" placeholder="প্রকল্পের বিস্তারিত বিবরণ..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Description (English)</label>
                <textarea name="description" rows="5" placeholder="Project details in English..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg mt-2">
                প্রকল্প প্রকাশ করুন
            </button>
        </form>
    </div>
</div>

<div id="editProjectModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('editProjectModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-pen text-amber-400"></i>
                <span>বিনিয়োগ প্রকল্প সম্পাদনা</span>
            </h3>
        </div>

        <form id="editProjectForm" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-300 mb-1">প্রকল্পের নাম (বাংলা)</label>
                <input type="text" id="edit_proj_title_bn" name="title_bn" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Project Title (English)</label>
                <input type="text" id="edit_proj_title" name="title" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">অবস্থা (Status)</label>
                    <select id="edit_proj_status" name="status" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-bold">
                        <option value="ongoing">চলমান (Ongoing)</option>
                        <option value="planned">পরিকল্পনাধীন (Planned)</option>
                        <option value="completed">সম্পন্ন (Completed)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">বিনিয়োগ বাজেট</label>
                    <input type="text" id="edit_proj_target_amount" name="target_amount" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">মেয়াদকাল</label>
                    <input type="text" id="edit_proj_timeline" name="timeline" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">স্থান</label>
                    <input type="text" id="edit_proj_location" name="location" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ছবির লিংক (Image URL)</label>
                <input type="text" id="edit_proj_image_url" name="image_url" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">বিবরণ (বাংলা)</label>
                <textarea id="edit_proj_desc_bn" name="description_bn" rows="5" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Description (English)</label>
                <textarea id="edit_proj_desc_en" name="description" rows="5" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>


            <button type="submit" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg mt-2">
                হালনাগাদ সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>

<!-- 6. Add / Edit Rule Modals -->
<div id="addRuleModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-brand-gold/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('addRuleModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-brand-gold"></i>
                <span>নতুন নীতিমালা / ধারা যোগ করুন</span>
            </h3>
        </div>

        <form action="{{ route('rule.create') }}" method="POST" class="space-y-3.5 text-xs font-bangla">
            @csrf
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">ধারা নম্বর</label>
                    <input type="text" name="rule_number" value="ধারা {{ count($rules) + 1 }}" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">ক্রম (Order)</label>
                    <input type="number" name="order" value="{{ count($rules) + 1 }}" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ধারার শিরোনাম (বাংলা)</label>
                <input type="text" name="title_bn" required placeholder="যেমন: সাধারণ সভার সিদ্ধান্ত গ্রহণ" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Title (English)</label>
                <input type="text" name="title_en" required placeholder="e.g. General Meeting Decisions" class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ধারার বিবরণ (বাংলা)</label>
                <textarea name="description_bn" rows="5" required placeholder="ধারার পূর্ণ বিবরণ..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Description (English)</label>
                <textarea name="description_en" rows="5" required placeholder="Full description in English..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-gold hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg mt-2">
                ধারা প্রকাশ করুন
            </button>
        </form>
    </div>
</div>

<div id="editRuleModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-brand-gold/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('editRuleModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-brand-gold"></i>
                <span>নীতিমালা / ধারা সম্পাদনা</span>
            </h3>
        </div>

        <form id="editRuleForm" method="POST" class="space-y-3.5 text-xs font-bangla">
            @csrf
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">ধারা নম্বর</label>
                    <input type="text" id="edit_rule_number" name="rule_number" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">ক্রম</label>
                    <input type="number" id="edit_rule_order" name="order" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ধারার শিরোনাম (বাংলা)</label>
                <input type="text" id="edit_rule_title_bn" name="title_bn" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Title (English)</label>
                <input type="text" id="edit_rule_title_en" name="title_en" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ধারার বিবরণ (বাংলা)</label>
                <textarea id="edit_rule_desc_bn" name="description_bn" rows="5" required class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Description (English)</label>
                <textarea id="edit_rule_desc_en" name="description_en" rows="5" required class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>


            <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-gold hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg mt-2">
                হালনাগাদ সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>

<!-- 7. View Proof Image Modal -->
<div id="proofViewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md">
    <div class="bg-slate-900 border border-white/15 rounded-3xl p-6 max-w-xl w-full shadow-2xl relative">
        <button onclick="closeModal('proofViewModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h4 id="proof_modal_title" class="text-sm font-bold text-white font-bangla mb-3">পেমেন্ট রসিদ ভাউচার</h4>
        <div class="rounded-2xl overflow-hidden border border-white/10 bg-slate-950 flex items-center justify-center max-h-[70vh]">
            <img id="proof_modal_img" src="" alt="Proof" class="max-h-full max-w-full object-contain">
        </div>
        <p id="proof_modal_ref" class="text-xs text-amber-300 font-mono mt-3"></p>
    </div>
</div>

<!-- 8. Exemption Modal -->
<div id="exemptionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-purple-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
        <button onclick="closeModal('exemptionModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-heart text-purple-400"></i>
                <span>বকেয়া অবকাশ/মওকুফ মঞ্জুর</span>
            </h3>
            <p id="exemption_member_label" class="text-xs text-purple-300 font-bangla mt-1"></p>
        </div>

        <form id="exemptionForm" method="POST" class="space-y-4 text-xs font-bangla">
            @csrf
            <div>
                <label class="block font-bold text-slate-300 mb-1">মওকুফকৃত মাসের সংখ্যা</label>
                <input type="number" id="exemption_months_input" name="exemption_months" min="0" max="12" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white font-mono font-bold">
                <span id="exemption_max_hint" class="text-[10px] text-slate-500 block mt-1"></span>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">অবকাশের কারণ ও বিবরণ</label>
                <input type="text" name="exemption_reason" placeholder="যেমন: ব্যবসায়িক অবকাশ বা বিদেশে অবস্থান" required class="w-full px-3 py-2 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition-colors shadow-lg">
                অবকাশ মঞ্জুর করুন
            </button>
        </form>
    </div>
</div>

<!-- 9. Profile & Password Modal -->
<div id="profileModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-brand-gold/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('profileModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="text-xl font-black text-white font-bangla mb-4 flex items-center gap-2">
            <i class="fa-solid fa-user-gear text-brand-gold"></i>
            <span>প্রোফাইল ছবি ও সিকিউরিটি সেটিংস</span>
        </h3>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-bangla border-b border-white/10 pb-5 mb-5">
            @csrf
            
            <!-- Avatar Upload & Live Preview -->
            <div class="flex items-center gap-4 p-3.5 rounded-2xl bg-white/5 border border-white/10">
                <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-brand-gold bg-slate-950 flex-shrink-0 shadow-lg">
                    <img id="profile_avatar_preview" src="{{ $currentUser->get('avatar') ?: '/assets/images/avatar-default.svg' }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div class="space-y-1.5 flex-1">
                    <label class="block font-bold text-brand-gold text-xs">আপনার প্রোফাইল ছবি আপলোড করুন</label>
                    <input type="file" name="avatar_file" accept="image/*" onchange="previewProfileAvatar(this)" class="w-full text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-gold file:text-slate-950 hover:file:bg-amber-400 cursor-pointer">
                    <span class="text-[10px] text-slate-400 block">এই ছবিটি ওয়েবসাইটে ও ড্যাশবোর্ডে প্রদর্শিত হবে (JPG/PNG/WebP, সর্বোচ্চ ১০ মেগাবাইট)</span>
                </div>
            </div>

            <h5 class="font-bold text-amber-300 uppercase tracking-wider text-[11px] pt-1">ব্যক্তিগত তথ্য</h5>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 mb-1">নাম (English)</label>
                    <input type="text" name="name" value="{{ $currentUser->get('name') }}" required class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">নাম (বাংলা)</label>
                    <input type="text" name="bangla_name" value="{{ $currentUser->get('bangla_name') }}" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 mb-1">মোবাইল নম্বর</label>
                <input type="text" name="phone" value="{{ $currentUser->get('phone') }}" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-lg transition-all cursor-pointer">
                <i class="fa-solid fa-floppy-disk mr-1"></i> ছবি ও তথ্য সংরক্ষণ করুন
            </button>
        </form>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-3 text-xs font-bangla">
            @csrf
            <h5 class="font-bold text-rose-300 uppercase tracking-wider text-[11px]">পাসওয়ার্ড পরিবর্তন</h5>
            
            <div>
                <label class="block text-slate-300 mb-1">বর্তমান পাসওয়ার্ড</label>
                <input type="password" name="current_password" required class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 mb-1">নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password" minlength="6" required class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
                <div>
                    <label class="block text-slate-300 mb-1">পুনরায় নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation" minlength="6" required class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
                </div>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition-colors shadow-lg cursor-pointer">
                পাসওয়ার্ড পরিবর্তন করুন
            </button>
        </form>
    </div>
</div>


<!-- Add Announcement / Royal Decree Modal -->
<div id="addAnnouncementModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('addAnnouncementModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-scroll text-amber-400"></i>
                <span>নতুন রাজকীয় সমবায় ফরমান / নোটিশ জারি</span>
            </h3>
            <p class="text-xs text-slate-400 font-bangla mt-1">সমিতির সদস্যদের ও ওয়েবসাইটের জন্য বিশেষ নোটিশ বা ফরমান প্রকাশ করুন।</p>
        </div>

        <form action="{{ route('announcement.create') }}" method="POST" class="space-y-3.5 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-300 mb-1">ফরমানের শিরোনাম (বাংলা) *</label>
                <input type="text" name="title_bn" required placeholder="e.g. বার্ষিক সাধারণ সভা ও তহবিল সংক্রান্ত ঘোষণাপত্র" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Announcement Title (English)</label>
                <input type="text" name="title_en" placeholder="e.g. Annual General Meeting & Fund Proclamation" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">জারি তারিখ / Timeline Date</label>
                <input type="date" name="announcement_date" value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">ফরমানের মূল বক্তব্য ও বিবরণ (বাংলা) *</label>
                <textarea name="body_bn" rows="5" required placeholder="বিস্তারিত রাজকীয় আদেশ বা সমবায় নির্দেশনা লিখুন..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Decree Body (English)</label>
                <textarea name="body_en" rows="5" placeholder="Optional English proclamation text..." class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>

            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-200">
                <i class="fa-solid fa-crown mr-1 text-amber-400"></i>
                আপনার ভূমিকা ({{ $userRole === 'superadmin' ? 'সভাপতি' : ($userRole === 'cashier' ? 'ক্যাশিয়ার' : 'সহ-সভাপতি') }}) অনুসারে ফরমানটি যথাক্রমে অগ্রাধিকার ক্রমানুসারে ওয়েবসাইটে প্রদর্শিত হবে।
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 text-white font-black text-xs shadow-xl transition-all cursor-pointer">
                <i class="fa-solid fa-feather-pointed mr-1"></i> ফরমান জারি ও প্রকাশ করুন
            </button>
        </form>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeModal('editAnnouncementModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h3 class="text-xl font-black text-white font-bangla flex items-center gap-2">
                <i class="fa-solid fa-pen-nib text-amber-400"></i>
                <span>ফরমান / ঘোষণাপত্র সম্পাদনা</span>
            </h3>
        </div>

        <form id="editAnnouncementForm" method="POST" class="space-y-3.5 text-xs font-bangla">
            @csrf
            
            <div>
                <label class="block font-bold text-slate-300 mb-1">ফরমানের শিরোনাম (বাংলা) *</label>
                <input type="text" id="edit_ann_title_bn" name="title_bn" required class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Announcement Title (English)</label>
                <input type="text" id="edit_ann_title_en" name="title_en" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">জারি তারিখ</label>
                <input type="date" id="edit_ann_date" name="announcement_date" class="w-full px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-mono">
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">মূল বক্তব্য (বাংলা) *</label>
                <textarea id="edit_ann_body_bn" name="body_bn" rows="5" required class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white leading-relaxed"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-300 mb-1">Body (English)</label>
                <textarea id="edit_ann_body_en" name="body_en" rows="5" class="w-full min-h-[120px] px-3.5 py-2.5 bg-slate-800 border border-white/15 rounded-xl text-white font-sans leading-relaxed"></textarea>
            </div>


            <button type="submit" class="w-full py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-xl transition-all cursor-pointer">
                <i class="fa-solid fa-floppy-disk mr-1"></i> হালনাগাদ সংরক্ষণ করুন
            </button>
        </form>
    </div>
</div>

<!-- Official Somiti Money Receipt Modal (Immutable & Permanent) -->
<div id="receiptModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-brand-gold/40 rounded-3xl p-5 sm:p-8 max-w-2xl w-full shadow-2xl relative max-h-[92vh] overflow-y-auto text-slate-200 animate-float">
        <button onclick="closeModal('receiptModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg no-print">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Printable Money Receipt Area -->
        <div id="printableReceipt" class="space-y-6 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 border border-brand-gold/30 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
            <!-- Background Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                <img src="/assets/images/user-logo.jpg" alt="USS Watermark" class="w-96 h-96 object-contain">
            </div>

            <!-- Receipt Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b-2 border-brand-gold/30 pb-5">
                <div class="flex items-center gap-3 text-center sm:text-left">
                    <div class="w-16 h-16 rounded-2xl bg-white p-1 border-2 border-brand-gold/50 shadow-md flex-shrink-0">
                        <img src="/assets/images/user-logo.jpg" alt="USS Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-white font-bangla">উপলব্ধি সমবায় সমিতি (USS)</h2>
                        <p class="text-xs text-brand-gold font-bangla">সত্যের পথে স্বপ্নের অভিযান — স্থাপিত ২০২০</p>
                        <p class="text-[10px] text-slate-400 font-mono mt-0.5">Govt Reg. Track &bull; Flat-File Transparent Financial Ledger</p>
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 text-xs font-bold font-bangla inline-block mb-1">
                        <i class="fa-solid fa-certificate mr-1"></i> অফিসিয়াল মানি রসিদ
                    </span>
                    <span id="rec_number" class="font-mono text-sm sm:text-base font-black text-brand-gold tracking-wider block">USS-REC-2025-XXXX</span>
                </div>
            </div>

            <!-- Meta Details Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-white/[0.03] p-3.5 rounded-xl border border-white/10 font-bangla">
                <div>
                    <span class="text-[10px] text-slate-400 block">জমার তারিখ:</span>
                    <span id="rec_payment_date" class="font-bold text-white font-mono">-</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block">অনুমোদনের তারিখ:</span>
                    <span id="rec_reviewed_at" class="font-bold text-emerald-400 font-mono">-</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block">পেমেন্ট মাধ্যম:</span>
                    <span id="rec_payment_method" class="font-bold text-white uppercase">-</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block">TrxID / রেফারেন্স:</span>
                    <span id="rec_reference" class="font-bold text-amber-300 font-mono text-[11px]">-</span>
                </div>
            </div>

            <!-- Member Info Section -->
            <div class="border border-white/10 rounded-xl p-4 bg-slate-900/60 font-bangla space-y-2 text-xs">
                <div class="flex items-center justify-between border-b border-white/10 pb-2">
                    <span class="text-slate-400">সদস্যের নাম ও পদবী:</span>
                    <span id="rec_member_name" class="font-black text-white text-sm">সজিব মোল্লা</span>
                </div>
                <div class="flex items-center justify-between border-b border-white/10 pb-2">
                    <span class="text-slate-400">ইমেইল ও মেম্বার আইডি:</span>
                    <span id="rec_member_email" class="font-mono text-slate-300">sajib@upolobdi.org</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">পরিশোধিত কিস্তির মাস:</span>
                    <span id="rec_payment_months" class="font-bold text-amber-300">জানুয়ারি ২০২৫</span>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-gradient-to-r from-emerald-950/60 via-slate-900 to-emerald-950/60 border-2 border-emerald-500/40 rounded-2xl p-4 sm:p-5 text-center space-y-1.5 shadow-xl">
                <span class="text-xs text-emerald-300 font-bangla font-semibold block">পরিশোধিত মোট জমার পরিমাণ</span>
                <h3 id="rec_amount" class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono">১,০০০ BDT</h3>
                <p id="rec_amount_words" class="text-xs text-slate-300 font-bangla italic">কথায়: এক হাজার টাকা মাত্র</p>
            </div>

            <!-- Verification & Digital Somiti Seal Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 items-end">
                <div class="space-y-1 text-xs font-bangla">
                    <span class="text-[10px] text-slate-400 block">যাচাইকারী ও অনুমোদনকারী:</span>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span id="rec_reviewed_by" class="font-bold text-white">সাইফুল ইসলাম (ক্যাশিয়ার)</span>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono block">সত্যায়ন কোড: <span id="rec_auth_code" class="text-amber-300">VERIFIED-PERM</span></span>
                </div>

                <div class="text-right">
                    <div class="inline-block border-2 border-emerald-500/50 rounded-xl px-3.5 py-2 bg-emerald-950/40 text-center font-mono text-[10px] text-emerald-300 shadow-lg">
                        <div class="font-black uppercase tracking-wider">★ OFFICIAL AUDIT ★</div>
                        <div class="text-[9px] text-emerald-400 font-bangla">উপলব্ধি সমবায় সমিতি</div>
                        <div class="text-[8px] text-slate-400">IMMUTABLE RECEIPT</div>
                    </div>
                </div>
            </div>

            <!-- Footer Notice -->
            <div class="border-t border-white/10 pt-3 text-center text-[10px] text-slate-500 font-bangla">
                * এটি উপলব্ধি সমবায় সমিতির সফটওয়্যার-জেনারেটেড অফিশিয়াল মানি রসিদ। তথ্য স্থায়ীভাবে সংরক্ষিত।
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-3 mt-6 no-print">
            <div class="flex items-center gap-2">
                <button type="button" onclick="printReceipt()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-brand-gold to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 shadow-lg hover:scale-105 transition-all cursor-pointer">
                    <i class="fa-solid fa-print"></i>
                    <span>রসিদ প্রিন্ট / PDF</span>
                </button>
                <a id="rec_direct_link" href="#" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/30 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>ফুল পেজ / ডাউনলোড</span>
                </a>
            </div>

            <button type="button" onclick="closeModal('receiptModal')" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-colors cursor-pointer">
                বন্ধ করুন
            </button>
        </div>
    </div>
</div>

<!-- Proof Voucher Viewer Modal -->
<div id="proofViewModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md">
    <div class="bg-slate-900 border border-white/15 rounded-3xl p-6 max-w-lg w-full shadow-2xl relative animate-float">
        <button onclick="closeModal('proofViewModal')" class="absolute top-5 right-5 text-slate-400 hover:text-white text-lg cursor-pointer">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-4">
            <h4 id="proof_modal_title" class="text-base font-bold text-white font-bangla">পেমেন্ট ভাউচার প্রমাণ</h4>
            <span id="proof_modal_ref" class="text-xs text-brand-gold font-mono block mt-0.5"></span>
        </div>

        <div class="max-h-[65vh] overflow-hidden rounded-2xl border border-white/10 bg-slate-950 flex items-center justify-center p-2">
            <img id="proof_modal_img" src="" alt="Proof Voucher" class="max-h-[60vh] max-w-full object-contain rounded-xl">
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    const monthlyRateGlobal = {{ (int)($settings['monthly_installment'] ?? 1000) }};

    function updateMonthsFromAmount(amount) {
        const amt = parseInt(amount, 10) || monthlyRateGlobal;
        const months = Math.max(1, Math.round(amt / monthlyRateGlobal));
        const monthsInput = document.getElementById('pay_months_count');
        if (monthsInput) {
            monthsInput.value = months;
        }
    }

    function updateAmountFromMonths(months) {
        const m = parseInt(months, 10) || 1;
        const amtInput = document.getElementById('pay_amount');
        if (amtInput) {
            amtInput.value = m * monthlyRateGlobal;
        }
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (m) {
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    }

    function openPaymentModal() {
        const m = document.getElementById('paymentModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openDirectPaymentModal() {
        const m = document.getElementById('directPaymentModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openPaymentModalWithDue(amount, monthsCount) {
        document.getElementById('pay_amount').value = amount;
        document.getElementById('pay_months_count').value = monthsCount;
        openPaymentModal();
    }

    function openProfileModal() {
        const m = document.getElementById('profileModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openApproveModal(id, memberName, amount, monthsCount, ref, proofUrl) {
        const form = document.getElementById('approveForm');
        form.action = `/payment/review/${id}`;
        document.getElementById('approve_member_info').innerText = `সদস্য: ${memberName} | TrxID: ${ref}`;
        document.getElementById('approve_adjusted_amount').value = amount;
        document.getElementById('approve_adjusted_months').value = monthsCount;

        const proofContainer = document.getElementById('approve_proof_container');
        const proofImg = document.getElementById('approve_proof_img');
        if (proofUrl && proofUrl.length > 2) {
            proofImg.src = proofUrl;
            proofContainer.classList.remove('hidden');
        } else {
            proofContainer.classList.add('hidden');
        }

        const m = document.getElementById('approveModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openRejectModal(paymentId, memberName, amount) {
        const form = document.getElementById('rejectForm');
        form.action = `/payment/review/${paymentId}`;
        const m = document.getElementById('rejectModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openExemptionModal(email, name, currentMonths, maxLimit) {
        const form = document.getElementById('exemptionForm');
        form.action = `/member/exemption/${email}`;
        document.getElementById('exemption_member_label').innerText = `সদস্য: ${name} (${email})`;
        const input = document.getElementById('exemption_months_input');
        input.max = maxLimit;
        input.value = currentMonths || 0;
        document.getElementById('exemption_max_hint').innerText = `আপনার ভূমিকা অনুযায়ী সর্বোচ্চ সীমা: ${maxLimit} মাস`;
        
        const m = document.getElementById('exemptionModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openAddProjectModal() {
        const m = document.getElementById('addProjectModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openEditProjectModal(id, title, titleBn, status, targetAmount, timeline, location, image, desc, descBn) {
        const form = document.getElementById('editProjectForm');
        form.action = `/project/update/${id}`;
        document.getElementById('edit_proj_title').value = title;
        document.getElementById('edit_proj_title_bn').value = titleBn;
        document.getElementById('edit_proj_status').value = status;
        document.getElementById('edit_proj_target_amount').value = targetAmount;
        document.getElementById('edit_proj_timeline').value = timeline;
        document.getElementById('edit_proj_location').value = location;
        document.getElementById('edit_proj_image_url').value = image;
        document.getElementById('edit_proj_desc_en').value = desc;
        document.getElementById('edit_proj_desc_bn').value = descBn;

        const m = document.getElementById('editProjectModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openAddRuleModal() {
        const m = document.getElementById('addRuleModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openEditRuleModal(id, titleBn, titleEn, ruleNumber, order, descBn, descEn) {
        const form = document.getElementById('editRuleForm');
        form.action = `/rule/update/${id}`;
        document.getElementById('edit_rule_number').value = ruleNumber;
        document.getElementById('edit_rule_order').value = order;
        document.getElementById('edit_rule_title_bn').value = titleBn;
        document.getElementById('edit_rule_title_en').value = titleEn;
        document.getElementById('edit_rule_desc_bn').value = descBn;
        document.getElementById('edit_rule_desc_en').value = descEn;
        
        const m = document.getElementById('editRuleModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function viewProofModal(imageSrc, memberName, ref) {
        document.getElementById('proof_modal_img').src = imageSrc;
        document.getElementById('proof_modal_title').innerText = `রসিদ ভাউচার: ${memberName}`;
        document.getElementById('proof_modal_ref').innerText = `TrxID / Ref: ${ref}`;
        const m = document.getElementById('proofViewModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function togglePaymentAccountsGuide() {
        const card = document.getElementById('paymentAccountsGuideCard');
        const icon = document.getElementById('pay_guide_icon');
        if (card) {
            if (card.classList.contains('hidden')) {
                card.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            } else {
                card.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        }
    }

    function copyToClipboard(text, btn) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check mr-1 text-emerald-400"></i> কপি হয়েছে';
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 2000);
        }).catch(() => {
            alert('কপি হয়েছে: ' + text);
        });
    }

    function openAddAnnouncementModal() {
        const m = document.getElementById('addAnnouncementModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function openEditAnnouncementModal(id, titleBn, titleEn, bodyBn, bodyEn, date) {
        const form = document.getElementById('editAnnouncementForm');
        form.action = `/announcement/update/${id}`;
        document.getElementById('edit_ann_title_bn').value = titleBn;
        document.getElementById('edit_ann_title_en').value = titleEn;
        document.getElementById('edit_ann_body_bn').value = bodyBn;
        document.getElementById('edit_ann_body_en').value = bodyEn;
        document.getElementById('edit_ann_date').value = date;

        const m = document.getElementById('editAnnouncementModal');
        if (m) {
            m.classList.remove('hidden');
            m.classList.add('flex');
        }
    }

    function handleEditRule(btn) {
        try {
            const r = JSON.parse(btn.getAttribute('data-rule'));
            const form = document.getElementById('editRuleForm');
            form.action = `/rule/update/${r.id}`;
            document.getElementById('edit_rule_number').value = r.rule_number || '';
            document.getElementById('edit_rule_order').value = r.order || 1;
            document.getElementById('edit_rule_title_bn').value = r.title_bn || '';
            document.getElementById('edit_rule_title_en').value = r.title_en || '';
            document.getElementById('edit_rule_desc_bn').value = r.description_bn || '';
            document.getElementById('edit_rule_desc_en').value = r.description_en || '';
            
            const m = document.getElementById('editRuleModal');
            if (m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        } catch(e) {
            console.error('Error parsing rule json', e);
        }
    }

    function handleEditProject(btn) {
        try {
            const pr = JSON.parse(btn.getAttribute('data-project'));
            const form = document.getElementById('editProjectForm');
            form.action = `/project/update/${pr.id}`;
            document.getElementById('edit_proj_title').value = pr.title || '';
            document.getElementById('edit_proj_title_bn').value = pr.title_bn || '';
            document.getElementById('edit_proj_status').value = pr.status || 'ongoing';
            document.getElementById('edit_proj_target_amount').value = pr.target_amount || '';
            document.getElementById('edit_proj_timeline').value = pr.timeline || '';
            document.getElementById('edit_proj_location').value = pr.location || '';
            document.getElementById('edit_proj_image_url').value = pr.image || '';
            document.getElementById('edit_proj_desc_en').value = pr.description || '';
            document.getElementById('edit_proj_desc_bn').value = pr.description_bn || '';

            const m = document.getElementById('editProjectModal');
            if (m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        } catch(e) {
            console.error('Error parsing project json', e);
        }
    }

    function handleEditAnnouncement(btn) {
        try {
            const ann = JSON.parse(btn.getAttribute('data-announcement'));
            const form = document.getElementById('editAnnouncementForm');
            form.action = `/announcement/update/${ann.id}`;
            document.getElementById('edit_ann_title_bn').value = ann.title_bn || '';
            document.getElementById('edit_ann_title_en').value = ann.title_en || '';
            document.getElementById('edit_ann_body_bn').value = ann.body_bn || '';
            document.getElementById('edit_ann_body_en').value = ann.body_en || '';
            document.getElementById('edit_ann_date').value = ann.announcement_date || '';

            const m = document.getElementById('editAnnouncementModal');
            if (m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        } catch(e) {
            console.error('Error parsing announcement json', e);
        }
    }

    function handleOpenReceipt(btn) {
        try {
            const raw = btn.getAttribute('data-receipt');
            if (!raw) return;
            let data = {};
            try {
                data = JSON.parse(atob(raw));
            } catch(err) {
                data = JSON.parse(raw);
            }

            const recNum = data.receipt_number || ('USS-REC-' + (data.id ? String(data.id).substring(0,6).toUpperCase() : '2025-01'));
            document.getElementById('rec_number').innerText = recNum;
            document.getElementById('rec_payment_date').innerText = data.payment_date || '-';
            document.getElementById('rec_reviewed_at').innerText = data.reviewed_at || data.payment_date || '-';
            document.getElementById('rec_payment_method').innerText = data.payment_method_label || data.payment_method || '-';
            document.getElementById('rec_reference').innerText = data.reference_number || 'N/A';
            document.getElementById('rec_member_name').innerText = `${data.member_bangla_name || data.member_name} (${data.member_role_bn || 'সদস্য'})`;
            document.getElementById('rec_member_email').innerText = `${data.member_email} | ID: ${data.member_id || 'USS'}`;
            document.getElementById('rec_payment_months').innerText = data.payment_months || (data.months_count + ' মাস');
            
            const amt = Number(data.amount || 0);
            document.getElementById('rec_amount').innerText = `${amt.toLocaleString()} BDT`;
            document.getElementById('rec_amount_words').innerText = `কথায়: ${data.amount_words || 'টাকা মাত্র'}`;
            document.getElementById('rec_reviewed_by').innerText = data.reviewed_by || 'সাইফুল ইসলাম (ক্যাশিয়ার)';
            document.getElementById('rec_auth_code').innerText = 'AUDIT-' + (data.receipt_number ? data.receipt_number.replace('USS-REC-', '') : 'PERM');

            const directLink = document.getElementById('rec_direct_link');
            if (directLink && data.id) {
                directLink.href = `/payment/receipt/${data.id}`;
            }

            const m = document.getElementById('receiptModal');
            if (m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        } catch(e) {
            console.error('Error opening receipt modal:', e);
        }
    }

    function printReceipt() {
        showPreloader('অফিসিয়াল মানি রসিদ ডাউনলোড ও প্রিন্ট প্রিভিউ প্রস্তুত হচ্ছে...', 'উপলব্ধি সমবায় সমিতি (USS)');
        const printContent = document.getElementById('printableReceipt');
        if (!printContent) {
            hidePreloader();
            return;
        }
        const win = window.open('', '_blank', 'height=750,width=850');
        if (!win) {
            hidePreloader();
            // If popup blocked, navigate to direct page
            const directLink = document.getElementById('rec_direct_link');
            if (directLink && directLink.href && directLink.href !== '#') {
                window.open(directLink.href, '_blank');
            } else {
                window.print();
            }
            return;
        }
        win.document.write('<!DOCTYPE html><html><head><title>USS Money Receipt</title>');
        win.document.write('<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">');
        win.document.write('<script src="https://cdn.tailwindcss.com"></' + 'script>');
        win.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">');
        win.document.write('<style>* { font-family: "Hind Siliguri", "Plus Jakarta Sans", sans-serif; } @media print { body { background: #020617 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; } .no-print { display: none !important; } } body { background: #020617; color: white; padding: 24px; }</style>');
        win.document.write('</head><body>');
        win.document.write(printContent.outerHTML);
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        setTimeout(() => {
            win.print();
            win.close();
            hidePreloader();
        }, 700);
    }

    function previewProfileAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 10 * 1024 * 1024) {
                alert('ফাইলের সাইজ অনুমোদিত সীমার চেয়ে বেশি (সর্বোচ্চ ১০ মেগাবাইট)। অনুগ্রহ করে ছোট সাইজের ছবি নির্বাচন করুন।');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const prev = document.getElementById('profile_avatar_preview');
                if (prev) {
                    prev.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>



@endpush

