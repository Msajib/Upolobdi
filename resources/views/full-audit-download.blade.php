<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পূর্ণাঙ্গ আর্থিক অডিট ও ব্যালেন্স শীট | {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ $settings['custom_favicon'] ?? '/assets/images/user-logo.jpg' }}">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Hind Siliguri', 'Plus Jakarta Sans', sans-serif; }
        .font-bangla { font-family: 'Hind Siliguri', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #0f172a !important; padding: 0 !important; }
            .receipt-card { box-shadow: none !important; border: 2px solid #cbd5e1 !important; }
            .print-bg-fix { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen py-6 sm:py-10 px-3 sm:px-6 flex flex-col justify-between print-bg-fix">

    <!-- Top Action Navigation (Hidden in Print) -->
    <div class="max-w-5xl mx-auto w-full mb-6 flex items-center justify-between gap-3 no-print">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs flex items-center gap-2 shadow border border-white/10 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>ড্যাশবোর্ডে ফিরে যান</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 shadow-xl hover:scale-105 transition-all cursor-pointer">
                <i class="fa-solid fa-print"></i>
                <span>প্রিন্ট ও PDF সংরক্ষণ</span>
            </button>
        </div>
    </div>

    <!-- Official Full Financial Statement Card -->
    <div class="max-w-5xl mx-auto w-full receipt-card bg-slate-900 border border-amber-500/40 rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden text-slate-200">
        
        <!-- Background Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Watermark" class="w-96 h-96 object-contain">
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b-2 border-amber-500/40 pb-6 relative z-10">
            <div class="flex items-center gap-3.5 text-center sm:text-left">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white p-1 border-2 border-amber-500/60 shadow-lg flex-shrink-0">
                    <img src="{{ $settings['custom_logo'] ?? '/assets/images/user-logo.jpg' }}" alt="USS Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-white font-bangla">
                        {{ $settings['somiti_name_bn'] ?? 'উপলব্ধি সমবায় সমিতি (USS)' }}
                    </h2>
                    <p class="text-xs text-amber-400 font-bangla font-semibold">
                        {{ $settings['motto_bn'] ?? 'সত্যের পথে স্বপ্নের অভিযান' }} — স্থাপিত {{ $settings['est_year'] ?? '২০২০' }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">
                        {{ $settings['contact_address'] ?? 'ঢাকা, বাংলাদেশ' }} &bull; {{ $settings['contact_phone'] ?? '+880 1712-345678' }}
                    </p>
                </div>
            </div>

            <div class="text-center sm:text-right flex-shrink-0">
                <span class="px-3.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/40 text-xs font-bold font-bangla inline-block mb-1 shadow-sm">
                    <i class="fa-solid fa-scale-balanced mr-1 text-amber-400"></i> সমন্বিত আর্থিক অডিট খতিয়ান
                </span>
                <span class="font-mono text-xs text-slate-300 block mt-0.5">
                    অডিট তারিখ: {{ date('d F, Y') }}
                </span>
                <span class="font-mono text-[10px] text-emerald-400 block mt-0.5">
                    স্ট্যাটাস: অফিসিয়ালি অডিটেড ও অনুমোদিত
                </span>
            </div>
        </div>

        <!-- 1. EXECUTIVE BALANCE SUMMARY -->
        <div class="my-6 relative z-10 font-bangla">
            <h3 class="text-sm font-bold text-amber-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie"></i>
                <span>১. তহবিলের মূল সারসংক্ষেপ ও বর্তমান ব্যালেন্স (Executive Balance Summary)</span>
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center mb-3">
                <div class="p-3.5 rounded-2xl bg-emerald-950/40 border border-emerald-500/30">
                    <span class="text-[10px] text-emerald-300 font-bold block">মোট কিস্তি ও ফি জমা</span>
                    <span class="text-lg font-black text-emerald-400 font-mono mt-0.5 block">
                        {{ number_format($overview['total_collected'] ?? 0) }} BDT
                    </span>
                    <span class="text-[9px] text-slate-400 block mt-0.5">সদস্যদের পরিশোধিত মূলধন</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-sky-950/40 border border-sky-500/30">
                    <span class="text-[10px] text-sky-300 font-bold block">সর্বমোট বিনিয়োগ মুনাফা</span>
                    <span class="text-lg font-black text-sky-400 font-mono mt-0.5 block">
                        +{{ number_format($overview['total_profits'] ?? 0) }} BDT
                    </span>
                    <span class="text-[9px] text-slate-400 block mt-0.5">ব্যবসা/জমি/প্রকল্প থেকে আয়</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-rose-950/40 border border-rose-500/30">
                    <span class="text-[10px] text-rose-300 font-bold block">সর্বমোট ব্যয় ও খরচ</span>
                    <span class="text-lg font-black text-rose-400 font-mono mt-0.5 block">
                        -{{ number_format($overview['total_expenses'] ?? 0) }} BDT
                    </span>
                    <span class="text-[9px] text-slate-400 block mt-0.5">অনুমোদিত ব্যয় কর্তন</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-950/60 border-2 border-amber-500/60 shadow-lg">
                    <span class="text-[10px] text-amber-300 font-black block">সমিতির নীট বর্তমান তহবিল</span>
                    <span class="text-xl font-black text-amber-400 font-mono mt-0.5 block">
                        {{ number_format($overview['net_somiti_balance'] ?? 0) }} BDT
                    </span>
                    <span class="text-[9px] text-amber-200/80 block mt-0.5">হাতে নগদ / ব্যাংকে গচ্ছিত</span>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-950/60 border border-white/10 flex items-center justify-between text-xs text-slate-300 font-mono">
                <span>মোট অপরিশোধিত সদস্য বকেয়া: <strong class="text-rose-400 font-bold">{{ number_format($overview['total_due'] ?? 0) }} BDT</strong></span>
                <span>মাসিক কিস্তির বর্তমান হার: <strong class="text-white">{{ number_format($monthlyAmount) }} BDT</strong></span>
            </div>
        </div>

        <!-- 2. MEMBERS PAYMENT & DUE BREAKDOWN -->
        <div class="my-6 relative z-10 font-bangla">
            <h3 class="text-sm font-bold text-amber-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-users"></i>
                <span>২. সকল সদস্যের পরিশোধ ও বকেয়া তালিকা (Member Payments & Dues)</span>
            </h3>

            <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/60">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-white/10 text-slate-300 bg-white/5">
                            <th class="p-2.5">সদস্য</th>
                            <th class="p-2.5">পদবী</th>
                            <th class="p-2.5">পরিশোধিত টাকা</th>
                            <th class="p-2.5">পরিশোধিত মাস</th>
                            <th class="p-2.5">বকেয়া টাকা</th>
                            <th class="p-2.5">বকেয়া বিবরণ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($membersDue as $md)
                        <tr>
                            <td class="p-2.5 font-bold text-white">
                                {{ $md['bangla_name'] }}
                                <span class="block text-[10px] text-slate-500 font-mono">{{ $md['email'] }}</span>
                            </td>
                            <td class="p-2.5 text-slate-300">{{ $md['designation'] ?? $md['role'] }}</td>
                            <td class="p-2.5 font-mono font-bold text-emerald-400">{{ number_format($md['total_paid_amount']) }} BDT</td>
                            <td class="p-2.5 font-mono text-slate-300">{{ $md['total_paid_months'] }} মাস</td>
                            <td class="p-2.5 font-mono font-bold {{ $md['total_due_amount'] > 0 ? 'text-rose-400' : 'text-slate-400' }}">
                                {{ number_format($md['total_due_amount']) }} BDT
                            </td>
                            <td class="p-2.5 text-[11px] text-slate-300">
                                @if($md['is_exempted'])
                                    <span class="text-purple-300">{{ $md['exemption_months'] }} মাস অবকাশপ্রাপ্ত</span>
                                @elseif($md['total_due_amount'] > 0)
                                    <span class="text-rose-300">{{ $md['range_bn'] }}</span>
                                @else
                                    <span class="text-emerald-400">পরিশোধিত</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. INVESTMENT PROFITS LEDGER -->
        <div class="my-6 relative z-10 font-bangla">
            <h3 class="text-sm font-bold text-sky-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i>
                <span>৩. বিনিয়োগ লভ্যাংশ ও মুনাফা খতিয়ান (Investment Profits Ledger)</span>
            </h3>

            @if(count($profits) === 0)
                <div class="p-4 rounded-xl bg-slate-950/40 border border-white/5 text-center text-xs text-slate-500">
                    কোনো বিনিয়োগ মুনাফা রেকর্ড নেই।
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/60">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-300 bg-white/5">
                                <th class="p-2.5">তারিখ</th>
                                <th class="p-2.5">খাত ও বিবরণ</th>
                                <th class="p-2.5">ধরন</th>
                                <th class="p-2.5">রেফারেন্স / চুক্তি</th>
                                <th class="p-2.5 text-right">মুনাফার পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 font-mono">
                            @foreach($profits as $prf)
                            <tr>
                                <td class="p-2.5 text-slate-400">{{ $prf['profit_date'] }}</td>
                                <td class="p-2.5 font-bangla">
                                    <span class="font-bold text-white block">{{ $prf['title'] }}</span>
                                    <span class="text-[10px] text-sky-300">{{ $prf['source_category_label'] }}</span>
                                </td>
                                <td class="p-2.5 font-bangla text-slate-300 text-[11px]">{{ $prf['profit_type_label'] }}</td>
                                <td class="p-2.5 text-slate-400 text-[11px]">{{ $prf['reference_number'] }}</td>
                                <td class="p-2.5 text-right font-bold text-sky-400">
                                    +{{ number_format($prf['amount']) }} BDT
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-white/5 font-bold font-bangla">
                                <td colspan="4" class="p-2.5 text-white">সর্বমোট বিনিয়োগ মুনাফা:</td>
                                <td class="p-2.5 text-right font-mono text-sky-400">
                                    +{{ number_format($overview['total_profits'] ?? 0) }} BDT
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        <!-- 4. OPERATING EXPENSES LEDGER -->
        <div class="my-6 relative z-10 font-bangla">
            <h3 class="text-sm font-bold text-rose-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-receipt"></i>
                <span>৪. অনুমোদিত খরচ ও ব্যয় খতিয়ান (Operating Expenses Ledger)</span>
            </h3>

            @if(count($expenses) === 0)
                <div class="p-4 rounded-xl bg-slate-950/40 border border-white/5 text-center text-xs text-slate-500">
                    কোনো খরচের রেকর্ড নেই।
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-950/60">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-300 bg-white/5">
                                <th class="p-2.5">তারিখ</th>
                                <th class="p-2.5">খরচের শিরোনাম</th>
                                <th class="p-2.5">ক্যাটাগরি</th>
                                <th class="p-2.5">রেকর্ডকারী</th>
                                <th class="p-2.5 text-right">খরচের পরিমাণ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 font-mono">
                            @foreach($expenses as $exp)
                            <tr>
                                <td class="p-2.5 text-slate-400">{{ $exp['expense_date'] }}</td>
                                <td class="p-2.5 font-bangla">
                                    <span class="font-bold text-white block">{{ $exp['title'] }}</span>
                                    @if(!empty($exp['description']))
                                    <span class="text-[10px] text-slate-400 block">{{ $exp['description'] }}</span>
                                    @endif
                                </td>
                                <td class="p-2.5 font-bangla text-slate-300 text-[11px]">{{ $exp['category'] }}</td>
                                <td class="p-2.5 font-bangla text-slate-400 text-[11px]">{{ $exp['recorded_by'] ?? $exp['created_by_name'] ?? 'কমিটি সদস্য' }}</td>
                                <td class="p-2.5 text-right font-bold text-rose-400">
                                    -{{ number_format($exp['amount']) }} BDT
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-white/10 bg-white/5 font-bold font-bangla">
                                <td colspan="4" class="p-2.5 text-white">সর্বমোট খরচ ও ব্যয়:</td>
                                <td class="p-2.5 text-right font-mono text-rose-400">
                                    -{{ number_format($overview['total_expenses'] ?? 0) }} BDT
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>

        <!-- 5. SIGNATURES & AUDIT CERTIFICATION -->
        <div class="border-t border-white/10 pt-8 mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center font-bangla relative z-10">
            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[অনুমোদিত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">সজিব মোল্লা</p>
                    <p class="text-[10px] text-amber-400">সভাপতি</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[যাচাইকৃত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">কাওসার আহমেদ</p>
                    <p class="text-[10px] text-amber-400">সাধারণ সম্পাদক</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>

            <div class="space-y-1">
                <div class="h-10 flex items-end justify-center">
                    <span class="text-xs font-mono text-emerald-400 font-bold italic">[যাচাইকৃত]</span>
                </div>
                <div class="border-t border-slate-600 pt-1.5">
                    <p class="text-xs font-bold text-white">রুবেল হোসেন</p>
                    <p class="text-[10px] text-amber-400">ক্যাশিয়ার ও অর্থ সম্পাদক</p>
                    <p class="text-[9px] text-slate-500">উপলব্ধি সমবায় সমিতি</p>
                </div>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="border-t border-white/10 pt-4 mt-6 text-center text-[10px] text-slate-500 font-bangla relative z-10">
            * এটি উপলব্ধি সমবায় সমিতি পোর্টালের স্বয়ংক্রিয়ভাবে অডিটেড সমন্বিত আর্থিক খতিয়ান।
        </div>
    </div>

</body>
</html>
